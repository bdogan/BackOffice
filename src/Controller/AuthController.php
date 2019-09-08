<?php
namespace BackOffice\Controller;

use Cake\Chronos\Chronos;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Auth Controller
 *
 * @property-read \BackOffice\Model\Table\UsersTable $Users
 */
class AuthController extends AppController
{

	/**
	 * @var string ModelClass
	 */
	public $modelClass = 'Users';

	/**
	 * @inheritdoc
	 * @return array
	 */
	public function implementedEvents() {
		return array_merge(parent::implementedEvents(), [
			'BackOffice.Auth.afterLogin' => 'onAfterLogin'
		]);
	}

	/**
	 * Set last_login on Auth.afterIdentify
	 *
	 * @param Event $event
	 * @param $user
	 */
	public function onAfterLogin(Event $event) {

		$userData = $event->getData('user');
		$controller = $event->getSubject();

		// Save Last Login / Ip
		$analytic = [
			'last_login' => Chronos::create(),
			'last_login_ip' => $controller->getRequest()->clientIp()
		];
		$this->Users->updateAll($analytic, [ 'id' => $userData['id'] ]);

		// Merge
		$userData = array_merge($userData, $analytic);

		// Change result
		$event->setResult($userData);

	}

	/**
	 * Get & Post login action
	 */
	public function login() {
		if ($this->Auth->user()) { // Check already logged in
			return $this->redirect($this->Auth->redirectUrl());
		}
		if ($this->request->is('post')) { // Check Request is post
			$user = $this->Auth->identify(); // Identify user
			if ($user) {
				$loginEvent = new Event('BackOffice.Auth.afterLogin', $this, [
					'user' => $user,
					'remember_me' => $this->request->getData('remember_me')
				]);
				$this->getEventManager()->dispatch($loginEvent);
				$user = $loginEvent->getResult();
				if (!empty($user)) {
					$this->Auth->setUser($user); // Save user
					return $this->redirect($this->Auth->redirectUrl());
				}
				$this->Flash->error(__('Something was wrong'));
			} else {
				$this->Flash->error(__('Email or password is incorrect'));
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
