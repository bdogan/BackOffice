<?php
$class = !empty($params['class']) ? $params['class'] : 'info';
if (!isset($params['escape']) || $params['escape'] !== false) {
	$message = h($message);
}
?>
<div class="alert alert-<?= h($class) ?> alert-dismissible fade show" role="alert">
	<?= $message ?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>