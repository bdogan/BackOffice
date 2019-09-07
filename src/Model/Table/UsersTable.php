<?php
namespace BackOffice\Model\Table;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @method \BackOffice\Model\Entity\User get($primaryKey, $options = [])
 * @method \BackOffice\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \BackOffice\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \BackOffice\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \BackOffice\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \BackOffice\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \BackOffice\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \BackOffice\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
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
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('password')
	        ->lengthBetween('password', [ 8, 20 ])
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
	 * Password change validation
	 *
	 * @param \Cake\Validation\Validator $validator
	 *
	 * @return \Cake\Validation\Validator
	 */
    public function validationChangePassword(Validator $validator) {

	    $validator
		    ->scalar('old_password')
		    ->requirePresence('old_password')
		    ->notEmptyString('old_password')
		    ->add('old_password', 'custom', [
			    'rule' => function($value, $context) {
				    $hasher = new DefaultPasswordHasher();
				    return $hasher->check($value, $context['data']['password']);
			    },
			    'message' => 'Wrong password'
		    ]);

	    $validator
		    ->scalar('new_password')
		    ->lengthBetween('new_password', [ 8, 20 ])
		    ->requirePresence('new_password')
		    ->notEmptyString('new_password');

	    $validator
		    ->scalar('new_password_verify')
		    ->equalToField('new_password_verify', 'new_password')
		    ->requirePresence('new_password_verify')
		    ->notEmptyString('new_password_verify');

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
