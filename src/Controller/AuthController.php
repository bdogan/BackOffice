<?php
namespace BackOffice\Controller;

use Cake\Chronos\Chronos;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Auth Controller
 *
 */
class AuthController extends AppController
{
	/**
	 * Table Locator
	 */
	use LocatorAwareTrait;

	/**
	 * @inheritdoc
	 * @return array
	 */
	public function implementedEvents() {
		return array_merge(parent::implementedEvents(), [
			'Auth.afterIdentify' => 'onAfterIdentity'
		]);
	}

	/**
	 * Set last_login on Auth.afterIdentify
	 *
	 * @param Event $event
	 * @param $user
	 */
	public function onAfterIdentity(Event $event, $user) {
		// Get users table
		$usersTable = $this->getTableLocator()->get('ControlPanel.Users');

		// Get specific user
		$userEntity = $usersTable->get($user['id']);

		// Set last login DateTime
		$userEntity->last_login = Chronos::create();

		// Set last login ip address
		$userEntity->last_login_ip = $this->getRequest()->clientIp();

		// Save to database
		$usersTable->save($userEntity);

		// Change result
		$event->setResult($userEntity->toArray());
	}

	/**
	 * Get & Post login action
	 */
	public function login() {
		if ($this->Auth->user()) { // Check already logged in
			return $this->redirect($this->Auth->redirectUrl($this->mainPage));
		}
		if ($this->request->is('post')) { // Check Request is post
			$user = $this->Auth->identify(); // Identify user
			if ($user) {
				$this->Auth->setUser($user); // Save user
				return $this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Flash->error(__('Email or password is incorrect'), [ 'plugin' => 'BackOffice' ]);
			}
		}
		// Set email to login
		$this->set('email', $this->request->getData('email'));
		// Render login page
		$this->render('BackOffice.Auth/login', 'BackOffice.Login');
	}

	/**
	 * Logout
	 */
	public function logout() {
		return $this->redirect($this->Auth->logout());
	}
}
