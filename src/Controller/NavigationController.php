<?php
namespace BackOffice\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;

/**
 * Class NavigationController
 *
 * @package BackOffice\Controller
 */
class NavigationController extends AppController
{

	/**
	 * Index Page
	 */
	public function index()
	{
		// Get item key
		$activeMenuItemKey = $this->getRequest()->getParam('active_menu', null);
		if (!$activeMenuItemKey) {
			throw new NotFoundException();
		}

		// Parse items from config
		$activeMenu = Configure::read('BackOffice.menu.' . $activeMenuItemKey);
		if (!$activeMenu) {
			throw new NotFoundException(__('Menu {0} not found', $activeMenuItemKey));
		}

		// Set items
		$this->set(compact('activeMenu'));
	}
}
