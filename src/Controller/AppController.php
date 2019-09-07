<?php

namespace BackOffice\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;

class AppController extends Controller
{
	/**
	 * App Controller initialize
	 *
	 * @throws \Exception
	 */
	public function initialize()
	{
		// Set view class
		$this->viewBuilder()->setClassName('BackOffice.App');

		// FlashComponent
		$this->loadComponent('Flash', [
			'element' => 'BackOffice.default',
		]);

		// CsrfComponent
		$this->loadComponent('Csrf');

		// AuthComponent
		if (Configure::check('BackOffice.auth')) {
			$this->loadComponent('Auth', [
				'authenticate' => Configure::readOrFail('BackOffice.auth.authenticate'),
				'loginAction' => Configure::read('BackOffice.auth.loginAction'),
				'logoutAction' => Configure::read('BackOffice.auth.logoutAction'),
				'loginRedirect' => Configure::read('BackOffice.auth.loginRedirect'),
				'flash' => [
					'element' => 'BackOffice.default',
					'params' => [
						'class' => 'danger'
					]
				],
				'storage' => Configure::read('BackOffice.auth.storage', 'session')
			]);
		}
	}

}
