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
		// Parse items from config
		$activeMenu = $this->BackOffice->getActiveMenu();
		if (!$activeMenu) {
			throw new NotFoundException(__('ActiveMenu not found'));
		}

		// Set items
		$this->set(compact('activeMenu'));
	}
}
