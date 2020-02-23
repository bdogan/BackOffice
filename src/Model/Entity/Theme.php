<?php
namespace BackOffice\Model\Entity;

use Cake\ORM\Entity;

/**
 * Theme Entity
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property bool $is_active
 *
 * @property \BackOffice\Model\Entity\ThemeTemplate[] $theme_templates
 */
class Theme extends Entity
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
        'alias' => true,
        'is_active' => true,
        'theme_templates' => true
    ];

}
