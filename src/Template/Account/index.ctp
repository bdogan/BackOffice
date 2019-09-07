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
      <?= $this->Form->control('name', [ 'label' => 'Name', 'container' => [ 'class' => 'col-12 mb-3' ] ]); ?>
      <?= $this->Form->control('email', [ 'label' => 'E-Mail', 'container' => [ 'class' => 'col-12 mb-3' ], 'info' => __('This email address also used to login backoffice.') ]); ?>
    </div>
    <?= $this->Form->button(__('Update account info'), [ 'class' => 'btn btn-success' ]); ?>
    <?= $this->Form->end(); ?>
  </div>
</div>

<!-- Password -->
<div class="row form_section">
  <div class="form_section_info col-lg-4">
    <h4 class="form_section_title"><?=__('Password')?></h4>
    <p><?=__('Change your account password. After changed you will be redirect to login page.') ?></p>
  </div>
  <div class="form_section_form col-lg-8">
      <?= $this->Form->create($user); ?>
	    <?= $this->Form->hidden('target', [ 'value' => 'password' ]); ?>
      <div class="form-row mb-2">
	        <?= $this->Form->control('old_password', [ 'type' => 'password', 'label' => 'Current Password', 'container' => [ 'class' => 'col-12 mb-3' ] ]); ?>
          <?= $this->Form->control('new_password', [ 'type' => 'password', 'label' => 'New Password', 'info' => __('Min.8 Max.20 character long'), 'container' => [ 'class' => 'col-lg-6 mb-3' ] ]); ?>
	        <?= $this->Form->control('new_password_verify', [ 'type' => 'password', 'label' => 'New Password Verify', 'container' => [ 'class' => 'col-lg-6 mb-3' ] ]); ?>
      </div>
      <?= $this->Form->button(__('Update password'), [ 'class' => 'btn btn-danger' ]); ?>
      <?= $this->Form->end(); ?>
  </div>
</div>
