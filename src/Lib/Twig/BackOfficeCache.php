<?php

namespace BackOffice\Lib\Twig;

use BackOffice\Plugin;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Utility\Text;
use Twig\Cache\FilesystemCache;

/**
 * Class BackOfficeCache
 *
 * @package BackOffice\Lib\Twig
 */
class BackOfficeCache extends FilesystemCache
{
	/**
	 * @var \BackOffice\Plugin
	 */
	protected $backoffice;

	/**
	 * @var array|\BackOffice\Model\Entity\Theme|\Cake\Datasource\EntityInterface|null
	 */
	protected $active_theme;

	/**
	 * @var string
	 */
	private $directory;

	/**
	 * BackOfficeCache constructor.
	 *
	 * @param $directory
	 * @param \BackOffice\Plugin $backoffice
	 * @param int $options
	 */
	public function __construct( $directory, Plugin $backoffice, $options = 0 )
	{
		parent::__construct( $directory, $options );
		$this->backoffice   = $backoffice;
		$this->active_theme = $this->backoffice->getActiveTheme();
		$this->directory    = $directory;
	}

	/**
	 * @param string $name
	 * @param string $className
	 *
	 * @return string
	 */
	public function generateKey( $name, $className )
	{
		$node = $this->backoffice->getTemplateByPath($name);
		$key = $this->directory . $this->active_theme->alias . DS . $node->modified()->getTimestamp() . '_' . strtolower(Text::slug($name)) . '.php';

		// Delete old files
		if (!file_exists($key)) {
			$dir = new Folder($this->directory . $this->active_theme->alias);
			$files = $dir->find('[0-9]+_' . strtolower(Text::slug($name)) . '\.php');
			foreach ($files as $file) {
				$file = new File($dir->path . DS . $file);
				$file->delete();
			}
		}

		return $key;
	}

}
