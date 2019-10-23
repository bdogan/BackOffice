<?php
namespace BackOffice\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Members Model
 *
 * @method \BackOffice\Model\Entity\Member get($primaryKey, $options = [])
 * @method \BackOffice\Model\Entity\Member newEntity($data = null, array $options = [])
 * @method \BackOffice\Model\Entity\Member[] newEntities(array $data, array $options = [])
 * @method \BackOffice\Model\Entity\Member|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \BackOffice\Model\Entity\Member saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \BackOffice\Model\Entity\Member patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \BackOffice\Model\Entity\Member[] patchEntities($entities, array $data, array $options = [])
 * @method \BackOffice\Model\Entity\Member findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MembersTable extends Table
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

        $this->setTable('members');
        $this->setDisplayField('id');
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
            ->scalar('firstname')
            ->maxLength('firstname', 50)
            ->requirePresence('firstname', 'create')
            ->notEmptyString('firstname');

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 50)
            ->requirePresence('lastname', 'create')
            ->notEmptyString('lastname');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('last_login_ip')
            ->maxLength('last_login_ip', 255)
            ->allowEmptyString('last_login_ip');

        $validator
            ->dateTime('last_login')
            ->allowEmptyDateTime('last_login');

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
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}
