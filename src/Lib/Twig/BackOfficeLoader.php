<?php

namespace BackOffice\Lib\Twig;

use BackOffice\Model\Entity\Page;
use BackOffice\Plugin;
use Cake\Utility\Text;
use Twig\Loader\ExistsLoaderInterface;
use Twig\Loader\LoaderInterface;
use Twig\Loader\SourceContextLoaderInterface;
use Twig\Source;

/**
 * Class BackOfficeLoader
 *
 * @package BackOffice\Lib\Twig
 */
class BackOfficeLoader implements LoaderInterface, ExistsLoaderInterface, SourceContextLoaderInterface
{

	/**
	 * @var \BackOffice\Plugin
	 */
	private $backoffice;

	/**
	 * @var \BackOffice\Model\Entity\Theme
	 */
	private $active_theme;

	/**
	 * @var \BackOffice\Model\Entity\ThemeTemplate[]|Page[]
	 */
	private $resolvedTemplates = [];

	/**
	 * @param $path
	 *
	 * @return \BackOffice\Model\Entity\ThemeTemplate|Page
	 */
	private function getTemplate($path)
	{
		if (isset($this->resolvedTemplates[$path])) return $this->resolvedTemplates[$path];
		return $this->resolvedTemplates[$path] = $this->backoffice->getTemplateByPath($path);
	}

	/**
	 * BackOfficeLoader constructor.
	 *
	 * @param \BackOffice\Plugin $backoffice
	 */
	public function __construct(Plugin $backoffice) {
		$this->backoffice = $backoffice;
		$this->active_theme = $this->backoffice->getActiveTheme();
	}

	/**
	 * @param string $name
	 *
	 * @return string|void
	 */
	public function getSource( $name ) {
		$template = $this->getTemplate($name);
		if ($template instanceof Page) {
			return $template->body;
		}
		return $template->content;
	}

	/**
	 * @param string $name
	 *
	 * @return string|void
	 */
	public function getCacheKey( $name )
	{
		return Text::slug($this->getTemplate($name)->alias());
	}

	/**
	 * @param string $name
	 * @param int $time
	 *
	 * @return bool|void
	 */
	public function isFresh( $name, $time )
	{
		$template = $this->getTemplate($name);
		return $template->modified()->getTimestamp() <= $time;
	}

	/**
	 * @param string $name
	 *
	 * @return bool|void
	 */
	public function exists( $name ) {
		return !!$this->getTemplate($name);
	}

	/**
	 * @param string $name
	 *
	 * @return \Twig\Source|void
	 */
	public function getSourceContext( $name ) {
		$template = $this->getTemplate($name);
		if ($template instanceof Page) {
			return new Source($template->body, $name);
		}
		return new Source($template->content, $name);
	}


}
