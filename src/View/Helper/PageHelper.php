<?php

namespace BackOffice\View\Helper;

use Cake\Core\Configure;
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
	 * @var \BackOffice\Plugin
	 */
    protected $BackOffice;

    /**
     * @inheritDoc
     */
    public function initialize( array $config )
    {
		$this->BackOffice = Configure::read('BackOffice');
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
	    $mainPage = $this->BackOffice->getConfig('main_page');
    	if ($mainPage) {
		    $this->crumbs = array_merge([ $mainPage ], $this->crumbs);
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
    	return $this->BackOffice->getConfig('menu.' . $menuName, []);
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
