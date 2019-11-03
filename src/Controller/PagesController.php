<?php
namespace BackOffice\Controller;

use BackOffice\Model\Entity\Page;
use Cake\I18n\Time;

/**
 * Pages Controller
 *
 * @property \BackOffice\Model\Table\PagesTable $Pages
 *
 * @method \BackOffice\Model\Entity\Page[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PagesController extends AppController
{

	public function index() {

	}

	public function create() {
		$page = new Page();
		if ($this->request->is('post')) {
			// Data -> Entity
			$this->Pages->patchEntity( $page, $this->request->getData() );

		}
		// Set default values
		$page->set('is_published', $this->request->getData('is_published', true));
		$this->set('page', $page);
	}
}
