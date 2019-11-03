<?php $this->Page->addCrumb('Pages', [ '_name' => 'backoffice:pages.index' ]); ?>
<?php $this->Page->addCrumb('New Page'); ?>

<?= $this->Form->create($page, [ 'class' => 'row' ]) ?>
<?php if ($page->id) echo $this->Form->hidden('id', [ 'value' => $page->id ]); ?>
<div class="col-12">
  <div class="row">
    <div class="col-md-8">
      <div class="card mb-3">
        <div class="card-body">
	        <?= $this->Form->control('name', [ 'container' => [ 'class' => 'col-12 p-0 mb-3' ], 'placeholder' => 'Beautiful page' ] ); ?>
	        <?= $this->Form->control('body', [ 'container' => [ 'class' => 'col-12 p-0 mb-3' ], 'rows' => 14, 'data-code-mirror' => 'true' ] ); ?>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <div class=" d-flex justify-content-between">
            <h5 class="card-title">Search engine result preview</h5>
            <a href="#" onclick="setSeoMode('custom'); $(this).remove(); return false;">Edit</a>
          </div>
          <p class="card-text text-muted font-weight-light">Add a title and description to see how this product might appear in a search engine listing.</p>
          <div class="seo_preview" style="display: none;">
            <p class="title"></p>
            <p class="link"><?= $this->Url->build('/', [ 'fullBase' => true ]); ?><span></span></p>
            <p class="description"></p>
          </div>
        </div>
        <div class="card-body border-top" data-visible="seoMode" style="display: none;">
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
	        <?= $this->Form->control('is_published', [ 'type' => 'checkbox', 'checked' => !!$page->is_published, 'label' => 'Published', 'container' => [ 'class' => 'mt-2 mb-1' ] ]) ?>
        </div>
        <div class="card-body border-top" style="<?= ($page->is_published ? '' : 'display: none;')?>">
	        <?= $this->Form->control('published_after', [ 'label' => 'Publish After', 'container' => [ 'class' => 'published_after mt-0 mb-2' ] ]); ?>
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

  <div class="d-flex justify-content-end border-top pt-3 mb-5">
    <?= $this->Html->link(__('Cancel'), [ 'action' => 'index' ], [ 'class' => 'btn btn-outline mr-3' ]); ?>
    <?= $this->Form->button(__('Create'), [ 'class' => 'btn btn-success' ]); ?>
  </div>
</div>
<?= $this->Form->end(); ?>

<script type="application/javascript">
<?php $this->Html->scriptStart([ 'block' => true ]); ?>
(function($, window, getSlug){

  // Seo mode
  let seoMode = null;
  window.setSeoMode = function(mode) {
    if (seoMode !== mode) {
      seoMode = mode;
      $("[data-visible='seoMode']")[seoMode === 'auto' ? 'slideUp' : 'slideDown'](100);
    }
  };

  /**
   * Show/Hide Date input based on is_published flag
   */
  $("input[name='is_published']").on('change', function(){
    const targetEl = $(".published_after select");
    targetEl[this.checked ? 'removeAttr' : 'attr']('disabled', 'disabled')
      .closest('.card-body')[this.checked ? 'slideDown' : 'slideUp'](100);
  });

  /**
   * Autogenerate Seo params
   */
  const tmpEl = document.createElement("DIV");
  $("[name='name'], [name='body']").on('input', function () {
    // Get Value
    const value = $(this).val();
    // Targets
    const titleTarget = $("[name='title']");
    const descriptionTarget = $("[name='description']");
    const slugTarget = $("[name='slug']");
    switch ($(this).attr('name')) {
      // Name source
      case 'name':
        // Page Title
        if (!titleTarget.data('dirty')) titleTarget.val(value.slice(0, 100));
        // Slug
        if (!slugTarget.data('dirty')) slugTarget.val(getSlug(value).slice(0, 100));
        break;
      // Body source
      case 'body':
        // Description
        if (!descriptionTarget.data('dirty')) {
          tmpEl.innerHTML = value.slice(0, 250);
          descriptionTarget.val(tmpEl.textContent || tmpEl.innerText || "");
        }
        break;
    }
    renderSeoPreview();
  });

  /**
   * Seo preview generation
   */
  const renderSeoPreview = function(){
    const seoPreviewEl = $(".seo_preview");
    const preview = [ $("input[name='title']").val().trim(), $("textarea[name='description']").val().trim(), $("input[name='slug']").val().trim() ];
    seoPreviewEl[preview.join('').length > 0 ? 'show' : 'hide']().prev()[preview.join('').length > 0 ? 'hide' : 'show']();
    seoPreviewEl.find('.title').html(preview[0] || "&nbsp;");
    seoPreviewEl.find('.link > span').html(preview[2] || "&nbsp;");
    seoPreviewEl.find('.description').html(preview[1] || "&nbsp;");
  };
  $("input[name='title'], textarea[name='description'], input[name='slug']").on('input', function () {
    $(this).data('dirty', true);
    renderSeoPreview();
  });
  renderSeoPreview();

  // Set seo mode as
  setSeoMode('auto');

})($, window, getSlug);
<?php $this->Html->scriptEnd(); ?>
</script>
