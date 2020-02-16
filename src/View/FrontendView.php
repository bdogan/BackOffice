<?php

namespace BackOffice\View;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\View\Exception\MissingLayoutException;
use Cake\View\Exception\MissingTemplateException;
use WyriHaximus\TwigView\View\TwigView;

/**
 * Class FrontendView
 *
 * @package BackOffice\View
 */
class FrontendView extends TwigView
{

	/**
	 * Template
	 */
	const TEMPLATE_CACHE_DIR = CACHE . 'template';

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
		parent::initialize();
		$this->backoffice = Configure::read('BackOffice');
		$this->activeTheme = $this->backoffice->getActiveTheme();
		$this->activePage = $this->backoffice->getActivePage($this->getRequest());
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

		$templateName = Hash::get($this->activePage, 'data.template');

		$template = $this->backoffice->getTemplate(self::TEMPLATE_CONTENT_TYPE, $templateName);

		if ($template) {
			$path = self::TEMPLATE_CACHE_DIR . DS . $this->activeTheme->alias . DS . $template->type . DS;
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
			}
			if (!file_exists($path . $template->name . TwigView::EXT)) {
				file_put_contents($path . $template->name . TwigView::EXT, $template->content);
			}
			if (file_exists($path . $template->name . TwigView::EXT)) {
				return $path . $template->name . TwigView::EXT;
			}
		}

		throw new MissingTemplateException([ 'theme' => $this->activeTheme->alias, 'type' => self::TEMPLATE_CONTENT_TYPE, 'template' => $templateName ]);
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

		$layoutName = Hash::get($this->activePage, 'data.layout');
		$path = self::TEMPLATE_CACHE_DIR . DS . $this->activeTheme->alias . DS . self::LAYOUT_CONTENT_TYPE . DS;

		// From cache
		if (file_exists($path . $layoutName . TwigView::EXT)) {
			return $path . $layoutName . TwigView::EXT;
		}


		$layout = $this->backoffice->getTemplate(self::LAYOUT_CONTENT_TYPE, $layoutName);

		if ($layout) {

			if (!file_exists($path)) {
				mkdir($path, 0775, true);
			}
			if (!file_exists($path . $layout->name . TwigView::EXT)) {
				file_put_contents($path . $layout->name . TwigView::EXT, $layout->content);
			}
			if (file_exists($path . $layout->name . TwigView::EXT)) {
				return $path . $layout->name . TwigView::EXT;
			}
		}

		throw new MissingLayoutException([ 'theme' => $this->activeTheme->alias, 'type' => self::LAYOUT_CONTENT_TYPE, 'layout' => $layoutName ]);
	}

}
