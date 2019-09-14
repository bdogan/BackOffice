<?php

namespace BackOffice\View\Helper;

use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\View\Helper;

/**
 * Class PageHelper
 * @package BackOffice\View\Helper
 *
 * @property-read \Cake\View\Helper\UrlHelper $Url
 * @property-read \Cake\View\Helper\HtmlHelper $Html
 */
class PageHelper extends Helper
{

	use InstanceConfigTrait;

	/**
	 * @var array Helpers
	 */
	public $helpers = [ 'Url', 'Html' ];

    /**
     * Breadcrumb items
     *
     * @var array holds crumbs
     */
    protected $crumbs = [];

    /**
     * @inheritDoc
     */
    public function initialize( array $config )
    {
    }

    /**
     * Adds new crumb
     *
     * @param $title
     * @param null $action
     * @param null $options
     */
    public function addCrumb($title, $action = null, $options = array())
    {
        $this->crumbs[] = compact('title', 'action', 'options');
    }

    /**
     * Returns crumbs
     *
     * @return array
     */
    public function getCrumbs()
    {
    	if (Configure::check('BackOffice.main_page')) {
		    $this->crumbs = array_merge([ Configure::read('BackOffice.main_page') ], $this->crumbs);
	    }
    	return $this->crumbs;
    }

	/**
	 * Returns menu config
	 *
	 * @param $menuName
	 *
	 * @return mixed
	 */
    public function getMenu($menuName)
    {
    	return Configure::read('BackOffice.menu.' . $menuName, []);
    }

	/**
	 * Check given action is active
	 *
	 * @param $action
	 * @param bool $exact
	 *
	 * @return bool
	 */
    public function isActiveAction($action, $exact = false)
    {
    	return $exact ? ($this->Url->build($action) === $this->Url->build(null)) : (strpos($this->Url->build(null), $this->Url->build($action)) === 0);
    }

	/**
	 * Icon
	 *
	 * @param $name
	 * @param null $size
	 *
	 * @return mixed
	 */
    public function icon($name, $size = null)
    {
    	return $this->Html->tag('i', $name, [ 'class' => 'material-icons-round ' . $size ]);
    }

	/**
	 * Char icon
	 *
	 * @param $title
	 * @param null $size
	 *
	 * @return string
	 */
    public function charIcon($title, $size = null)
    {
    	return $this->Html->tag('i',  mb_strtoupper( mb_substr( $title, 0, 1 ) ), [ 'class' => 'bo-char-icon ' . $size ] );
    }

}
