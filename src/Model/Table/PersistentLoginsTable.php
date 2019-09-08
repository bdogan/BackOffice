<?php
namespace BackOffice\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PersistentLogins Model
 *
 * @method \BackOffice\Model\Entity\PersistentLogin get($primaryKey, $options = [])
 * @method \BackOffice\Model\Entity\PersistentLogin newEntity($data = null, array $options = [])
 * @method \BackOffice\Model\Entity\PersistentLogin[] newEntities(array $data, array $options = [])
 * @method \BackOffice\Model\Entity\PersistentLogin|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \BackOffice\Model\Entity\PersistentLogin saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \BackOffice\Model\Entity\PersistentLogin patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \BackOffice\Model\Entity\PersistentLogin[] patchEntities($entities, array $data, array $options = [])
 * @method \BackOffice\Model\Entity\PersistentLogin findOrCreate($search, callable $callback = null, $options = [])
 */
class PersistentLoginsTable extends Table
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

        $this->setTable('persistent_logins');
        $this->setDisplayField('series');
        $this->setPrimaryKey('series');
    }

}
