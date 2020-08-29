<?= $this->Form->create($record, [ 'class' => 'row' ]) ?>
<?= $this->Form->hidden($_primary_key); ?>
<?php $_fields->each(function($name){
  echo $this->Html->tag('div', $this->Form->control( $name, [ 'container' => [ 'class' => 'col-6 mb-3' ] ] ), [ 'class' => 'col-12' ]);
}); ?>
<div class="col-6 mt-3 d-flex justify-content-end">
    <?= $this->Html->link(__('Cancel'), [ 'action' => 'index', 'modelClass' => $modelClass ], [ 'class' => 'btn btn-outline' ]); ?>
    <?= $this->Form->button(__('Save'), [ 'class' => 'btn btn-success' ]); ?>
</div>
<?= $this->Form->end(); ?>


