<?php
namespace BackOffice\View;

use BackOffice\View\Helper\BreadcrumbHelper;
use Cake\View\View;

/**
 * Class AppView
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

        // Load helpers
        $this->loadHelper('BackOffice.Page');

    }

    /**
     * Load assets
     */
    private function loadAssets() {

        // JQuery 3.4.1
        $this->Html->script(
            'https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js',
            [ 'block' => 'script', 'integrity' => 'sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=', 'crossorigin' => 'anonymous' ]
        );

        // Popper.js 1.15.0
        $this->Html->script(
            'https://cdn.jsdelivr.net/npm/popper.js@1.15.0/dist/umd/popper.min.js',
            [ 'block' => 'script', 'integrity' => 'sha256-fTuUgtT7O2rqoImwjrhDgbXTKUwyxxujIMRIK7TbuNU=', 'crossorigin' => 'anonymous' ]
        );

        // Bootstrap 4.3.1
        $this->Html->css(
            'https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css',
            [ 'block' => 'css', 'integrity' => 'sha256-YLGeXaapI0/5IgZopewRJcFXomhRMlYYjugPLSyNjTY=', 'crossorigin' => 'anonymous' ]
        );
        $this->Html->script(
            'https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js',
            [ 'block' => 'script', 'integrity' => 'sha256-CjSoeELFOcH0/uxWu6mC/Vlrc1AARqbm/jiiImDGV3s=', 'crossorigin' => 'anonymous' ]
        );

        // Material icons
        $this->Html->css(
            'https://fonts.googleapis.com/icon?family=Material+Icons+Round',
            [ 'block' => 'css' ]
        );

        // Proxima Nova Font
        $this->Html->css(
        	$this->Url->assetUrl('BackOffice.font/proxima-nova/fonts.css'),
            /*'https://fonts.googleapis.com/css?family=Roboto:400,500,700',*/
            [ 'block' => 'css' ]
        );

        // Application assets
        $this->Html->script('BackOffice.app', [ 'block' => 'script' ]);
        $this->Html->css('BackOffice.style', [ 'block' => 'css' ]);

    }

}
