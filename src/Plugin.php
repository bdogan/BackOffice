<?php

namespace BackOffice;

use BackOffice\Model\Entity\Page;
use BackOffice\Model\Table\PagesTable;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Middleware\EncryptedCookieMiddleware;
use Cake\I18n\Time;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Utility\Hash;

/**
 * Plugin for BackOffice
 */
class Plugin extends BasePlugin
{

	use InstanceConfigTrait;
	use EventDispatcherTrait;
	use LocatorAwareTrait;

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
		'pages' => [
			/** Default pages */
			'main.index' => [ 'method' => 'GET', 'data' => [ 'slug' => '', 'body' => 'Main page body', 'title' => 'Main Page', 'layout' => 'default', 'template' => 'page' ], 'frozen' => [ 'slug' ], 'action' => [ 'prefix' => 'Frontend', 'controller' => 'Pages', 'action' => 'index', 'plugin' => 'BackOffice' ] ],
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
				'definitions' => [ 'title' => 'Definitions', 'icon' => 'dvr', 'action' => [ '_name' => 'backoffice:definitions.index' ], 'order' => 99999, 'children' => [

				] ],
			],
		],
	];

	/**
	 * @inheritDoc
	 *
	 * @param PluginApplicationInterface $app
	 */
	public function bootstrap( PluginApplicationInterface $app )
	{
		// Load config file
		$configLoader = new PhpConfig();
		$this->setConfig($configLoader->read('backoffice'));

		// Set config
		Configure::write('BackOffice', $this);

		// Set pages table
		$this->_pages = $this->getTableLocator()->get('BackOffice.Pages');

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

		// Get static page definitions
		$staticPages = $this->getConfig('pages', []);

		// Merge config
		foreach ($query as $page) {
			if (!empty($page->alias) && isset($staticPages[$page->alias])) {
				$staticPages[$page->alias]['data'] = array_merge_recursive($staticPages[$page->alias]['data'], $page->toArray());
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

		// Load pages
		$routes->scope(
			'/',
			function (RouteBuilder $routes) {
				foreach ($this->getPages() as $key => $config) {
					$routes->connect($config['data']['slug'], $config['action'], [ '_name' => $key ])->setMethods((array) $config['method']);
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

		// Return middleware
		return $middleware;
	}
}
