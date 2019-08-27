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
                    <?= $this->Html->link($this->Html->tag('i', 'arrow_back_ios', [ 'class' => 'material-icons-round md-16' ]) . $prevCrumb['title'], $prevCrumb['link'], $prevCrumb['options'] + [ 'escape' => false ]); ?>
                </div>
            </div>
        <?php } ?>
        <?php if ($currentCrumb) { ?>
            <div class="row">
                <h3 class="col"><?= $currentCrumb['title'] ?></h3>
            </div>
        <?php } ?>
    </div>
    <?= $this->Flash->render() ?>
</header>
