<?php

namespace BackOffice;

use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Routing\RouteBuilder;
use Cake\Utility\Hash;

/**
 * Plugin for BackOffice
 */
class Plugin extends BasePlugin
{
	/**
	 * Map
	 *
	 * @var array
	 */
	private $map = [];

	/**
	 * Target Plugin name
	 *
	 * @var string
	 */
	private $targetPlugin = 'ControlPanel';

	/**
	 * Plugin constructor.
	 *
	 * @param array $map
	 */
	public function __construct( array $config = [] ) {
		// Parent call
		parent::__construct($config);

		// Set map
		Configure::write('BackOffice.map', $this->map = $config['map']);

	}

	/**
	 * @param $key
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function getMapValue($key, $default = null)
	{
		return Hash::get($this->map, $key, $default);
	}

	/**
	 * @inheritDoc
	 *
	 * @param PluginApplicationInterface $app
	 */
	public function bootstrap( PluginApplicationInterface $app ) {

	}

	/**
	 * Plugin routes
	 *
	 * @param $routes RouteBuilder
	 */
	public function routes( $routes ) {
		// Get route config
		$routeConfig = $this->getMapValue('routes');

		// Config plugin routes
		$routes->plugin(
			$this->targetPlugin,
			[ 'path' => $this->getMapValue('rootPath') ],
			function(RouteBuilder $routes) use ($routeConfig) {
				foreach ($routeConfig as $name => $config)
				{
					$routes->{$config['method']}($config['template'], $config['target'], $name);
				}
			}
		);
	}
}
