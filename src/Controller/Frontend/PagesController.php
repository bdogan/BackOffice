<?php

namespace BackOffice\Controller\Frontend;

use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;

/**
 * Class PagesController
 *
 * @package BackOffice\Controller\Frontend
 */
class PagesController extends FrontendAppController
{

	private $activePage;



	/**
	 * @inheritDoc
	 * @param \Cake\Event\Event $event
	 *
	 * @return \Cake\Http\Response|null
	 * @throws \ReflectionException if the model entity class not exists
	 */
	public function beforeFilter( Event $event )
	{

		// Get active page
		$this->activePage = $this->BackOffice->getActivePage($this->getRequest());

		// Check has page
		if (!$this->activePage) {
			throw new NotFoundException();
		}

	}

	/**
	 * @param \Cake\Event\Event $event
	 *
	 * @return \Cake\Http\Response|void|null
	 */
	public function beforeRender( Event $event ) {

		// Set template
		$this->viewBuilder()->setTemplatePath('Content');

		// Set Layout path
		//$this->viewBuilder()->setLayoutPath(null);

		$this->viewBuilder()->enableAutoLayout(false);

		// Set layout
		$this->viewBuilder()->setLayout(Hash::get($this->activePage, 'data.layout', 'default'));

		// Set page
		$this->set('page', $this->activePage);

	}

	/**
	 * Index page
	 */
	public function index()
	{
		// Disable default render
		$this->disableAutoRender();

		// Render
		$this->render(Hash::get($this->activePage, 'data.template', 'page'), Hash::get($this->activePage, 'data.layout', 'default'));

	}
}
