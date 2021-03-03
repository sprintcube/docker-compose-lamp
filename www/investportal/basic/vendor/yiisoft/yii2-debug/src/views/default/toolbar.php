<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $panels \yii\debug\Panel[] */
/* @var $tag string */
/* @var $position string */
/* @var $defaultHeight int */

$firstPanel = reset($panels);
$url = $firstPanel->getUrl();
?>
<div id="yii-debug-toolbar" class="yii-debug-toolbar yii-debug-toolbar_position_<?= $position ?>" data-height="<?= $defaultHeight ?>">
    <div class="yii-debug-toolbar__resize-handle"></div>
    <div class="yii-debug-toolbar__bar">
        <div class="yii-debug-toolbar__block yii-debug-toolbar__title">
            <a href="<?= Url::to(['index']) ?>">
                <img width="30" height="30" alt="Yii" src="<?= \yii\debug\Module::getYiiLogo() ?>">
            </a>
        </div>

        <div class="yii-debug-toolbar__block yii-debug-toolbar__ajax" style="display: none">
            AJAX <span class="yii-debug-toolbar__label yii-debug-toolbar__ajax_counter">0</span>
            <div class="yii-debug-toolbar__ajax_info">
                <table>
                    <thead>
                    <tr>
                        <th>Method</th>
                        <th>Status</th>
                        <th>URL</th>
                        <th>Time</th>
                        <th>Profile</th>
                    </tr>
                    </thead>
                    <tbody class="yii-debug-toolbar__ajax_requests"></tbody>
                </table>
            </div>
        </div>

        <?php foreach ($panels as $panel): ?>
            <?php if ($panel->hasError()): ?>
                <div class="yii-debug-toolbar__block">
                    <a href="<?= $panel->getUrl() ?>"
                       title="<?= Html::encode($panel->getError()->getMessage()); ?>"><?= Html::encode($panel->getName()) ?>
                        <span class="yii-debug-toolbar__label yii-debug-toolbar__label_error">error</span></a>
                </div>
            <?php else: ?>
                <?= $panel->getSummary() ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="yii-debug-toolbar__block_last">

        </div>
        <a class="yii-debug-toolbar__external" href="#" target="_blank">
            <span class="yii-debug-toolbar__external-icon"></span>
        </a>

        <span class="yii-debug-toolbar__toggle">
            <span class="yii-debug-toolbar__toggle-icon"></span>
        </span>
    </div>

    <div class="yii-debug-toolbar__view">
        <iframe src="about:blank" frameborder="0" title="Yii2 debug bar"></iframe>
    </div>
</div>
