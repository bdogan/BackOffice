<?php

namespace BackOffice;

use BackOffice\Middleware\PageMiddleware;
use BackOffice\Model\Entity\Page;
use BackOffice\Model\Entity\Theme;
use BackOffice\Model\Table\PagesTable;
use Cake\Cache\Cache;
use Cake\Cache\Engine\FileEngine;
use Cake\Cache\Engine\RedisEngine;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Middleware\EncryptedCookieMiddleware;
use Cake\Http\ServerRequest;
use Cake\I18n\Time;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Utility\Text;

/**
 * Plugin for BackOffice
 */
class Plugin extends BasePlugin
{

	use InstanceConfigTrait;
	use EventDispatcherTrait;
	use LocatorAwareTrait;

	/**
	 * Crud methods
	 */
	const CRUD_METHODS = [
		'list' => [
			'method' => 'GET',
			'action' => 'index',
			'template' => ':template'
		],
		'create' => [
			'method' => [ 'GET', 'POST' ],
			'action' => 'create',
			'template' => ':template/create'
		],
		'update' => [
			'method' => [ 'GET', 'PUT' ],
			'action' => 'update',
			'template' => ':template/:id'
		],
		'delete' => [
			'method' => 'GET',
			'action' => 'delete',
			'template' => ':template/:id/delete'
		]
	];

	/**
	 * @var null|array PageList Memory Cache
	 */
	private $_pageList = null;

	/**
	 * @var PagesTable
	 */
	private $_pages;

	/**
	 * @var \BackOffice\Model\Table\ThemesTable
	 */
	private $_themes;

	/**
	 * @var \BackOffice\Model\Table\ThemeTemplatesTable
	 */
	private $_theme_templates;

	/**
	 * @var Theme;
	 */
	private $_active_theme;

	/**
	 * @var array
	 */
	private $_undefinedAction = [ 'controller' => 'UndefinedAction', 'action' => 'index', 'plugin' => 'BackOffice' ];

	/**
	 * @var array
	 */
	private $_defaultTheme = [ 'name' => 'Varsayılan', 'alias' => 'default' ];

	/**
	 * @var array Default Page Settings
	 */
	private $_defaultPageSettings = [
		'method' => 'GET',
		'data' => [
			'name' => 'Draft Page',
			'layout' => 'default',
			'template' => 'page'
		],
		'frozen' => [],
		'action' => [
			'prefix' => 'Frontend',
			'controller' => 'Pages',
			'action' => 'index',
			'plugin' => 'BackOffice'
		]
	];

	/**
	 * Config
	 *
	 * @var array
	 */
	private $_defaultConfig = [
		'rootPath' => '/_admin',
		'main_page' => [ 'title' => 'Dashboard', 'action' => [ '_name' => 'backoffice:dashboard.index' ] ],
		'storage' => [

		],
		'pages' => [
			/** Default pages */
			'main.index' => [ 'method' => 'GET', 'data' => [ 'slug' => '/', 'body' => 'Main page body', 'title' => 'Main Page', 'layout' => 'default', 'template' => 'page' ], 'frozen' => [ 'slug' ], 'action' => [ 'prefix' => 'Frontend', 'controller' => 'Pages', 'action' => 'index', 'plugin' => 'BackOffice' ] ],
		],
		'routes' => [
			/** BO Mandatory */
			'backoffice:dashboard.index' => [ 'method' => 'GET', 'template' => '/', 'action' => [ 'controller' => 'Dashboard', 'action' => 'index', 'plugin' => 'BackOffice' ] ],
			'backoffice:definitions.index' => [ 'method' => 'GET', 'template' => '/definitions', 'action' => [ 'controller' => 'Navigation', 'action' => 'index', 'plugin' => 'BackOffice' ] ],
			'backoffice:account.index' => [ 'method' => [ 'GET', 'PUT' ], 'template' => '/account', 'action' => [ 'controller' => 'Account', 'action' => 'index', 'plugin' => 'BackOffice' ] ],
			/** BO Pages */
			'backoffice:pages.index' => [ 'method' => 'GET', 'template' => '/pages', 'action' => [ 'controller' => 'Pages', 'action' => 'index', 'plugin' => 'BackOffice' ] ],
			'backoffice:pages.create' => [ 'method' => [ 'GET', 'POST' ], 'template' => '/pages/create', 'action' => [ 'controller' => 'Pages', 'action' => 'create', 'plugin' => 'BackOffice' ] ],
			'backoffice:pages.update' => [ 'method' => [ 'GET', 'PUT' ], 'template' => '/pages/:id', 'action' => [ 'controller' => 'Pages', 'action' => 'update', 'plugin' => 'BackOffice' ] ],
			'backoffice:pages.delete' => [ 'method' => 'GET', 'template' => '/pages/:id/delete', 'action' => [ 'controller' => 'Pages', 'action' => 'delete', 'plugin' => 'BackOffice' ] ],
			/** BO Auth */
			'backoffice:auth.login' => [ 'method' => [ 'GET', 'POST' ], 'template' => '/auth/login', 'action' => [ 'controller' => 'Auth', 'action' => 'login', 'plugin' => 'BackOffice' ] ],
			'backoffice:auth.logout' => [ 'method' => 'GET', 'template' => '/auth/logout', 'action' => [ 'controller' => 'Auth', 'action' => 'logout', 'plugin' => 'BackOffice' ] ],
			/** BO File Browser */
			'backoffice:file_browser.index' => [ 'method' => 'GET', 'template' => '/file_browser', 'action' => [ 'controller' => 'FileBrowser', 'action' => 'index', 'plugin' => 'BackOffice' ] ]
		],
		'auth' => [
			'authenticate' => [
				'Form' => [
					'userModel' => 'BackOffice.Users',
					'fields' => [ 'username' => 'email' ],
				],
			],
			'loginAction' => [ '_name' => 'backoffice:auth.login' ],
			'logoutAction' => [ '_name' => 'backoffice:auth.logout' ],
			'loginRedirect' => [ '_name' => 'backoffice:dashboard.index' ],
		],
		'menu' => [
			'_default' => [
				'main_page' => [ 'title' => 'Dashboard', 'exact' => true, 'icon' => 'home', 'action' => [ '_name' => 'backoffice:dashboard.index' ], 'order' => -2 ],
				'pages' => [ 'title' => 'Pages', 'icon' => 'library_books', 'action' => [ '_name' => 'backoffice:pages.index' ], 'order' => -1, 'children' => [
					[ 'title' => 'New Page', 'action' => [ '_name' => 'backoffice:pages.create' ] ],
				] ],
				'file_browser' => [ 'title' => 'File Browser', 'exact' => true, 'icon' => 'file_copy', 'action' => [ '_name' => 'backoffice:file_browser.index' ], 'order' => 99999 ],
				'definitions' => [ 'title' => 'Definitions', 'icon' => 'dvr', 'action' => [ '_name' => 'backoffice:definitions.index' ], 'order' => 99999, 'children' => [

				] ],
			],
		],
	];

	/**
	 * Plugin constructor.
	 *
	 * @param array $options
	 */
	public function __construct( array $options = [] ) {

		// Set options
		$this->setConfig($options);

		// Parent constructor
		parent::__construct( $options );

	}

	/**
	 * @inheritDoc
	 *
	 * @param PluginApplicationInterface $app
	 */
	public function bootstrap( PluginApplicationInterface $app )
	{
		// Set cache config
		Cache::setConfig('bo_shared', [
			'className' => RedisEngine::class,
			'duration' => '+999 days',
			'prefix' => 'bo_',
			'groups' => [ 'backoffice' ],
			'fallback' => 'default'
		] + Configure::read('Redis', []));
		Cache::setConfig('bo_file', [
			'className' => FileEngine::class,
			'duration' => '+999 days',
			'prefix' => 'bo_',
			'groups' => [ 'backoffice' ]
		] + Configure::read('Redis', []));

		// Set config
		Configure::write('BackOffice', $this);

		// Set tables
		$this->_pages = $this->getTableLocator()->get('BackOffice.Pages');
		$this->_themes = $this->getTableLocator()->get('BackOffice.Themes');
		$this->_theme_templates = $this->getTableLocator()->get('BackOffice.ThemeTemplates');

		// Add twig view plugin
		$app->addPlugin('WyriHaximus/TwigView', [ 'bootstrap' => true ]);

		// Fire event
		$this->dispatchEvent('BackOffice.ready', [ 'config' => $this->getConfig() ]);
	}

	/**
	 * Get active menu by given zone
	 *
	 * @param $zone
	 *
	 * @return array|bool
	 */
	public function getActiveMenu($zone = '_default')
	{
		$zone = is_array($zone) ? $zone : $this->getMenu($zone);
		foreach ($zone as $name => $menu) {
			if ($this->isActiveMenu($menu)) {
				return [ '_name' => $name ] + $menu;
			}
		}
		return false;
	}

	/**
	 * Check is active menu
	 *
	 * @param $menu
	 *
	 * @return bool
	 */
	public function isActiveMenu($menu)
	{
		$menu += [ 'exact' => false ];
		return $menu['exact'] ? Router::url($menu['action']) === Router::url(null) : strpos(Router::url(null), Router::url($menu['action'])) === 0;
	}

	/**
	 * @return array|\BackOffice\Model\Entity\Theme|\Cake\Datasource\EntityInterface|null
	 */
	public function getActiveTheme($cached = true)
	{
		if ($cached === true && $this->_active_theme) return $this->_active_theme;

		$activeTheme = $this->_themes->find()->where([ 'is_active' => 1 ]);
		$activeTheme->cache('active_theme', 'bo_shared');
		$activeTheme = $activeTheme->first();

		// Check active theme
		if (!$activeTheme) {

			$activeTheme = $this->getTheme('default');

			if (!$activeTheme) {
				$activeTheme = new Theme();
				$this->_themes->patchEntity($activeTheme, $this->_defaultTheme + [ 'is_active' => true ]);
			}

			$activeTheme->set('is_active', true);
			$this->_themes->save($activeTheme);
		}

		return $this->_active_theme = $activeTheme;
	}

	/**
	 * @param $type
	 * @param $name
	 *
	 * @return \BackOffice\Model\Entity\ThemeTemplate
	 */
	public function getTemplate($type, $name)
	{
		$activeTheme = $this->getActiveTheme();
		$template = $this->_theme_templates->find()->where([ 'theme_id' => $activeTheme->id, 'type' => $type, 'name' => $name ]);
		$template->cache('theme:' . $activeTheme->id . ':template:' . $name, 'bo_shared');
		return $template->first();
	}

	/**
	 * @param $path
	 *
	 * @return array
	 */
	private function splitTemplatePath($path)
	{
		if (strpos($path, '.') === false) $path = 'Content.' . $path;
		$dotLocation = strpos($path, '.');
		return [ substr($path, 0, $dotLocation), substr($path,$dotLocation + 1) ];
	}

	/**
	 * @param $path
	 *
	 * @return \BackOffice\Model\Entity\ThemeTemplate|Page
	 */
	public function getTemplateByPath($path)
	{
		list($type, $page) = $this->splitTemplatePath($path);
		return $type === 'Page' ? $this->getPage(intval($page)) : $this->getTemplate($type, $page);
	}

	/**
	 * @param $alias
	 *
	 * @return array|\Cake\Datasource\EntityInterface|null
	 */
	public function getTheme($alias)
	{
		return $this->_themes->find()->where([ 'alias' => $alias ])->first();
	}

	/**
	 * Returns active page
	 *
	 * @param ServerRequest $request
	 * @return array|bool|mixed
	 */
	public function getActivePage(ServerRequest $request)
	{
		$matchedRoute = $request->getParam('_matchedRoute');
		foreach ($this->getPages() as $key => $page) {
			if ($page['data']['slug'] === $matchedRoute) {
				return [ '_key' => $key ] + $page;
			}
		}
		return false;
	}

	/**
	 * @param $id
	 *
	 * @return array|mixed|null
	 */
	public function getPage($id)
	{
		foreach ($this->getPages() as $key => $page) {
			if ($page['data']->id === $id) return $page['data'];
		}
		return null;
	}

	/**
	 * Get menu by given zone
	 *
	 * @param $zone
	 *
	 * @return array|null
	 */
	public function getMenu($zone = '_default')
	{
		return Hash::sort($this->getConfig('menu.' . $zone, []), '{s}.order');
	}

	/**
	 * Return pages
	 */
	public function getPages()
	{
		/**
		 * @var \BackOffice\Model\Entity\Page $page
		 * @var array $staticPages
		 */

		// Check memory
		if ($this->_pageList !== null) return $this->_pageList;

		// Get db pages
		$query = $this->_pages->find('all');
		$query->cache('pages', 'bo_shared');

		// Get static page definitions
		$staticPages = $this->getConfig('pages', []);

		// Merge config
		foreach ($query as $page) {
			if (!isset($staticPages[$page->alias()])) {
				$staticPages[$page->alias()] = $this->_defaultPageSettings;
			}
			$this->_pages->patchEntity($page, array_merge(Hash::get($staticPages[$page->alias()], 'data', []), $page->toArray()));
			$staticPages[$page->alias()]['data'] = $page;
		}

		// Foreach static pages
		foreach ($staticPages as $key => $staticPage) {
			// Check has db record
			if (!$staticPage['data'] instanceof Page) {
				$page = new Page();
				$this->_pages->patchEntity($page, array_merge($this->_defaultPageSettings['data'], $staticPage['data'], [
					'name' => Hash::get($staticPage, 'data.name', Hash::get($staticPage, 'data.title', $this->_defaultPageSettings['data']['name'])),
					'is_system_default' => 1,
					'published_after' => Time::now(),
					'type' => 'complex',
					'alias' => $key
				]));
				if ($this->_pages->save($page)) {
					$staticPages[$key]['data'] = $page;
				}
			}
		}

		return $this->_pageList = $staticPages;
	}

	/**
	 * Returns active route
	 *
	 * @return array|bool
	 */
	public function getActiveRoute()
	{
		foreach ($this->getConfig('routes') as $name => $config) {
			if (Router::url($config['action']) === Router::url(null)) return [ '_name' => $name ] + $config;
		}
		return false;
	}

	/**
	 * Plugin routes
	 *
	 * @param $routes RouteBuilder
	 */
	public function routes( $routes )
	{
		// Build back office routes
		$routes->scope(
			$this->getConfig('rootPath'),
			function (RouteBuilder $routes) {
				foreach ($this->getConfig('routes') as $name => $config)
				{
					$routes->connect($config['template'], $config['action'], [ '_name' => $name ])->setMethods((array) $config['method']);
				}
			}
		);

		// Load frontend pages
		$routes->scope(
			'/',
			function (RouteBuilder $routes) {
				foreach ($this->getPages() as $key => $config) {
					$alias = $config['data']['alias'] ? $config['data']['alias'] : ('page:' . $config['data']['id']);
					$routes->connect($config['data']['slug'], $config['action'], [ '_name' => $alias ])->setMethods((array) $config['method']);
				}
			}
		);

	}

	/**
	 * @param \Cake\Http\MiddlewareQueue $middleware
	 *
	 * @return \Cake\Http\MiddlewareQueue|void
	 */
	public function middleware( $middleware ) {
		// Encrypted cookie middleware
		$middleware->add(new EncryptedCookieMiddleware(
			[ 'UPLR', 'BOSK' ],
			Configure::readOrFail('Security.cookieKey')
		));

		$middleware->add(new PageMiddleware(
			$this->getConfig('rootPath'),
			$this->getPages()
		));

		// Return middleware
		return $middleware;
	}

	/**
	 * Adds a new route
	 *
	 * @param $name
	 * @param $options
	 */
	public function addRoute($name, $options = [])
	{
		$this->setConfig([ 'routes.' . $name => $options ]);
	}

	/**
	 * Adds a new route sets
	 *
	 * @param array $routes
	 */
	public function addRoutes($routes = [])
	{
		foreach ($routes as $name => $options) {
			$this->addRoute($name, $options);
		}
	}

	/**
	 * Adds a new menu
	 *
	 * @param $section
	 * @param $name
	 * @param $menu
	 */
	public function addMenu($name, $menu, $zone = '_default')
	{
		$menu += [ 'order' => 1 ];
		$this->setConfig('menu.' . $zone . '.' . $name, $menu);
	}

	/**
	 * Adda a menu sets
	 *
	 * @param $section
	 * @param $menus
	 */
	public function addMenus($menus, $zone = '_default')
	{
		foreach ($menus as $name => $menu) {
			$this->addMenu($name, $menu, $zone);
		}
	}

	/**
	 * Add crud route
	 *
	 * @param $name
	 * @param $modelClass
	 * @param $template
	 * @param array $options
	 */
	public function addCrud($name, $modelClass, $template, $options = [])
	{
		foreach (self::CRUD_METHODS as $methodName => $method) {
			// Check if disable
			if (Hash::get($options, $methodName) === false) continue;
			$this->addRoute('backoffice:crud:' . $name . ':' . $methodName, [
				'method' => $method['method'],
				'template' => Text::insert($method['template'], [ 'template' => $template ]),
				'options' => $options,
				'action' => [
					'controller' => 'Crud',
					'action' => $method['action'],
					'plugin' => 'BackOffice',
					'modelClass' => $modelClass
				]
			]);
		}
	}
}
