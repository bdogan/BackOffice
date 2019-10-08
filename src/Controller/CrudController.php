<?php
namespace BackOffice\Controller;

use Cake\Collection\Collection;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

/**
 * Class CrudController
 *
 * @package BackOffice\Controller
 */
class CrudController extends AppController
{

	/**
	 * @var \Cake\ORM\Table
	 */
	private $model;

	/**
	 * @var \Cake\ORM\Entity
	 */
	private $entity;

	/**
	 * @var array
	 */
	private $activeMenu;

	/**
	 * @var array
	 */
	private $parentMenu;

	/**
	 * Model hidden fields
	 *
	 * @var array
	 */
	private $hiddenFields = [ 'created', 'modified' ];

	/**
	 * @return array
	 */
	public function implementedEvents() {
		return array_merge(parent::implementedEvents(), [
			'View.beforeRender' => 'beforeViewRender',
		]);
	}

	/**
	 * @inheritDoc
	 * @param \Cake\Event\Event $event
	 *
	 * @return \Cake\Http\Response|null
	 * @throws \ReflectionException if the model entity class not exists
	 */
	public function beforeFilter( Event $event ) {

		// Load parent active menu
		$this->activeMenu = $this->BackOffice->getActiveMenu() ?: [];
		if (!$this->activeMenu) {
			throw new NotFoundException(__('ActiveMenu not found'));
		}

		// Load model
		$this->modelClass = $this->getRequest()->getParam('modelClass');
		$this->model = $this->loadModel($this->modelClass);

		// Load entity
		$this->entity = (new \ReflectionClass($this->model->getEntityClass()))->newInstance();

		// Continue
		return parent::beforeFilter($event);
	}

	/**
	 * Before render
	 *
	 * @param \Cake\Event\Event $event
	 *
	 * @return \Cake\Http\Response|null
	 */
	public function beforeRender( Event $event ) {
		// Get model schema
		$schema = $this->model->getSchema();
		// Filter fields
		$fields = new Collection(Hash::filter($schema->columns(), function($columnName) {
			return !in_array($columnName, $this->getHiddenFields());
		}));
		// Set to view
		$this->set([
			'_schema' => $schema,
			'_fields' => $fields,
			'_primary_key' => $this->model->getPrimaryKey(),
			'activeMenu' => $this->activeMenu,
			'modelClass' => $this->modelClass,
			'entity' => $this->model->getAlias(),
		]);
		return parent::beforeRender( $event ); // TODO: Change the autogenerated stub
	}

	/**
	 * Before view render
	 *
	 * @param \Cake\Event\Event $event
	 */
	public function beforeViewRender(Event $event)
	{
		/** @var \BackOffice\View\AppView $appView */
		$appView = $event->getSubject();

		// Set breadcrumb
		if ($this->activeMenu) {
			$appView->Page->addCrumb( $this->activeMenu['title'], $this->activeMenu['action'] );
		}
		$appView->Page->addCrumb( Inflector::pluralize($this->model->getAlias()), [ 'action' => 'index', 'modelClass' => $this->modelClass ] );

		// Add create action
		if ($appView->view === 'index') {
			$appView->Page->addAction('primary', [ 'title' => 'New ' .  Inflector::singularize($this->model->getAlias()), 'action' => [ 'action' => 'create', 'modelClass' => $this->modelClass ] ]);
		}

		// Add crumb on update & create
		$actions = new Collection([
			[
				'action' => 'create',
				'title' => 'New {0}',
				'route' => [ 'action' => 'create', 'modelClass' => $this->modelClass ]
			],
			[
				'action' => 'update',
				'title' => 'Edit {0}',
				'route' => [ 'action' => 'update', 'modelClass' => $this->modelClass ]
			]
		]);
		$action = $actions->firstMatch([ 'action' => $this->getRequest()->getParam('action') ]);
		if ($action) {
			$appView->Page->addCrumb( __($action['title'], Inflector::singularize($this->model->getAlias())) , $action['route'] );
		}

		// Set model class to url params
		$appView->Paginator->options([
			'url' => [ 'modelClass' => $this->modelClass ]
		]);

	}

	/**
	 * Hidden fields
	 *
	 * @return array
	 */
	protected function getHiddenFields()
	{
		return $this->hiddenFields + [ count($this->hiddenFields) => $this->model->getPrimaryKey() ];
	}

	public function index()
	{
		// Prepare associations data
		$assocMap = [];
		$this->paginate = [
			'order' => [
				$this->model->getAlias() . '.' . $this->model->getDisplayField() => 'asc'
			],
			'contain' => Hash::map($this->model->associations()->getByType('BelongsTo'), '{n}', function($assoc) use (&$assocMap) {
				/**
				 * @var \Cake\ORM\Association\BelongsTo $assoc
				 */
				$assocMap[$assoc->getForeignKey()] = Inflector::underscore(Inflector::singularize($assoc->getAlias())) . '.' . $assoc->getDisplayField();
				return $assoc->getAlias();
			})
		];
		// Get items
		$items = $this->paginate($this->model);
		// Execute assoc map
		$items->each(function($item) use (&$assocMap) {
			/**
			 * @var \Cake\ORM\Entity $item
			 */
			foreach ($assocMap as $field => $path) {
				$item->set($field, Hash::get($item->toArray(), $path));
			}
			// Set primary key
			$item->set('_primary', $item->get($this->model->getPrimaryKey()));
		});
		// Set to view
		$this->set([
			'_items' => $items
		]);
	}

	/**
	 * Creates a new entity
	 *
	 * @return \Cake\Http\Response|null
	 */
	public function create()
	{
		// Check Request for POST
		if ($this->request->is('post')) {
			// Data -> Entity
			$this->model->patchEntity($this->entity, $this->request->getData());
			// Save to database
			if ($this->model->save($this->entity)) {
				$this->Flash->success(__('New record has been created successfully.'));
				return $this->redirect([ 'action' => 'index', 'modelClass' => $this->modelClass ]);
			}
		}

		// Set record to entity
		$this->set('record', $this->entity);

		// Prepare associations data
		/**
		 * @var \Cake\ORM\Association\BelongsTo $assoc
		 */
		foreach ($this->model->associations()->getByType('BelongsTo') as $assoc) {
			$this->set(Inflector::tableize($assoc->getAlias()), $assoc->find('list'));
		}
	}

	/**
	 * Delete record
	 *
	 * @return \Cake\Http\Response|null
	 */
	public function delete() {
		$entity = $this->model->get($this->getRequest()->getParam('id'));
		if (!$entity) {
			throw new NotFoundException('Record not found!');
		}
		$this->model->deleteOrFail($entity);
		$this->Flash->success(__('Record has been deleted successfully.'));
		return $this->redirect([ 'action' => 'index', 'modelClass' => $this->modelClass ]);
	}

	/**
	 * Update
	 *
	 * @return \Cake\Http\Response|null
	 */
	public function update() {
		$entity = $this->model->get($this->getRequest()->getParam('id'));
		if (!$entity) {
			throw new NotFoundException('Record not found!');
		}
		// Check Request for POST
		if ($this->request->is('put')) {
			// Data -> Entity
			$this->model->patchEntity($entity, $this->request->getData());
			// Save to database
			if ($this->model->save($entity)) {
				$this->Flash->success(__('Record has been updated successfully.'));
				return $this->redirect([ 'action' => 'index', 'modelClass' => $this->modelClass ]);
			}
		}

		// Set entity to view
		$this->set('record', $entity);

		// Prepare associations data
		/**
		 * @var \Cake\ORM\Association\BelongsTo $assoc
		 */
		foreach ($this->model->associations()->getByType('BelongsTo') as $assoc) {
			$this->set(Inflector::tableize($assoc->getAlias()), $assoc->find('list'));
		}

		// Set template to create
		$this->viewBuilder()->setTemplate('create');
	}

}
