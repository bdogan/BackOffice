<?php

namespace BackOffice\Controller\Frontend;

/**
 * Class PagesController
 *
 * @package BackOffice\Controller\Frontend
 */
class PagesController extends FrontendAppController
{

	/**
	 * Index page
	 */
	public function index()
	{
		echo "hello";
		$this->disableAutoRender();
	}
}
