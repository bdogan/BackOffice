<?php

namespace BackOffice\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Class AppController
 *
 * @package BackOffice\Controller
 * @property \BackOffice\Controller\Component\FlashComponent $Flash
 */
class AppController extends Controller
{
	/**
	 * @var \BackOffice\Plugin
	 */
	protected $BackOffice;

	/**
	 * App Controller initialize
	 *
	 * @throws \Exception
	 */
	public function initialize()
	{
		// Set back office
		$this->BackOffice = Configure::read('BackOffice');

		// Set view class
		$this->viewBuilder()->setClassName('BackOffice.App');

		// FlashComponent
		$this->loadComponent('BackOffice.Flash');

		// CsrfComponent
		$this->loadComponent('Csrf');

		// AuthComponent
		if ($this->BackOffice->getConfig('auth')) {
			$this->loadComponent('Auth', [
				'authenticate' => $this->BackOffice->getConfig('auth.authenticate'),
				'loginAction' => $this->BackOffice->getConfig('auth.loginAction'),
				'logoutAction' => $this->BackOffice->getConfig('auth.logoutAction'),
				'loginRedirect' => $this->BackOffice->getConfig('auth.loginRedirect'),
				'flash' => [ 'element' => 'error' ],
				'storage' => $this->BackOffice->getConfig('auth.storage', 'session')
			]);
			// CookieLoginComponent
			$this->loadComponent('BackOffice.CookieAuth');
		}
	}

}
