<?php
  use Cake\Core\Configure;
  use Cake\Utility\Hash;
?>
<ul class="wrapper">
    <li class="user">
        <div class="avatar"><?= strtoupper(substr($this->request->getSession()->read('Auth.User.name'), 0, 1)) ?></div>
        <div class="title">
            <?= $this->request->getSession()->read('Auth.User.name') ?> <br />
            <?= __(\BackOffice\Type\UserRole::getTitle($this->request->getSession()->read('Auth.User.role'))) ?>
        </div>
        <div class="btn-group dropright">
            <button class="dropdown-toggle" data-toggle="dropdown" id="user-menu">
                <i class="material-icons-round">settings_applications</i>
            </button>
            <div class="dropdown-menu" aria-labelledby="user-menu">
	              <?= $this->Html->link(__('Account'), [ '_name' => 'account' ], [ 'class' => 'dropdown-item' ]) ?>
                <div class="dropdown-divider"></div>
                <?= $this->Html->link(__('Logout'), [ '_name' => 'logout' ], [ 'class' => 'dropdown-item' ]) ?>
            </div>
        </div>
    </li>
    <?php foreach ($this->Page->getMenu('sidebar') as $item) { ?>
        <li class="menu-item">
            <?= $this->Html->link($this->Html->tag('i', $item['icon'], [ 'class' => 'material-icons-round' ]) . $item['title'], $item['action'], [ 'escape' => false, 'class' => $this->Page->isActiveAction($item['action'], Hash::get($item, 'exact', false)) ? 'active' : '' ]) ?>
        </li>
    <?php } ?>
</ul>
