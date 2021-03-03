<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\debug\widgets\NavigationButton;

/* @var $this \yii\web\View */
/* @var $summary array */
/* @var $tag string */
/* @var $manifest array */
/* @var $panels \yii\debug\Panel[] */
/* @var $activePanel \yii\debug\Panel */

$this->title = 'Yii Debugger';
?>
<div class="yii-debug-main-container default-view">
    <div id="yii-debug-toolbar" class="yii-debug-toolbar yii-debug-toolbar_position_top" style="display: none;">
        <div class="yii-debug-toolbar__bar">
            <div class="yii-debug-toolbar__block yii-debug-toolbar__title">
                <a href="<?= Url::to(['index']) ?>">
                    <img width="29" height="30" alt="" src="<?= \yii\debug\Module::getYiiLogo() ?>">
                </a>
            </div>

            <?php foreach ($panels as $panel): ?>
                <?= $panel->getSummary() ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container-fluid main-container">
        <div class="row">
            <div class="col-md-2">
                <div class="list-group">
                    <?php
                    $classes = ['list-group-item', 'd-flex', 'justify-content-between', 'align-items-center'];
                    foreach ($panels as $id => $panel) {
                        $label = Html::tag('span', Html::encode($panel->getName())) . '<span class="icon"></span>';
                        echo Html::a($label, ['view', 'tag' => $tag, 'panel' => $id], [
                            'class' => $panel === $activePanel ? array_merge($classes, ['active']) : $classes,
                        ]);
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-10">
                <?php
                $statusCode = $summary['statusCode'];
                if ($statusCode === null) {
                    $statusCode = 200;
                }
                if ($statusCode >= 200 && $statusCode < 300) {
                    $calloutClass = 'callout-success';
                } elseif ($statusCode >= 300 && $statusCode < 400) {
                    $calloutClass = 'callout-info';
                } else {
                    $calloutClass = 'callout-danger';
                }
                ?>
                <div class="callout <?= $calloutClass ?>">
                    <?php
                    $count = 0;
                    $items = [];
                    foreach ($manifest as $meta) {
                        $label = ($meta['tag'] == $tag ? Html::tag('strong',
                                '&#9658;&nbsp;' . $meta['tag']) : $meta['tag'])
                            . ': ' . Html::encode($meta['method']) . ' ' . Html::encode($meta['url']) . ($meta['ajax'] ? ' (AJAX)' : '')
                            . ', ' . date('Y-m-d h:i:s a', $meta['time'])
                            . ', ' . $meta['ip'];
                        $url = ['view', 'tag' => $meta['tag'], 'panel' => $activePanel->id];
                        $items[] = [
                            'label' => $label,
                            'url' => $url,
                        ];
                        if (++$count >= 10) {
                            break;
                        }
                    }

                    ?>
                    <div class="btn-group btn-group-sm" role="group">
                        <?= NavigationButton::widget(
                            ['manifest' => $manifest, 'tag' => $tag, 'panel' => $activePanel, 'button' => 'Prev']
                        ) ?>
                        <?= NavigationButton::widget(
                            ['manifest' => $manifest, 'tag' => $tag, 'panel' => $activePanel, 'button' => 'Next']
                        ) ?>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <?=Html::a('All', ['index'], ['class' => ['btn', 'btn-light']]);?>
                        <?=Html::a('Latest', ['view', 'panel' => $activePanel->id], ['class' => ['btn', 'btn-light']]);?>
                        <div class="btn-group btn-group-sm" role="group">
                            <?=Html::button('Last 10', [
                                'type' => 'button',
                                'class' => ['btn', 'btn-light', 'dropdown-toggle'],
                                'data' => [
                                    'toggle' => 'dropdown'
                                ],
                                'aria-haspopup' => 'true',
                                'aria-expanded' => 'false'
                            ]);?>
                            <?=\yii\widgets\Menu::widget([
                                'encodeLabels' => false,
                                'items' => $items,
                                'options' => ['class' => 'dropdown-menu'],
                                'itemOptions' => ['class' => 'dropdown-item']
                            ]);?>
                        </div>
                    </div>
                    <?php
                    echo "\n" . $summary['tag'] . ': ' . Html::encode($summary['method']) . ' ' . Html::a(Html::encode($summary['url']),
                            $summary['url']);
                    echo ' at ' . date('Y-m-d h:i:s a', $summary['time']) . ' by ' . $summary['ip'];
                    ?>
                </div>
                <?= $activePanel->getDetail(); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    if (window.top == window) {
        document.querySelector('#yii-debug-toolbar').style.display = 'block';
    }
</script>
