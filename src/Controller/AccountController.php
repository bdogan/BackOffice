<?php
namespace BackOffice\Controller;

use BackOffice\Controller\AppController;

/**
 * Account Controller
 *
 */
class AccountController extends AppController
{
	public function index()
	{
		$this->render('BackOffice.Account/index');
	}
}
