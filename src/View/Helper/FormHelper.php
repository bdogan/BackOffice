<?php
namespace BackOffice\View\Helper;

use Cake\View\Helper\FormHelper as BaseFormHelper;

/**
 * Form helper
 */
class FormHelper extends BaseFormHelper
{
	/**
	 * Initialize
	 *
	 * @param array $config
	 */
	public function initialize( array $config ) {
		parent::initialize( $config );
		$this->setConfig([
			'errorClass' => 'is-invalid',
			'templates' => [
				'error' => '<div class="invalid-feedback">{{content}}</div>',
				'formGroup' => '{{label}}{{input}}',
				'inputContainer' => '<div class="{{class}} {{type}}{{required}}">{{content}}{{info}}</div>',
				'inputContainerError' => '<div class="{{class}} {{type}}{{required}}">{{content}}{{error}}</div>'
			]
		]);
	}

	/**
	 * @inheritDoc
	 */
	protected function _getInput( $fieldName, $options ) {
		// Set default values
		$options += [
			'class' => ''
		];

		// Set form control
		$options['class'] = 'form-control ' . $options['class'];

		// Return parent call
		return parent::_getInput( $fieldName, $options );
	}

	/**
	 * @inheritDoc
	 */
	public function control( $fieldName, array $options = [] ) {
		$options += [
			'templateVars' => []
		];

		// Input container options
		if (isset($options['container'])) {
			$options['templateVars'] = array_merge($options['templateVars'], $options['container']);
			unset($options['container']);
		}

		// Input info options
		if (isset($options['info'])) {
			$options['templateVars'] = array_merge($options['templateVars'], [ 'info' => $this->Html->tag('small', $options['info'], [ 'id' => $fieldName . '_help', 'class' => 'form-text text-muted' ]) ]);
			unset($options['info']);
		}

		// Render control
		return parent::control( $fieldName, $options );
	}

}
