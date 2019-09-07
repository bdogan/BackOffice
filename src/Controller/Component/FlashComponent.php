<?php
namespace BackOffice\Controller\Component;

use Cake\Controller\Component\FlashComponent as BaseFlashComponent;

/**
 * Flash component
 *
 * @method void primary(string $message, array $options = []) Set a message using "primary" class
 * @method void secondary(string $message, array $options = []) Set a message using "secondary" class
 * @method void danger(string $message, array $options = []) Set a message using "danger" class
 * @method void warning(string $message, array $options = []) Set a message using "warning" class
 * @method void info(string $message, array $options = []) Set a message using "info" class
 * @method void light(string $message, array $options = []) Set a message using "light" class
 * @method void dark(string $message, array $options = []) Set a message using "dark" class
 */
class FlashComponent extends BaseFlashComponent
{

	/**
	 * Returns element name to class name
	 *
	 * @param $element
	 *
	 * @return string
	 */
	private function elementToClass($element = null) {
		switch ($element) {
			case null:
			case 'default': return 'primary';
			case 'error': return 'danger';
			default: return $element;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function set( $message, array $options = [] ) {
		$options = array_merge($options, [
			'element' => 'BackOffice.default',
			'params' => [
				'class' => $this->elementToClass($options['element'])
			]
		]);
		parent::set( $message, $options );
	}
}
