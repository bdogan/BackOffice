<div class="col-12 col-sm-8 col-md-6 col-lg-5 mx-auto __login">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title mt-2"><?= __('Welcome'); ?></h5>
      <h6 class="card-subtitle mb-2 text-muted"><?= __('Please identify yourself') ?></h6>
    </div>
    <div class="card-body">
  <?= $this->Flash->render(); ?>
  <?= $this->Form->create(); ?>
      <div class="form-group">
        <label for="emailInput"><?= __('Email address'); ?></label>
        <input name="email" type="email" class="form-control" id="emailInput" aria-describedby="emailHelp" placeholder="<?= __('Enter email'); ?>" value="<?= $email ?>">
      </div>
      <div class="form-group">
        <label for="passwordInput"><?= __('Password'); ?></label>
        <input name="password" type="password" class="form-control" id="passwordInput" placeholder="<?= __('Password'); ?>">
      </div>
      <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1"><?= __('Remember me'); ?></label>
      </div>
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary"><?= __('Login'); ?></button>
      </div>
  <?= $this->Form->end(); ?>
    </div>
  </div>
</div>