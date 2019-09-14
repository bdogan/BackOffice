<?php
// Set breadcrumb
if ($parentMenu) {
	$this->Page->addCrumb( $parentMenu['title'], $parentMenu['action'] );
}
$this->Page->addCrumb( $activeMenu['title'], $activeMenu['action'] );
?>
