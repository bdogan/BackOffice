<?php

namespace BackOffice\View;

use BackOffice\Lib\Twig\BackOfficeCache;
use BackOffice\Lib\Twig\BackOfficeLoader;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\Utility\Security;
use Cake\Utility\Text;
use Cake\View\Exception\MissingLayoutException;
use Cake\View\Exception\MissingTemplateException;
use WyriHaximus\TwigView\Event\LoaderEvent;
use WyriHaximus\TwigView\View\TwigView;

/**
 * Class FrontendView
 *
 * @package BackOffice\View
 */
class FrontendView extends TwigView
{

	/**
	 * Types
	 */
	const TEMPLATE_CONTENT_TYPE = 'Content';
	const LAYOUT_CONTENT_TYPE = 'Layout';

	/**
	 * @var null
	 */
	protected $subDir = null;

	/**
	 * @var \BackOffice\Plugin
	 */
	protected $backoffice;

	/**
	 * @var \BackOffice\Model\Entity\Theme
	 */
	protected $activeTheme;

	/**
	 * @var array
	 */
	protected $activePage;

	/**
	 * @param null $plugin
	 * @param bool $cached
	 *
	 * @return array
	 */
	protected function _paths( $plugin = null, $cached = true )
	{
		return parent::_paths($plugin, $cached);
	}

	/**
	 * Initialize
	 */
	public function initialize()
	{
		$this->backoffice = Configure::read('BackOffice');
		$this->activeTheme = $this->backoffice->getActiveTheme();
		$this->activePage = $this->backoffice->getActivePage($this->getRequest());

		if ($this->activePage) {
			$this->getEventManager()->on('View.beforeRender', [ $this, 'beforeRender' ]);
			$this->getEventManager()->on(LoaderEvent::EVENT, [ $this, 'loader' ]);
		}

		Configure::write(self::ENV_CONFIG, [
			'cache' => new BackOfficeCache(CACHE . 'twigView' . DS)
		]);

		parent::initialize();

		$this->loadHelper('Html');
		$this->loadHelper('Form');
		$this->loadHelper('Flash');
		$this->loadHelper('Url');
	}

	/**
	 * @return \Twig\Loader\LoaderInterface
	 */
	public function loader(LoaderEvent $event)
	{
		$event->result = new BackOfficeLoader($this->backoffice, $this->activeTheme);
	}

	/**
	 * @param \Cake\Event\Event $event
	 *
	 * @throws \Exception
	 */
	public function beforeRender(Event $event)
	{
		if ($this->activePage) $this->Blocks->set('page', $this->_render('Page.' . $this->activePage['data']->id));
	}

	/**
	 * @param null $name
	 *
	 * @return string
	 * @throws \Exception
	 */
	protected function _getViewFileName( $name = null )
	{
		if (!$this->activePage || $name !== null) return parent::_getViewFileName($name);
		return self::TEMPLATE_CONTENT_TYPE . '.' . Hash::get($this->activePage, 'data.template');
	}

	/**
	 * @param null $name
	 *
	 * @return string
	 * @throws \Exception
	 */
	protected function _getLayoutFileName( $name = null )
	{
		if (!$this->activePage) return parent::_getLayoutFileName($name);
		return self::LAYOUT_CONTENT_TYPE  . '.' . Hash::get($this->activePage, 'data.layout');
	}

}
