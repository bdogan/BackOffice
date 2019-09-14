<!DOCTYPE html>
<html lang="tr">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->fetch('title') ?></title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col sidebar">
                <?= $this->element('BackOffice.sidebar'); ?>
            </div>
            <div class="col content">
                <?= $this->element('BackOffice.header'); ?>
                <div class="container-fluid" style="margin: 0">
	                  <?= $this->Flash->render() ?>
                </div>
                <div class="container-fluid" style="margin: 0">
                    <?= $this->fetch('content') ?>
                </div>
            </div>
        </div>
    </div>
    <?= $this->fetch('script') ?>
</body>
</html>
