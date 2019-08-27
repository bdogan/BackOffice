<?php

namespace BackOffice\View\Helper;

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
     * @param null $link
     * @param null $options
     */
    public function addCrumb($title, $link = null, $options = array()) {
        $this->crumbs[] = compact('title', 'link', 'options');
    }

    /**
     * Returns crumbs
     *
     * @return array
     */
    public function getCrumbs() {
        return $this->crumbs;
    }

}
