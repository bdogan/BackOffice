<?php

namespace BackOffice\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Class PageHelper
 * @package BackOffice\View\Helper
 */
class PageHelper extends Helper
{

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
    public function addCrumb($title, $action = null, $options = array()) {
        $this->crumbs[] = compact('title', 'action', 'options');
    }

    /**
     * Returns crumbs
     *
     * @return array
     */
    public function getCrumbs() {
    	if (!isset($this->crumbs['main_page']) && Configure::check('BackOffice.routes.main_page.action')) {
		    $this->crumbs = array_merge([
		    	'main_page' => [
				    'title' => __('Main Page'),
				    'action' => Configure::read('BackOffice.routes.main_page.action'),
				    'options' => []
			    ]
		    ], $this->crumbs);
	    }
    	return $this->crumbs;
    }

}
