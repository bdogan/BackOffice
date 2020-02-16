<?php
namespace BackOffice\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ThemeTemplates Model
 *
 * @property \BackOffice\Model\Table\ThemesTable&\Cake\ORM\Association\BelongsTo $Themes
 *
 * @method \BackOffice\Model\Entity\ThemeTemplate get($primaryKey, $options = [])
 * @method \BackOffice\Model\Entity\ThemeTemplate newEntity($data = null, array $options = [])
 * @method \BackOffice\Model\Entity\ThemeTemplate[] newEntities(array $data, array $options = [])
 * @method \BackOffice\Model\Entity\ThemeTemplate|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \BackOffice\Model\Entity\ThemeTemplate saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \BackOffice\Model\Entity\ThemeTemplate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \BackOffice\Model\Entity\ThemeTemplate[] patchEntities($entities, array $data, array $options = [])
 * @method \BackOffice\Model\Entity\ThemeTemplate findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ThemeTemplatesTable extends Table
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

        $this->setTable('theme_templates');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Themes', [
            'foreignKey' => 'theme_id',
            'joinType' => 'INNER',
            'className' => 'BackOffice.Themes'
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
            ->scalar('type')
            ->maxLength('type', 45)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('name')
            ->maxLength('name', 45)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('content')
            ->allowEmptyString('content');

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
        $rules->add($rules->existsIn(['theme_id'], 'Themes'));

        return $rules;
    }
}
