<?php
namespace BackOffice\Controller;

use BackOffice\Controller\AppController;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;

/**
 * ThemeSettings Controller
 *
 * @property-read \BackOffice\Model\Table\ThemesTable $Themes
 */
class ThemeSettingsController extends AppController
{

	/**
	 * @var string
	 */
	public $modelClass = 'BackOffice.Themes';

	/**
	 * @var \BackOffice\Model\Entity\Theme
	 */
	private $theme;

	/**
	 * @param \Cake\Event\Event $event
	 *
	 * @return \Cake\Http\Response|null
	 */
	public function beforeFilter( Event $event )
	{
		// Get theme
		$this->theme = $this->Themes->get($this->getRequest()->getParam('id'));
		return parent::beforeFilter( $event );
	}

	public function templates()
	{

	}
}
