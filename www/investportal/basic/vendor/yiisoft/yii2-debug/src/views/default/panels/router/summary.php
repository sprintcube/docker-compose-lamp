<?php

use \yii\helpers\Html;

/* @var $panel yii\debug\panels\RouterPanel */

?>
<div class="yii-debug-toolbar__block">
    <a href="<?= $panel->getUrl() ?>" title="Action: <?= Html::encode($panel->data['action']) ?>">Route <span
            class="yii-debug-toolbar__label"><?= Html::encode($panel->data['route']) ?></span></a>
</div>
