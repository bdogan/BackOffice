<?php

namespace BackOffice;

use BackOffice\Middleware\CookieAuthMiddleware;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\Middleware\EncryptedCookieMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Utility\Inflector;

/**
 * Plugin for BackOffice
 */
class Plugin extends BasePlugin
{

	use InstanceConfigTrait;

	/**
	 * Target plugin
	 *
	 * @var BasePlugin
	 */
	private $plugin;

	/**
	 * Config
	 *
	 * @var array
	 */
	private $_defaultConfig = [
		'rootPath' => '/_admin',
		'main_page' => [ 'title' => 'Dashboard', 'action' => [ '_name' => 'main_page' ] ],
		'routes' => [
			'account' => [ 'method' => [ 'get', 'put' ], 'template' => '/account', 'action' => [ 'controller' => 'Account', 'action' => 'index', 'plugin' => 'BackOffice' ] ],
			'login' => [ 'method' => [ 'get', 'post' ], 'template' => '/auth/login', 'action' => [ 'controller' => 'Auth', 'action' => 'login', 'plugin' => 'BackOffice' ] ],
			'logout' => [ 'method' => 'get', 'template' => '/auth/logout', 'action' => [ 'controller' => 'Auth', 'action' => 'logout', 'plugin' => 'BackOffice' ] ]
		],
		'auth' => [
			'authenticate' => [
				'Form' => [
					'userModel' => 'BackOffice.Users',
					'fields' => [ 'username' => 'email' ]
				]
			],
			'loginAction' => [ '_name' => 'login' ],
			'logoutAction' => [ '_name' => 'logout' ],
			'loginRedirect' => [ '_name' => 'main_page' ]
		],
	];

	/**
	 * Plugin constructor.
	 *
	 * @param array $map
	 */
	public function __construct( array $config = [] ) {
		// Parent call
		parent::__construct($config);

		// Set target plugin
		$this->plugin = $config['plugin'];

		// Load config file
		if ($config['config']) {
			$configLoader = new PhpConfig();
			$this->setConfig($configLoader->read($this->plugin->name . '.backoffice'));
		}

		// Add plugin name to routes
		foreach ($this->getConfig('routes') as $name => $route) {
			$route['action'] += [ 'plugin' => $this->plugin->getName() ];
			$this->setConfig('routes.' . $name . '.action', $route['action']);
		}

		// Set config
		Configure::write('BackOffice', $this->getConfig());

	}

	/**
	 * @inheritDoc
	 *
	 * @param PluginApplicationInterface $app
	 */
	public function bootstrap( PluginApplicationInterface $app )
	{
		// Parent call
		parent::bootstrap($app);
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
