<?php
namespace BackOffice\Model\Entity;

use Cake\ORM\Entity;

/**
 * ThemeTemplate Entity
 *
 * @property int $id
 * @property int $theme_id
 * @property string $type
 * @property string $name
 * @property string|null $content
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \BackOffice\Model\Entity\Theme $theme
 */
class ThemeTemplate extends Entity
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
        'theme_id' => true,
        'type' => true,
        'name' => true,
        'content' => true,
        'created' => true,
        'modified' => true,
        'theme' => true
    ];
}
