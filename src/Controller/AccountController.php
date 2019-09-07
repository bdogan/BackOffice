<?php
namespace BackOffice\Controller;

use BackOffice\Model\Table\UsersTable;
use Cake\Core\Configure;
use Cake\Validation\Validator;

/**
 * Account Controller
 *
 * @property-read UsersTable $Users
 *
 */
class AccountController extends AppController
{
	// User Users Class
	public $modelClass = 'BackOffice.Users';

	/**
	 * Account info edit/view
	 */
	public function index()
	{
		/**
		 * Get user from database
		 * @var \BackOffice\Model\Entity\User $user
		 */
		$user = $this->Users->find()->where(['id' => $this->Auth->user('id')])->firstOrFail();

		// Check Request for POST
		if ($this->request->is('put')) {
			// Profile Update
			if ($this->request->getData('target') === 'profile') {
				// Data -> Entity
				$this->Users->patchEntity($user, $this->request->getData());
				// Save user to db
				if ($this->Users->save($user)) {
					$this->Flash->success(__('Your profile has been updated.'));
					return $this->redirect([ 'action' => 'index' ]);
				}
			}
			// Password change
			if ($this->request->getData('target') === 'password') {
				// Validate request
				$errors = $this->Users->getValidator('changePassword')->errors($this->request->getData() + [ 'password' => $user->password ]);
				$user->setErrors($errors);
				// Check Validation Results
				if (empty($errors)) {
					$user->password = $this->request->getData('new_password');
				}
				// Save user to db
				if ($this->Users->save($user)) {
					$this->Auth->logout();
					$this->Flash->success(__('Your password has been changed. Please login again.'));
					return $this->redirect(Configure::read('BackOffice.auth.loginAction'));
				}
			}

		}
		$user->setAccess('password', false);
		// Set user to view
		$this->set(compact('user'));
		// Render view
		$this->render('BackOffice.Account/index');
	}
}
