<?php $this->Page->addCrumb( 'Account' );  ?>

<!-- Name -->
<div class="row form_section">
	<div class="form_section_info col-lg-4">
		<h4 class="form_section_title"><?=__('Basic info')?></h4>
		<p><?=__('Change your display name and mail address. After changed you will be redirect to login page.') ?></p>
	</div>
  <div class="form_section_form col-lg-8">
	  <?= $this->Form->create(); ?>
      <div class="form-group">
        <label for="name"><?= __('Name'); ?></label>
        <input name="User.name" type="text" class="form-control" id="name" />
      </div>
      <div class="form-group">
        <label for="mail"><?= __('Mail Address'); ?></label>
        <input name="User.mail" type="text" class="form-control" id="mail" />
      </div>
      <div class="d-flex justify-content-start mt-4">
        <button type="submit" class="btn btn-success"><?= __('Update account info'); ?></button>
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

      <?= $this->Form->create(); ?>
      <div class="row">
          <div class="form-group col-lg-12 clearfix">
            <label for="current_password"><?= __('Current Password'); ?></label>
            <input name="User.Password.current_password" type="text" class="form-control" id="current_password" />
          </div>
          <div class="form-group col-lg-6">
            <label for="new_password"><?= __('New Password'); ?></label>
            <input name="User.Password.new_password" type="password" class="form-control" id="new_password" />
          </div>
          <div class="form-group col-lg-6">
            <label for="new_password_verify"><?= __('New Password Verify'); ?></label>
            <input name="User.Password.new_password_verify" type="password" class="form-control" id="new_password_verify" />
          </div>
          <div class="d-flex col-12 justify-content-start mt-4">
            <button type="submit" class="btn btn-danger"><?= __('Update password'); ?></button>
          </div>
      </div>
      <?= $this->Form->end(); ?>

  </div>
</div>
