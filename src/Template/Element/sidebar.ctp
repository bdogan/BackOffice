<?php

  use Cake\Core\Configure;

  $sidebarMenu = Configure::read('BackOffice.menu.sidebar', []);

?>
<ul class="wrapper">
    <li class="user">
        <div class="avatar"><?= strtoupper(substr($this->Session->read('Auth.User.name'), 0, 1)) ?></div>
        <div class="title">
            <?= $this->Session->read('Auth.User.name') ?> <br />
            Sistem Yöneticisi
        </div>
        <div class="btn-group dropright">
            <button class="dropdown-toggle" data-toggle="dropdown" id="user-menu">
                <i class="material-icons-round">settings_applications</i>
            </button>
            <div class="dropdown-menu" aria-labelledby="user-menu">
                <?= $this->Html->link(__('Çıkış'), Configure::read('BackOffice.auth.logoutAction'), [ 'class' => 'dropdown-item' ]) ?>
            </div>
        </div>
    </li>
    <?php foreach ($sidebarMenu as $name => $item) { ?>
      <li class="menu-item">
          <a href="#" class="active"><i class="material-icons-round"><?=$item['icon']?></i><?=$item['title']?></a>
      </li>
    <?php } ?>
    <li class="menu-item">
        <a href="#" class=""><i class="material-icons-round">local_pharmacy</i>Eczaneler</a>
    </li>
</ul>
