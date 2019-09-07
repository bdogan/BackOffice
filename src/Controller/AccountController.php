<?php
namespace BackOffice\Controller;

use BackOffice\Controller\AppController;
use BackOffice\Model\Table\UsersTable;
use Cake\Event\Event;
use Cake\ORM\Query;

/**
 * Account Controller
 *
 * @property-read UsersTable $Users
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
		// Get user
		$user = $this->Users->find()->where(['id' => $this->Auth->user('id')])->firstOrFail();
		// Check Request for POST
		if ($this->request->is('put')) {
			// Profile Update
			if ($this->request->getData('target') === 'profile') {
				$this->Users->patchEntity($user, $this->request->getData());
			}
			// Password change
			if ($this->request->getData('target') === 'password') {
				pr($this->request->getData());
				//$this->Users->patchEntity($user, $this->request->getData());
			}
			// Save user to db
			if ($this->Users->save($user)) {
				$this->Auth->setUser($user->toArray());
				$this->Flash->success(__('Your profile has been updated.'), [ 'plugin' => 'BackOffice' ]);
				return $this->redirect([ 'action' => 'index' ]);
			}
		}
		// Set user to view
		$this->set(compact('user'));
		// Render view
		$this->render('BackOffice.Account/index');
	}
}
