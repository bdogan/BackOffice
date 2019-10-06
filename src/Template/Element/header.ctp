<?php
    // Get breadcrumbs
    $crumbs = $this->Page->getCrumbs();
    $currentCrumb = array_pop($crumbs);
    $prevCrumb = array_pop($crumbs);
?>
<header>
    <div class="container br" style="margin: 0">
        <?php if ($prevCrumb) { ?>
            <div class="row">
                <div class="col">
                    <?= $this->Html->link($this->Html->tag('i', 'arrow_back_ios', [ 'class' => 'material-icons-round md-16' ]) . $prevCrumb['title'], $prevCrumb['action'], [ 'escape' => false ]); ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($currentCrumb) { ?>
            <div class="row">
                <h3 class="col"><?= $currentCrumb['title'] ?></h3>
            </div>
        <?php } ?>
    </div>
    <!-- Secondary Page Actions -->
    <div class="container pa" style="margin: 0">
        <div class="row">
            <div class="col-6 secondary">
                <?php foreach ($this->Page->getActions('secondary') as $item) { ?>
                    <?= $this->Html->link((isset($item['icon']) ? $this->Html->tag('i', $item['icon'], [ 'class' => 'material-icons-round' ]) : '') . $item['title'], $item['action'], [ 'escape' => false ]); ?>
                <?php } ?>
            </div>
            <div class="col-6 primary">
                <?php foreach ($this->Page->getActions('primary') as $item) { ?>
                    <?= $this->Html->link((isset($item['icon']) ? $this->Html->tag('i', $item['icon'], [ 'class' => 'material-icons-round' ]) : '') . $item['title'], $item['action'], [ 'class' => 'btn btn-primary', 'escape' => false ]); ?>
                <?php } ?>
            </div>
        </div>
    </div>
</header>
