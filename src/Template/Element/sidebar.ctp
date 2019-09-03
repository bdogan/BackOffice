<?php

  use Cake\Core\Configure;

  $sidebarMenu = Configure::read('BackOffice.map.sidebar_menu');
?>
<ul class="wrapper">
    <li class="user">
        <div class="avatar">U</div>
        <div class="title">
            Uğur Eskici <br />
            Sistem Yöneticisi
        </div>
        <div class="btn-group dropright">
            <button class="dropdown-toggle" data-toggle="dropdown" id="user-menu">
                <i class="material-icons-round">settings_applications</i>
            </button>
            <div class="dropdown-menu" aria-labelledby="user-menu">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
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
