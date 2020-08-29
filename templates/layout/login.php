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
			<?= $this->fetch('content') ?>
		</div>
	</div>
	<?= $this->fetch('script') ?>
</body>
</html>
