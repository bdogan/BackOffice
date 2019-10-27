<?php $this->Page->addCrumb('Pages', [ '_name' => 'backoffice:pages.index' ]); ?>
<?php $this->Page->addCrumb('New Page'); ?>

<?= $this->Form->create($page, [ 'class' => 'row' ]) ?>

<div class="col-12">
  <div class="row">
    <div class="col-md-8">
      <div class="card mb-3">
        <div class="card-body">
	        <?= $this->Form->control('name', [ 'container' => [ 'class' => 'col-12 p-0 mb-3' ], 'placeholder' => 'Beautiful page' ] ); ?>
	        <?= $this->Form->control('body', [ 'container' => [ 'class' => 'col-12 p-0 mb-3' ] ] ); ?>
        </div>
      </div>
      <div class="card">
        <div class="card-body border-bottom">
          <div class=" d-flex justify-content-between">
            <h5 class="card-title">Search engine result preview</h5>
          </div>
          <p class="card-text text-muted font-weight-light">Add a title and description to see how this product might appear in a search engine listing.</p>
          <div class="seo_preview" style="display: none;">
            <p class="title"></p>
            <p class="link"><?= $this->Url->build('/', [ 'fullBase' => true ]); ?><span></span></p>
            <p class="description"></p>
          </div>
        </div>
        <div class="card-body">
          <?= $this->Form->control('title', [ 'container' => [ 'class' => 'col-12 p-0 mb-3' ] ] ); ?>
          <?= $this->Form->control('description', [ 'type' => 'textarea', 'container' => [ 'class' => 'col-12 p-0 mb-3' ] ] ); ?>
          <?= $this->Form->control('slug', [ 'container' => [ 'class' => 'col-12 p-0 mb-3' ], 'prefix' => $this->Url->build('/', [ 'fullBase' => true ]) ] ); ?>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card mb-3">
        <div class="card-body border-bottom">
          <h5 class="card-title">Visibility</h5>
          <p class="card-text text-muted font-weight-light">You can set date for page visibility.</p>
        </div>
        <div class="card-body">
	        <?= $this->Form->control('is_published', [ 'type' => 'checkbox', 'label' => 'Published', 'container' => [ 'class' => 'mt-2 mb-1' ] ]) ?>
        </div>
        <div class="card-body border-top">
	        <?= $this->Form->control('published_after', [ 'type' => 'datetime-local', 'label' => 'Publish After', 'container' => [ 'class' => 'mt-0 mb-2' ] ]); ?>
        </div>
      </div>
      <div class="card">
        <div class="card-body border-bottom">
          <h5 class="card-title">Theme options</h5>
          <p class="card-text text-muted font-weight-light">You can change page layout and template.</p>
        </div>
        <div class="card-body">
	        <?= $this->Form->control('layout', [ 'container' => [ 'class' => 'col-12 p-0 mb-3' ] ] ); ?>
          <?= $this->Form->control('template', [ 'container' => [ 'class' => 'col-12 p-0 mb-3' ] ] ); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="col-12 mt-3">

  <div class="d-flex justify-content-end border-top pt-3">
    <?= $this->Html->link(__('Cancel'), [ 'action' => 'index' ], [ 'class' => 'btn btn-outline mr-3' ]); ?>
    <?= $this->Form->button(__('Create'), [ 'class' => 'btn btn-success' ]); ?>
  </div>
</div>
<?= $this->Form->end(); ?>
<?php $this->Html->scriptStart([ 'block' => true ]); ?>
  $("input[name='is_published']").on('change', function(){
    $("input[name='published_after']")[this.checked ? 'removeAttr' : 'attr']('disabled', 'disabled').trigger('change');
    $("input[name='published_after']").closest('.card-body')[this.checked ? 'slideDown' : 'slideUp'](100);
  });
  $("input[name='title'], textarea[name='description'], input[name='slug']").on('input', function(){
    const preview = [ $("input[name='title']").val().trim(), $("textarea[name='description']").val().trim(), $("input[name='slug']").val().trim() ];
    if (preview.join('').length > 0) {
      $(".seo_preview").show().prev().hide();
    } else {
      $(".seo_preview").hide().prev().show();
    }
    $(".seo_preview").find('.title').html(preview[0]);
    $(".seo_preview").find('.link > span').html(preview[2]);
    $(".seo_preview").find('.description').html(preview[1]);
  }).trigger('input');
<?php $this->Html->scriptEnd(); ?>

