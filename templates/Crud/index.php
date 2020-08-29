<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <table class="table table-striped card-body">
        <thead>
          <tr>
            <?php foreach ($_fields as $field) : ?>
              <th><?= $this->Paginator->sort($field) ?></th>
            <?php endforeach; ?>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php /** @var \Cake\ORM\Entity $item */foreach ($_items as $item): ?>
            <tr>
              <?php foreach ($_fields as $field) : ?>
                <td><?= h($item->get($field)); ?></td>
              <?php endforeach; ?>
              <td class="actions">
                <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                  <?= $this->Html->link($this->Page->icon('edit'), [ 'id' => $item->get('_primary'), 'action' => 'update', 'modelClass' => $modelClass ], [ 'escape' => false, 'class' => 'btn btn-dark', 'title' => __('Edit'), 'data-toggle' => 'tooltip' ]); ?>
                  <?= $this->Html->link($this->Page->icon('delete'), [ 'id' => $item->get('_primary'), 'action' => 'delete', 'modelClass' => $modelClass ], [ 'escape' => false, 'class' => 'btn btn-dark', 'data-confirm' => 'Record will be deleted.', 'title' => __('Delete'), 'data-toggle' => 'tooltip' ]); ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
	  <?php if ($this->Paginator->total() > 1) { ?>
      <ul class="pagination justify-content-center mt-3">
	      <?= $this->Paginator->first($this->Page->icon('first_page'), [ 'escape' => false ]); ?>
        <?= $this->Paginator->prev($this->Page->icon('chevron_left'), [ 'escape' => false ]); ?>
			  <?= $this->Paginator->numbers(); ?>
        <?= $this->Paginator->next($this->Page->icon('chevron_right'), [ 'escape' => false ]); ?>
        <?= $this->Paginator->last($this->Page->icon('last_page'), [ 'escape' => false ]); ?>
      </ul>
	  <?php } ?>
  </div>
</div>
