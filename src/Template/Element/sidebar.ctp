<?php

  use Cake\Core\Configure;

  $sidebarMenu = Configure::read('BackOffice.menu.sidebar', []);

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
	              <?= $this->Html->link(__('Account'), Configure::read('BackOffice.routes.account.action'), [ 'class' => 'dropdown-item' ]) ?>
                <div class="dropdown-divider"></div>
                <?= $this->Html->link(__('Logout'), Configure::read('BackOffice.auth.logoutAction'), [ 'class' => 'dropdown-item' ]) ?>
            </div>
        </div>
    </li>
    <?php foreach ($sidebarMenu as $name => $item) { ?>
        <li class="menu-item">
            <?php
                $isActive = $this->Url->build($item['route']['action']) === $this->Url->build(null);
            ?>
            <?= $this->Html->link($this->Html->tag('i', $item['icon'], [ 'class' => 'material-icons-round' ]) . $item['title'], $item['route']['action'], [ 'escape' => false, 'class' => $isActive ? 'active' : '' ]) ?>
        </li>
    <?php } ?>
</ul>
