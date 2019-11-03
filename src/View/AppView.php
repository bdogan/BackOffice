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
		        'nextActive' => '<li class="page-item next"><a class="page-link" rel="next" href="{{url}}">{{text}}</a></li>',
		        'nextDisabled' => '',
		        'prevActive' => '<li class="page-item prev"><a class="page-link prev" href="{{url}}">{{text}}</a></li>',
		        'prevDisabled' => '',
		        'counterRange' => '{{start}} - {{end}} of {{count}}',
		        'counterPages' => '{{page}} of {{pages}}',
		        'first' => '<li class="page-item first"><a class="page-link" href="{{url}}">{{text}}</a></li>',
		        'last' => '<li class="page-item last"><a class="page-link" href="{{url}}">{{text}}</a></li>',
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

	    // Codemirror
	    $this->Html->css('https://unpkg.com/codemirror@5.49.2/lib/codemirror.css', [ 'block' => 'css' ] );
	    $this->Html->css('https://unpkg.com/codemirror@5.49.2/theme/monokai.css', [ 'block' => 'css' ] );
	    $this->Html->css('https://unpkg.com/codemirror@5.49.2/addon/display/fullscreen.css', [ 'block' => 'css' ] );
	    $this->Html->script('https://unpkg.com/codemirror@5.49.2/lib/codemirror.js', [ 'block' => 'script' ] );
	    $this->Html->script('https://unpkg.com/codemirror@5.49.2/mode/htmlmixed/htmlmixed.js', [ 'block' => 'script' ] );
	    $this->Html->script('https://unpkg.com/codemirror@5.49.2/mode/xml/xml.js', [ 'block' => 'script' ] );
	    $this->Html->script('https://unpkg.com/codemirror@5.49.2/mode/javascript/javascript.js', [ 'block' => 'script' ] );
	    $this->Html->script('https://unpkg.com/codemirror@5.49.2/mode/css/css.js', [ 'block' => 'script' ] );
	    $this->Html->script('https://unpkg.com/codemirror@5.49.2/addon/display/fullscreen.js', [ 'block' => 'script' ] );

	    // Slugify
	    $this->Html->script('https://unpkg.com/speakingurl@14.0.1/lib/speakingurl.js', [ 'block' => 'script' ] );

        // Application assets
        $this->Html->script('BackOffice.lib', [ 'block' => 'script' ]);
	    $this->Html->script('BackOffice.app', [ 'block' => 'script' ]);
        $this->Html->css('BackOffice.style', [ 'block' => 'css' ]);

    }

}
