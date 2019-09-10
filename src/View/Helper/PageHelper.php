<?php

namespace BackOffice\View\Helper;

use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Utility\Inflector;
use Cake\View\Helper;

/**
 * Class PageHelper
 * @package BackOffice\View\Helper
 *
 * @property-read Helper\UrlHelper $Url
 */
class PageHelper extends Helper
{

	use InstanceConfigTrait;

	/**
	 * @var array Helpers
	 */
	public $helpers = [ 'Url' ];

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

}
