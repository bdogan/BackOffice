<div class="col-12 col-sm-8 col-md-6 col-lg-5 mx-auto __login">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title mt-2"><?= __('Welcome'); ?></h5>
      <h6 class="card-subtitle mb-2 text-muted"><?= __('Please identify yourself') ?></h6>
    </div>
    <div class="card-body">
      <?= $this->Flash->render(); ?>
      <?= $this->Form->create(); ?>
          <?= $this->Form->control('email', [ 'type' => 'email', 'label' => 'E-Mail address', 'placeholder' => 'Enter a valid e-mail', 'container' => [ 'class' => 'mb-3' ] ]) ?>
          <?= $this->Form->control('password', [ 'type' => 'password', 'label' => 'Password', 'placeholder' => 'Enter account password', 'container' => [ 'class' => 'mb-3' ] ]) ?>
          <?= $this->Form->control('remember_me', [ 'type' => 'checkbox', 'label' => 'Remember me' ]) ?>
          <div class="d-flex justify-content-end">
            <?= $this->Form->button(__('Login'), [ 'class' => 'btn btn-primary' ]); ?>
          </div>
      <?= $this->Form->end(); ?>
    </div>
  </div>
</div>
