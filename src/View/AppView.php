<?php
namespace BackOffice\View;

use BackOffice\View\Helper\BreadcrumbHelper;
use Cake\View\View;

/**
 * Class AppView
 *
 * @property-read \BackOffice\View\Helper\PageHelper $Page
 * @property-read \BackOffice\View\Helper\FormHelper $Form
 * @package BackOffice\View
 */
class AppView extends View
{

    /**
     * Layout name
     *
     * @var string
     */
    protected $layout = 'BackOffice.default';

    /**
     * @inheritDoc
     */
    public function initialize() {

        // Load assets
        $this->loadAssets();

        // Load Page Helper
        $this->loadHelper('BackOffice.Page');

        // Load Form Helper
        $this->loadHelper('BackOffice.Form');

        // Load paginator
        $this->loadHelper('Paginator', [
	        'templates' => [
		        'nextActive' => '<li class="next"><a rel="next" href="{{url}}">{{text}}</a></li>',
		        'nextDisabled' => '<li class="next disabled"><a href="" onclick="return false;">{{text}}</a></li>',
		        'prevActive' => '<li class="prev"><a rel="prev" href="{{url}}">{{text}}</a></li>',
		        'prevDisabled' => '<li class="prev disabled"><a href="" onclick="return false;">{{text}}</a></li>',
		        'counterRange' => '{{start}} - {{end}} of {{count}}',
		        'counterPages' => '{{page}} of {{pages}}',
		        'first' => '<li class="first"><a href="{{url}}">{{text}}</a></li>',
		        'last' => '<li class="last"><a href="{{url}}">{{text}}</a></li>',
		        'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
		        'current' => '<li class="page-item active"><a class="page-link" href="{{url}}">{{text}}</a></li>',
		        'ellipsis' => '<li class="ellipsis">&hellip;</li>',
		        'sort' => '<a href="{{url}}">{{text}}</a>',
		        'sortAsc' => '<a class="asc" href="{{url}}">{{text}}</a>',
		        'sortDesc' => '<a class="desc" href="{{url}}">{{text}}</a>',
		        'sortAscLocked' => '<a class="asc locked" href="{{url}}">{{text}}</a>',
		        'sortDescLocked' => '<a class="desc locked" href="{{url}}">{{text}}</a>',
	        ]
        ]);

    }

    /**
     * Load assets
     */
    private function loadAssets() {

        // JQuery 3.4.1 **
        $this->Html->script('https://unpkg.com/jquery@3.4.1/dist/jquery.min.js', [ 'block' => 'script' ] );

        // Popper.js 1.15.0 **
        $this->Html->script('https://unpkg.com/popper.js@1.15.0/dist/umd/popper.min.js', [ 'block' => 'script' ] );

        // Bootstrap 4.3.1
        $this->Html->css('https://unpkg.com/bootstrap@4.3.1/dist/css/bootstrap.min.css', [ 'block' => 'css' ] );
        $this->Html->script('https://unpkg.com/bootstrap@4.3.1/dist/js/bootstrap.min.js', [ 'block' => 'script' ] );

        // Material icons
        $this->Html->css('https://fonts.googleapis.com/icon?family=Material+Icons+Round', [ 'block' => 'css' ] );

		// Lodash
	    $this->Html->script('https://unpkg.com/lodash@4.17.15/lodash.min.js', [ 'block' => 'script' ] );

	    // Handlebars
	    $this->Html->script('https://unpkg.com/handlebars@4.4.0/dist/handlebars.min.js', [ 'block' => 'script' ] );

	    // EventEmitter3
	    $this->Html->script('https://unpkg.com/eventemitter3@latest/umd/eventemitter3.min.js', [ 'block' => 'script' ] );

        // Application assets
        $this->Html->script('BackOffice.lib', [ 'block' => 'script' ]);
	    $this->Html->script('BackOffice.app', [ 'block' => 'script' ]);
        $this->Html->css('BackOffice.style', [ 'block' => 'css' ]);

    }

}
