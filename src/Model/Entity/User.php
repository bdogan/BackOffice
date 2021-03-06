<?php
namespace BackOffice\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string|null $last_login_ip
 * @property \Cake\I18n\FrozenTime|null $last_login
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'email' => true,
        'password' => true,
        'last_login_ip' => true,
        'last_login' => true,
        'created' => true,
        'modified' => true,
	    'role' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

	/**
	 * Set password
	 *
	 * @param $value
	 *
	 * @return bool|string
	 */
    protected function _setPassword($value)
    {
	    if (!empty($value)) {
		    $hasher = new DefaultPasswordHasher();
		    return $hasher->hash($value);
	    }
    }

	/**
	 * Check user password
	 *
	 * @param $password
	 *
	 * @return bool
	 */
    public function checkPassword($password)
    {
	    $hasher = new DefaultPasswordHasher();
    	return $hasher->check($password, $this->password);
    }
}
