<?php $this->Page->addCrumb( 'Account' );  ?>

<!-- Name -->
<div class="row form_section">
	<div class="form_section_info col-lg-4">
		<h4 class="form_section_title"><?=__('Basic info')?></h4>
		<p><?=__('Change your display name and mail address.') ?></p>
	</div>
  <div class="form_section_form col-lg-8">
    <?= $this->Form->create($user); ?>
    <?= $this->Form->hidden('target', [ 'value' => 'profile' ]); ?>
    <div class="form-row mb-2">
      <?= $this->Form->control('name', [ 'class' => 'form-control', 'label' => 'Name', 'templateVars' => [ 'class' => 'col-12 mb-3' ] ]); ?>
      <?= $this->Form->control('email', [ 'class' => 'form-control', 'label' => 'E-Mail', 'templateVars' => [ 'class' => 'col-12 mb-3' ] ]); ?>
    </div>
    <?= $this->Form->button(__('Update account info'), [ 'class' => 'btn btn-success' ]); ?>
    <?= $this->Form->end(); ?>
  </div>
</div>

<!-- Password -->
<div class="row form_section">
  <div class="form_section_info col-lg-4">
    <h4 class="form_section_title"><?=__('Password')?></h4>
    <p><?=__('Change your account password.') ?></p>
  </div>
  <div class="form_section_form col-lg-8">
      <?= $this->Form->create($user); ?>
	    <?= $this->Form->hidden('target', [ 'value' => 'password' ]); ?>
      <div class="form-row mb-2">
	        <?= $this->Form->control('old_password', [ 'type' => 'password', 'class' => 'form-control', 'label' => 'Current Password', 'templateVars' => [ 'class' => 'col-12 mb-3' ] ]); ?>
          <?= $this->Form->control('new_password', [ 'type' => 'password', 'class' => 'form-control', 'label' => 'New Password', 'templateVars' => [ 'class' => 'col-lg-6 mb-3' ] ]); ?>
	        <?= $this->Form->control('new_password_verify', [ 'type' => 'password', 'class' => 'form-control', 'label' => 'New Password Verify', 'templateVars' => [ 'class' => 'col-lg-6 mb-3' ] ]); ?>
      </div>
      <?= $this->Form->button(__('Update password'), [ 'class' => 'btn btn-danger' ]); ?>
      <?= $this->Form->end(); ?>
  </div>
</div>
