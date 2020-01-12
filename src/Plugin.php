<?php

namespace BackOffice;

use BackOffice\Middleware\PageMiddleware;
use BackOffice\Model\Entity\Page;
use BackOffice\Model\Table\PagesTable;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\PluginApplicationInterface;
use Cake\Datasource\ConnectionManager;
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
	 * @var array
	 */
	private $_undefinedAction = [ 'controller' => 'UndefinedAction', 'action' => 'index', 'plugin' => 'BackOffice' ];

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

	public function tableExists($table, $scope = 'default')
    {
        try {
            $connection = ConnectionManager::get($scope);
            if ($connection->connect()) {
                $result = $connection->execute('SELECT * FROM ' . $table . ' LIMIT 1');
                return $result->count() > 0;
            }
        } catch (\Exception $connectionError) {
            return false;
        }
    }

	/**
	 * @inheritDoc
	 *
	 * @param PluginApplicationInterface $app
	 */
	public function bootstrap( PluginApplicationInterface $app )
	{
		// Set config
		Configure::write('BackOffice', $this);

		// Set pages table
        if ($this->tableExists('pages')) {
            $this->_pages = $this->getTableLocator()->get('BackOffice.Pages');
        }

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

		// Check pages
        if (!$this->_pages) return [];

		// Check memory
		if ($this->_pageList !== null) return $this->_pageList;

		// Get db pages
		$query = $this->_pages->find('all');

		// Get static page definitions
		$staticPages = $this->getConfig('pages', []);

		// Merge config
		foreach ($query as $page) {
			if (!empty($page->alias) && isset($staticPages[$page->alias])) {
				$staticPages[$page->alias]['data'] = array_merge($staticPages[$page->alias]['data'], $page->toArray());
			} else {
				$staticPages['page:id:' . $page->id] = array_merge($this->_defaultPageSettings, [
					'data' => $page->toArray()
				]);
			}
		}

		// Foreach static pages
		foreach ($staticPages as $key => $staticPage) {
			if (!Hash::check($staticPage, 'data.id')) {
				$page = new Page();
				$this->_pages->patchEntity($page, array_merge($this->_defaultPageSettings['data'], $staticPage['data'], [
					'name' => Hash::get($staticPage, 'data.name', Hash::get($staticPage, 'data.title', $this->_defaultPageSettings['data']['name'])),
					'is_system_default' => 1,
					'published_after' => Time::now(),
					'type' => 'complex',
					'alias' => $key
				]));
				if ($this->_pages->save($page)) {
					$staticPages[$key]['data'] = $page->toArray();
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
					$routes->connect($config['data']['slug'], $config['action'], [ '_name' => $config['data']['name'] ])->setMethods((array) $config['method']);
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
