<?php $this->Page->addCrumb( 'Account' );  ?>

<!-- Name -->
<div class="row form_section">
	<div class="form_section_info col-lg-4">
		<h4 class="form_section_title"><?=__('Basic info')?></h4>
		<p><?=__('Change your display name and mail address. After changed you will be redirect to login page.') ?></p>
	</div>
  <div class="form_section_form col-lg-8">
	  <?= $this->Form->create($user); ?>
	    <?= $this->Form->hidden('target', [ 'value' => 'profile' ]); ?>
      <div class="form-group">
        <?= $this->Form->control('name', [ 'class' => 'form-control' ]); ?>
      </div>
      <div class="form-group">
	      <?= $this->Form->control('email', [ 'class' => 'form-control' ]); ?>
      </div>
      <div class="d-flex justify-content-start mt-4">
        <?= $this->Form->button(__('Update account info'), [ 'class' => 'btn btn-success' ]); ?>
      </div>
	  <?= $this->Form->end(); ?>
  </div>
</div>

<!-- Password -->
<div class="row form_section">
  <div class="form_section_info col-lg-4">
    <h4 class="form_section_title"><?=__('Password')?></h4>
    <p><?=__('Change your email address. After changed you will be redirect to login page.') ?></p>
  </div>
  <div class="form_section_form col-lg-8">
      <?= $this->Form->create($user); ?>
	    <?= $this->Form->hidden('target', [ 'value' => 'password' ]); ?>
      <div class="row">
          <div class="form-group col-lg-12 clearfix">
	            <?= $this->Form->control('current_password', [ 'class' => 'form-control', 'label' => 'Current Password' ]); ?>
          </div>
          <div class="form-group col-lg-6">
	            <?= $this->Form->control('new_password', [ 'class' => 'form-control', 'label' => 'New Password' ]); ?>
          </div>
          <div class="form-group col-lg-6">
	            <?= $this->Form->control('new_password_verify', [ 'class' => 'form-control', 'label' => 'New Password Verify' ]); ?>
          </div>
          <div class="d-flex col-12 justify-content-start mt-4">
	            <?= $this->Form->button(__('Update password'), [ 'class' => 'btn btn-danger' ]); ?>
          </div>
      </div>
      <?= $this->Form->end(); ?>

  </div>
</div>
