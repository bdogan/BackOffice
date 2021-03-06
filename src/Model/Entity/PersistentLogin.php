<?php
namespace BackOffice\Model\Entity;

use Cake\ORM\Entity;

/**
 * PersistentLogin Entity
 *
 * @property string $email
 * @property string $series
 * @property string $token
 */
class PersistentLogin extends Entity
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
        'email' => true,
        'token' => true,
	    'series' => true
    ];

}
