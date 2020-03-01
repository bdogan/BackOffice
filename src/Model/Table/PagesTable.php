<?php
namespace BackOffice\Model\Table;

use BackOffice\Model\Entity\Page;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

/**
 * Pages Model
 *
 * @method \BackOffice\Model\Entity\Page get($primaryKey, $options = [])
 * @method \BackOffice\Model\Entity\Page newEntity($data = null, array $options = [])
 * @method \BackOffice\Model\Entity\Page[] newEntities(array $data, array $options = [])
 * @method \BackOffice\Model\Entity\Page|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \BackOffice\Model\Entity\Page saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \BackOffice\Model\Entity\Page patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \BackOffice\Model\Entity\Page[] patchEntities($entities, array $data, array $options = [])
 * @method \BackOffice\Model\Entity\Page findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PagesTable extends Table
{

	/**
	 * @return array
	 */
	public function implementedEvents()
	{
		return parent::implementedEvents() + [
			'Model.afterSave' => 'afterSave',
			'Model.beforeDelete' => 'beforeDelete',
			'Model.afterDelete' => 'afterDelete'
		];
	}

	/**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('pages');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 250)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

	    $validator
		    ->scalar('alias')
		    ->maxLength('alias', 50)
		    ->allowEmptyString('alias');

	    $validator
            ->scalar('template')
            ->maxLength('template', 100)
            ->notEmptyString('template');

        $validator
            ->scalar('body')
            ->allowEmptyString('body');

        $validator
            ->dateTime('published_after')
            ->allowEmptyDateTime('published_after');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 100)
            ->requirePresence('slug', 'create')
            ->allowEmptyString('slug');

        $validator
            ->scalar('title')
            ->maxLength('title', 100)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->maxLength('description', 250)
            ->allowEmptyString('description');

        $validator
            ->scalar('type')
            ->maxLength('type', 255)
            ->notEmptyString('type');

        $validator
            ->boolean('is_system_default')
            ->notEmptyString('is_system_default');

        return $validator;
    }

	/**
	 * @inheritDoc
	 *
	 * @param \Cake\ORM\RulesChecker $rules
	 *
	 * @return \Cake\ORM\RulesChecker
	 */
    public function buildRules( RulesChecker $rules ) {

    	// Name unique
	    $rules->add($rules->isUnique(['name']));

    	// Slug unique
    	$rules->add($rules->isUnique(['slug']));
	    return $rules;
    }

	/**
	 * @param \Cake\Event\Event $event
	 * @param \BackOffice\Model\Entity\Page $page
	 * @param \ArrayObject $options
	 */
	public function beforeDelete(Event $event, Page $page, \ArrayObject $options)
	{
		if ($page->is_system_default) {
			$event->stopPropagation();
			$event->setResult(false);
		}
	}

	public function afterSave(Event $event, Page $page)
	{
		if (!$page->alias) {
			$page->alias = $page->alias();
			$this->save($page, [ 'checkRules' => false, 'atomic' => false ]);
		}
		Cache::clearGroup('backoffice', 'bo_shared');
	}

	/**
	 * After delete
	 */
	public function afterDelete()
	{
		Cache::clearGroup('backoffice', 'bo_shared');
	}

}
