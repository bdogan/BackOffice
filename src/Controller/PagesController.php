<?php
namespace BackOffice\Controller;

use BackOffice\Model\Entity\Page;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

/**
 * Pages Controller
 *
 * @property \BackOffice\Model\Table\PagesTable $Pages
 *
 * @method \BackOffice\Model\Entity\Page[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PagesController extends AppController
{

	public function index()
	{
		$this->paginate = [
			'order' => [
				'Page.name' => 'asc'
			]
		];
		// Get items
		$items = $this->paginate($this->Pages);
		// Set items
		$this->set('items', $items);
	}

	/**
	 * Creates new page
	 */
	public function create()
	{
		$page = new Page();

		if ($this->request->is('post')) {
			// Set published date
			$page->set('published_after', $this->request->getData('published_after', null));
			// Data -> Entity
			$this->Pages->patchEntity( $page, $this->request->getData() );
			// Save to database
			if ($this->Pages->save($page)) {
				$this->Flash->success(__('New page has been created successfully.'));
				return $this->redirect([ 'action' => 'index' ]);
			}
		}

		// Set layouts & templates
		$this->set('layouts', $this->BackOffice->getTemplates('layout')->combine('name', 'name'));
		$this->set('templates', $this->BackOffice->getTemplates('content')->combine('name', 'name'));

		// Set virtual values
		$page->set('is_published', $this->request->getData('is_published', true));

		// Set entity
		$this->set('page', $page);
	}

	/**
	 * Updates a page
	 */
	public function update()
	{
		// Get page
		$page = $this->Pages->get($this->request->getParam('id'));

		// Check page is alive
		if (!$page) {
			throw new NotFoundException('Page not found!');
		}
		// Check Request for POST
		if ($this->request->is('put')) {
			// Set published date
			$page->set('published_after', $this->request->getData('published_after', null));
			// Data -> Entity
			$this->Pages->patchEntity($page, $this->request->getData());
			// Save to database
			if ($this->Pages->save($page)) {
				$this->Flash->success(__('Page has been updated successfully.'));
				return $this->redirect([ 'action' => 'index' ]);
			}
		}

		// Set layouts & templates
		$this->set('layouts', $this->BackOffice->getTemplates('layout')->combine('name', 'name'));
		$this->set('templates', $this->BackOffice->getTemplates('content')->combine('name', 'name'));

		// Set virtual values
		$page->set('is_published', $this->request->getData('is_published', !!$page->published_after));

		// Set page
		$this->set('page', $page);

		// Set as template to create
		$this->viewBuilder()->setTemplate('create');
	}

	public function delete()
	{
		// Get page
		$page = $this->Pages->get($this->request->getParam('id'));

		// Check page is alive
		if (!$page) {
			throw new NotFoundException('Page not found!');
		}

		// Check is system default
		if ($page->is_system_default) {
			throw new BadRequestException('Current page cannot be deleted');
		}

		$this->Pages->deleteOrFail($page);
		$this->Flash->success(__('Page has been deleted successfully.'));
		return $this->redirect([ 'action' => 'index' ]);
	}
}
