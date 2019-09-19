<?php
	$this->Page->addCrumb( 'New ' .  Cake\Utility\Inflector::singularize($entity) );
?>
<div class="row form_section">
	<div class="form_section_form col-lg-8">
		<?= $this->Form->create('Province'); ?>
		<?php foreach ($_schema->columns() as $name) { ?>
			<?= $this->Form->control($name, [ 'label' => $name, 'container' => [ 'class' => 'col-12 mb-3' ] ]); ?>
		<?php } ?>
		<?= $this->Form->button(__('Update account info'), [ 'class' => 'btn btn-success' ]); ?>
		<?= $this->Form->end(); ?>
	</div>
</div>
