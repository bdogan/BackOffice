<?php

namespace BackOffice;

use BackOffice\Middleware\CookieAuthMiddleware;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\Event;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use Cake\Http\Middleware\EncryptedCookieMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

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
		'main_page' => [ 'title' => 'Dashboard', 'action' => [ '_name' => 'main_page' ] ],
		'routes' => [
			'bo_account' => [ 'method' => [ 'GET', 'POST' ], 'template' => '/account', 'action' => [ 'controller' => 'Account', 'action' => 'index', 'plugin' => 'BackOffice' ] ],
			'bo_login' => [ 'method' => [ 'GET', 'POST' ], 'template' => '/auth/login', 'action' => [ 'controller' => 'Auth', 'action' => 'login', 'plugin' => 'BackOffice' ] ],
			'bo_logout' => [ 'method' => 'GET', 'template' => '/auth/logout', 'action' => [ 'controller' => 'Auth', 'action' => 'logout', 'plugin' => 'BackOffice' ] ]
		],
		'auth' => [
			'authenticate' => [
				'Form' => [
					'userModel' => 'BackOffice.Users',
					'fields' => [ 'username' => 'email' ]
				]
			],
			'loginAction' => [ '_name' => 'bo_login' ],
			'logoutAction' => [ '_name' => 'bo_logout' ],
			'loginRedirect' => [ '_name' => 'main_page' ]
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

		// Add plugin name to routes
		foreach ($this->getConfig('routes') as $name => $route) {
			//$route['action'] += [ 'plugin' => $this->plugin->getName() ];
			$this->setConfig('routes.' . $name . '.action', $route['action']);
		}

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
	 * @return bool
	 */
	public function getActiveMenu($zone)
	{
		foreach ($this->getMenu($zone) as $name => $menu) {
			$exact = isset($menu['exact']) ? $menu['exact'] : false;
			if ($exact ? Router::url($menu['action']) === Router::url(null) : strpos(Router::url(null), Router::url($menu['action'])) === 0) {
				return true;
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
		$exact = isset($menu['exact']) ? $menu['exact'] : false;
		return $exact ? Router::url($menu['action']) === Router::url(null) : strpos(Router::url(null), Router::url($menu['action'])) === 0;
	}

	/**
	 * Get menu by given zone
	 *
	 * @param $zone
	 *
	 * @return array|null
	 */
	public function getMenu($zone)
	{
		$dotPosition = strpos($zone, '.');
		if ($dotPosition !== false) {
			// todo:
		}
		return $this->getConfig('menu.' . $zone, []);
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
