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
        $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js', [ 'block' => 'script' ] );

        // Popper.js 1.16.0 **
        $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/popper.min.js', [ 'block' => 'script' ] );

        // Bootstrap 4.4.1
        $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css', [ 'block' => 'css' ] );
        $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js', [ 'block' => 'script' ] );

        // Material icons
        $this->Html->css('https://fonts.googleapis.com/icon?family=Material+Icons+Round', [ 'block' => 'css' ] );

		// Lodash
	    $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.15/lodash.min.js', [ 'block' => 'script' ] );

	    // Handlebars
	    $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.3/handlebars.min.js', [ 'block' => 'script' ] );

	    // EventEmitter3
	    $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/eventemitter3/4.0.0/index.min.js', [ 'block' => 'script' ] );

	    // Codemirror
	    $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.0/codemirror.min.css', [ 'block' => 'css' ] );
	    $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.0/theme/monokai.min.css', [ 'block' => 'css' ] );
	    $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.0/addon/display/fullscreen.css', [ 'block' => 'css' ] );
	    $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.0/codemirror.min.js', [ 'block' => 'script' ] );
	    $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.0/mode/htmlmixed/htmlmixed.min.js', [ 'block' => 'script' ] );
	    $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.0/mode/xml/xml.min.js', [ 'block' => 'script' ] );
	    $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.0/mode/javascript/javascript.min.js', [ 'block' => 'script' ] );
	    $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.0/mode/css/css.min.js', [ 'block' => 'script' ] );
	    $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.0/addon/display/fullscreen.min.js', [ 'block' => 'script' ] );

	    // Slugify
	    $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/speakingurl/14.0.1/speakingurl.min.js', [ 'block' => 'script' ] );

	    // Datetime picker
		$this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css', [ 'block' => 'css' ] );
		$this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js', [ 'block' => 'script' ]);

        // Application assets
        $this->Html->script('BackOffice.lib', [ 'block' => 'script' ]);
	    $this->Html->script('BackOffice.app', [ 'block' => 'script' ]);
        $this->Html->css('BackOffice.style', [ 'block' => 'css' ]);

    }

}
