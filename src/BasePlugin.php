<?php

namespace BackOffice;

use Cake\Core\BasePlugin as CakeBasePlugin;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Routing\RouteBuilder;
use Cake\Utility\Hash;
use Cake\Utility\Text;

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
