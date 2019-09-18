<?php

namespace BackOffice;

use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Middleware\EncryptedCookieMiddleware;
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

	/**
	 * @var array
	 */
	private $_undefinedAction = [ 'controller' => 'UndefinedAction', 'action' => 'index', 'plugin' => 'BackOffice' ];

	/**
	 * Config
	 *
	 * @var array
	 */
	private $_defaultConfig = [
		'rootPath' => '/_admin',
		'main_page' => [ 'title' => 'Dashboard', 'action' => [ '_name' => 'backoffice:dashboard.index' ] ],
		'routes' => [
			'backoffice:dashboard.index' => [ 'method' => 'GET', 'template' => '/', 'action' => [ 'controller' => 'Dashboard', 'action' => 'index', 'plugin' => 'BackOffice' ] ],
			'backoffice:definitions.index' => [ 'method' => 'GET', 'template' => '/definitions', 'action' => [ 'controller' => 'Navigation', 'action' => 'index', 'plugin' => 'BackOffice' ] ],
			'backoffice:account.index' => [ 'method' => [ 'GET', 'PUT' ], 'template' => '/account', 'action' => [ 'controller' => 'Account', 'action' => 'index', 'plugin' => 'BackOffice' ] ],
			'backoffice:auth.login' => [ 'method' => [ 'GET', 'POST' ], 'template' => '/auth/login', 'action' => [ 'controller' => 'Auth', 'action' => 'login', 'plugin' => 'BackOffice' ] ],
			'backoffice:auth.logout' => [ 'method' => 'GET', 'template' => '/auth/logout', 'action' => [ 'controller' => 'Auth', 'action' => 'logout', 'plugin' => 'BackOffice' ] ]
		],
		'auth' => [
			'authenticate' => [
				'Form' => [
					'userModel' => 'BackOffice.Users',
					'fields' => [ 'username' => 'email' ]
				]
			],
			'loginAction' => [ '_name' => 'backoffice:auth.login' ],
			'logoutAction' => [ '_name' => 'backoffice:auth.logout' ],
			'loginRedirect' => [ '_name' => 'backoffice:dashboard.index' ]
		],
		'menu' => [
			'_default' => [
				'main_page' => [ 'title' => 'Dashboard', 'exact' => true, 'icon' => 'home', 'action' => [ '_name' => 'backoffice:dashboard.index' ], 'order' => -1 ],
				'definitions' => [ 'title' => 'Definitions', 'icon' => 'dvr', 'action' => [ '_name' => 'backoffice:definitions.index' ], 'order' => 99999, 'children' => [

				] ]
			]
		]
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
		// Build routes
		$routes->scope(
			$this->getConfig('rootPath'),
			function (RouteBuilder $routes) {
				foreach ($this->getConfig('routes') as $name => $config)
				{
					$routes->connect($config['template'], $config['action'], [ '_name' => $name ])->setMethods((array) $config['method']);
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
