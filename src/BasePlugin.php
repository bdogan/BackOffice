<?php

namespace BackOffice;

use Cake\Core\BasePlugin as CakeBasePlugin;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Routing\RouteBuilder;

/**
 * Class BasePlugin
 *
 * @package BackOffice
 */
class BasePlugin extends CakeBasePlugin implements EventListenerInterface
{

	/**
	 * @var \BackOffice\Plugin
	 */
	public $BackOfficePlugin;

	/**
	 * @inheritDoc
	 *
	 * @return array|void
	 */
	public function implementedEvents()
	{
		return [
			'BackOffice.ready' => 'onReadyBackOffice',
			'BackOffice.before.routes' => 'onBeforeRoutes'
		];
	}

	/**
	 * BasePlugin constructor.
	 *
	 * @param array $options
	 */
	public function __construct( array $options = [] )
	{
		parent::__construct( $options );

		// Set event listeners
		$listeners = $this->implementedEvents();
		foreach ($listeners as $key => $callable) {
			EventManager::instance()->on($key, [ $this, $callable ]);
		}
	}

	/**
	 * Ready Hook
	 *
	 * @param \BackOffice\Plugin $plugin
	 */
	public function ready(Plugin $plugin)
	{
	}

	/**
	 * Routes hook
	 *
	 * @param \BackOffice\Plugin $plugin
	 */
	public function beforeRoutes(RouteBuilder $routes)
	{
	}

	/**
	 * Adds a new route
	 *
	 * @param $name
	 * @param $options
	 */
	public function addRoute($name, $options = [])
	{
		$options['action'] += [ 'plugin' => $this->getName() ];
		$this->BackOfficePlugin->setConfig('routes.' . $name, $options);
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
	public function addMenu($section, $name, $menu)
	{
		$this->BackOfficePlugin->setConfig('menu.' . $section . '.' . $name, $menu);
	}

	/**
	 * Adda a menu sets
	 *
	 * @param $section
	 * @param $menus
	 */
	public function addMenus($section, $menus)
	{
		foreach ($menus as $name => $menu) {
			$this->addMenu($section, $name, $menu);
		}
	}

	/**
	 * Creates crud action
	 *
	 * @param $modelClass
	 * @param $menu
	 * @param array $options
	 *
	 * @return array
	 */
	public function crudAction($modelClass, $menu, $options = [])
	{
		return [
			'controller' => 'Crud',
			'action' => 'index',
			'plugin' => 'BackOffice',
			'model' => $modelClass,
			'menu' => $menu,
			'options' => $options
		];
	}

	/**
	 * Event ready listener
	 *
	 * @param \Cake\Event\Event $event
	 */
	public function onReadyBackOffice(Event $event)
	{
		$this->BackOfficePlugin = $event->getSubject();
		$this->ready($this->BackOfficePlugin);
	}

	/**
	 * Event before routes
	 *
	 * @param \Cake\Event\Event $event
	 */
	public function onBeforeRoutes(Event $event)
	{
		$this->beforeRoutes($event->getData('routes'));
	}

}
