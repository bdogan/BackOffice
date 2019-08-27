<?php

namespace BackOffice\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

class AppController extends Controller
{
    /**
     * Before Filter
     *
     * @param Event $event
     *
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter( Event $event )
    {
        // Set view class
        $this->viewBuilder()->setClassName('BackOffice.App');
    }

}
