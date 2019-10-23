<?php
namespace BackOffice\Model\Entity;

use Cake\ORM\Entity;

/**
 * Page Entity
 *
 * @property int $id
 * @property string $name
 * @property string $template
 * @property string|null $body
 * @property \Cake\I18n\FrozenTime|null $published_after
 * @property string $slug
 * @property string $title
 * @property string $canonical
 * @property string|null $description
 * @property string $type
 * @property bool $is_system_default
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Page extends Entity
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
        'template' => true,
        'body' => true,
        'published_after' => true,
        'slug' => true,
        'title' => true,
        'canonical' => true,
        'description' => true,
        'type' => true,
        'is_system_default' => true,
        'created' => true,
        'modified' => true
    ];
}
