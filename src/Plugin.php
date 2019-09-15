<?php

namespace BackOffice;

use BackOffice\Middleware\CookieAuthMiddleware;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Middleware\EncryptedCookieMiddleware;
use Cake\Routing\RouteBuilder;

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
			'bo_account' => [ 'method' => [ 'get', 'put' ], 'template' => '/account', 'action' => [ 'controller' => 'Account', 'action' => 'index', 'plugin' => 'BackOffice' ] ],
			'bo_login' => [ 'method' => [ 'get', 'post' ], 'template' => '/auth/login', 'action' => [ 'controller' => 'Auth', 'action' => 'login', 'plugin' => 'BackOffice' ] ],
			'bo_logout' => [ 'method' => 'get', 'template' => '/auth/logout', 'action' => [ 'controller' => 'Auth', 'action' => 'logout', 'plugin' => 'BackOffice' ] ]
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
					$methods = (array) $config['method'];
					foreach ($methods as $method) {
						$routes->{$method === 'all' ? 'connect' : $method}($config['template'], $config['action'], $method === 'get' ? $name : null);
					}
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
