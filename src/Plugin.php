<?php

namespace BackOffice;

use Cake\Core\BasePlugin;
use Cake\Routing\RouteBuilder;

/**
 * Plugin for BackOffice
 */
class Plugin extends BasePlugin
{

    /**
     * Plugin routes
     *
     * @param $routes RouteBuilder
     */
    public function routes( $routes ) {
        $routes->plugin(
            'BackOffice',
            [ 'path' => '/_admin' ],
            function(RouteBuilder $routes) {
                $routes->get('/', [ 'controller' => 'Main', 'action' => 'index' ], 'main_page');
            }
        );
    }
}
