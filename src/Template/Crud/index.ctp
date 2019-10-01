<div class="row">
  <div class="col-12">
    <table class="table table-striped bg-white">
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
            <td>
              <?= $this->Html->link($this->Page->icon('delete'), [ 'id' => $item->get('_primary'), 'action' => 'delete', 'modelClass' => $modelClass ], [ 'escape' => false, 'data-confirm' => 'Are you sure you wish to delete record?' ]); ?>
              <?= $this->Html->link($this->Page->icon('play_for_work'), [ 'id' => $item->get('_primary'), 'action' => 'update', 'modelClass' => $modelClass ], [ 'escape' => false ]); ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
