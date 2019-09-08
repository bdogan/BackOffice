<?php

namespace BackOffice;

use BackOffice\Middleware\CookieAuthMiddleware;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\Middleware\EncryptedCookieMiddleware;
use Cake\Routing\RouteBuilder;

/**
 * Plugin for BackOffice
 */
class Plugin extends BasePlugin
{

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
	private $config = [];

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
			$this->config = $configLoader->read($this->plugin->name . '.backoffice');
		}

		// Set config
		Configure::write('BackOffice', $this->config);

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
		// Get route config
		$routeConfig = Configure::read('BackOffice.routes', []);

		// Config plugin routes
		$routes->plugin(
			$this->plugin->getName(),
			[ 'path' => Configure::readOrFail('BackOffice.rootPath') ],
			function(RouteBuilder $routes) use ($routeConfig) {
				foreach ($routeConfig as $name => $config)
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
