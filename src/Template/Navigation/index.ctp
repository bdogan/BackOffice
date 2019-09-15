<?php $this->Page->addCrumb( $activeMenu['title'], $activeMenu['action'] ); ?>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <?= $this->Page->icon($activeMenu['icon']) ?>
        <?= $activeMenu['title'] ?>
      </div>
      <div class="card-body">
        <?php foreach ($activeMenu['children'] as $item) { ?>
          <?php
            echo $this->Html->link($this->Page->icon(isset($item['icon']) ? $item['icon'] : 'lens', 'md-18') . $item['title'], $item['action'], [ 'escape' => false, 'class' => 'btn btn-outline-secondary' ]);
          ?>
        <?php } ?>
      </div>
    </div>
  </div>
</div>


