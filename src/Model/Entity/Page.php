<?php
namespace BackOffice\Model\Entity;

use Cake\ORM\Entity;

/**
 * Page Entity
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $template
 * @property string $layout
 * @property string|null $body
 * @property \Cake\I18n\FrozenTime|null $published_after
 * @property string $slug
 * @property string $title
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
	    'alias' => true,
	    'layout' => true,
        'template' => true,
        'body' => true,
        'published_after' => true,
        'slug' => true,
        'title' => true,
        'description' => true,
        'type' => true,
        'is_system_default' => true,
        'created' => true,
        'modified' => true
    ];

	/**
	 * @return mixed|string
	 */
    public function alias()
	{
		return $this->get('alias') ? $this->get('alias') : ('page:' . $this->id);
	}

	/**
	 * @return \Cake\I18n\FrozenTime|null
	 */
	public function modified()
	{
		return $this->modified ? $this->modified : $this->created;
	}
}
