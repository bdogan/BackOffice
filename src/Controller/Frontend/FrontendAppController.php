<?php

namespace BackOffice\Controller\Frontend;

use App\Controller\AppController;
use BackOffice\View\FrontendView;
use Cake\Core\Configure;

/**
 * Class FrontendAppController
 *
 * @package BackOffice\Controller\Frontend
 */
class FrontendAppController extends AppController
{

	/**
	 * @var \BackOffice\Plugin
	 */
	protected $BackOffice;

	/**
	 * App Controller initialize
	 *
	 * @throws \Exception
	 */
	public function initialize()
	{
		// Set back office
		$this->BackOffice = Configure::read('BackOffice');
		// Set view class
		$this->viewBuilder()->setClassName(FrontendView::class);
	}

}
