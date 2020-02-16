<?php
namespace BackOffice\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Themes Model
 *
 * @property \BackOffice\Model\Table\ThemeTemplatesTable&\Cake\ORM\Association\HasMany $ThemeTemplates
 *
 * @method \BackOffice\Model\Entity\Theme get($primaryKey, $options = [])
 * @method \BackOffice\Model\Entity\Theme newEntity($data = null, array $options = [])
 * @method \BackOffice\Model\Entity\Theme[] newEntities(array $data, array $options = [])
 * @method \BackOffice\Model\Entity\Theme|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \BackOffice\Model\Entity\Theme saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \BackOffice\Model\Entity\Theme patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \BackOffice\Model\Entity\Theme[] patchEntities($entities, array $data, array $options = [])
 * @method \BackOffice\Model\Entity\Theme findOrCreate($search, callable $callback = null, $options = [])
 */
class ThemesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('themes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('ThemeTemplates', [
            'foreignKey' => 'theme_id',
            'className' => 'BackOffice.ThemeTemplates'
        ]);
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
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('alias')
            ->maxLength('alias', 25)
            ->requirePresence('alias', 'create')
            ->notEmptyString('alias')
            ->add('alias', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->boolean('is_active')
            ->requirePresence('is_active', 'create')
            ->notEmptyString('is_active');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['alias']));

        return $rules;
    }
}
