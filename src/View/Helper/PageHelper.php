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
	 * Page Actions
	 * @var array
	 */
	protected $actions = [];

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
    public function getMenu($zone = '_default')
    {
    	return $this->BackOffice->getMenu($zone);
    }

	/**
	 * Check given menu is active
	 *
	 * @param array $menu
	 *
	 * @return bool
	 */
    public function isActiveMenu($menu)
    {
	    return $this->BackOffice->isActiveMenu($menu);
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
    	if (!$name) return '';
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

	/**
	 * Adds a new action to given zone
	 *
	 * @param $zone
	 * @param $action
	 */
    public function addAction($zone, $action)
    {
    	if (!isset($this->actions[$zone])) {
    		$this->actions[$zone] = [];
	    }
    	$this->actions[$zone][] = $action;
    }

	/**
	 * Get actions
	 *
	 * @param $zone
	 *
	 * @return array|mixed
	 */
    public function getActions($zone)
    {
    	return isset($this->actions[$zone]) ? $this->actions[$zone] : [];
    }

	/**
	 * Has action
	 *
	 * @param $zone
	 *
	 * @return bool
	 */
    public function hasActions($zone)
    {
    	return isset($this->actions[$zone]);
    }

}
