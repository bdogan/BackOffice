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
		$page->set('is_published', true);
		$page->set('published_after', str_replace(' ', 'T', Time::now()->i18nFormat("yyyy-MM-dd HH:mm")));
		$this->set('page', $page);
	}
}
