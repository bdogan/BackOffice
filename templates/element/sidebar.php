<?php
  use Cake\Utility\Hash;

  $circleShape = '<svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="#B7BCCA"/></svg>';

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
                <i class="material-icons-round">account_circle</i>
            </button>
            <div class="dropdown-menu" aria-labelledby="user-menu">
	              <?= $this->Html->link(__('Account'), [ '_name' => 'backoffice:account.index' ], [ 'class' => 'dropdown-item' ]) ?>
                <div class="dropdown-divider"></div>
                <?= $this->Html->link(__('Logout'), [ '_name' => 'backoffice:auth.logout' ], [ 'class' => 'dropdown-item' ]) ?>
            </div>
        </div>
    </li>
    <?php foreach ($this->Page->getMenu() as $item) { ?>
        <li class="menu-item">
            <?php $isActive = $this->Page->isActiveMenu($item); ?>
            <?= $this->Html->link($this->Html->tag('i', $item['icon'], [ 'class' => 'material-icons-round' ]) . $item['title'], $item['action'], [ 'escape' => false, 'class' => $isActive ? 'active' : '' ]) ?>
            <?php if (isset($item['children']) && $isActive) { ?>
                <ul>
                    <?php foreach ($item['children'] as $subItem) { ?>
                        <li class="menu-item">
	                          <?= $this->Html->link($this->Html->tag('i', 'lens', [ 'class' => 'material-icons-round' ]) . $subItem['title'], $subItem['action'], [ 'escape' => false, 'class' => $this->Page->isActiveMenu($subItem) ? 'active' : '' ]) ?>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </li>
    <?php } ?>
</ul>
