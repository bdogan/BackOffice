<?php $this->Page->addCrumb('Pages'); ?>
<?php $this->Page->addAction('primary', [ 'title' => 'New Page', 'action' => [ 'action' => 'create' ] ]); ?>
<div class="row mb-4">
	<div class="col-12">
		<div class="card">
			<table class="table table-striped card-body">
				<thead>
					<tr>
						<th><?= $this->Paginator->sort('name') ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php /** @var \Cake\ORM\Entity $item */foreach ($items as $item): ?>
					<tr>
						<td><?= h($item->get('name')); ?></td>
						<td class="actions">
							<div class="btn-group btn-group-sm" role="group" aria-label="First group">
								<?= $this->Html->link($this->Page->icon('edit'), [ 'id' => $item->id, 'action' => 'update' ], [ 'escape' => false, 'class' => 'btn btn-dark', 'title' => __('Edit'), 'data-toggle' => 'tooltip' ]); ?>
								<?= $this->Html->link($this->Page->icon('delete'), [ 'id' => $item->id, 'action' => 'delete' ], [ 'escape' => false, 'class' => 'btn btn-dark', 'data-confirm' => 'Page `' . $item->name . '` will be deleted.', 'title' => __('Delete'), 'data-toggle' => 'tooltip' ]); ?>
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
