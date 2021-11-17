<?php

use yii\debug\TimelineAsset;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $panel yii\debug\panels\TimelinePanel */
/* @var $searchModel \yii\debug\models\timeline\Search */
/* @var $dataProvider \yii\debug\models\timeline\DataProvider */

TimelineAsset::register($this);
?>
<h1 class="debug-timeline-panel__title">Timeline - <?= number_format($panel->getDuration()); ?> ms</h1>

<?php $form = ActiveForm::begin([
    'method' => 'get',
    'action' => $panel->getUrl(),
    'id' => 'debug-timeline-search',
    'enableClientScript' => false,
    'options' => [
        'class' => 'debug-timeline-panel__search form-inline',
    ],
]) ?>

<?=$form->field($searchModel, 'duration', [
    'options' => ['class' => ['form-group', 'duration']],
    'template' => '{label}<div class="input-group">{input}<div class="input-group-append">
    <span class="input-group-text">ms</span>
  </div></div>'
])->input('number', [
    'min' => 0,
    'size' => '3',
    'class' => 'form-control form-control-sm'
])->hint('ms', ['tag' => 'span']);?>
<?=$form->field($searchModel, 'category', [
    'options' => ['class' => ['form-group', 'duration']]
])->textInput(['class' => 'form-control form-control-sm']);?>

<div class="form-group">
    <?=Html::submitButton('Filter', ['class' => ['btn', 'btn-success']])?>
</div>

<?php ActiveForm::end(); ?>
<div class="debug-timeline-panel">
    <div class="debug-timeline-panel__header">
        <?php foreach ($dataProvider->getRulers() as $ms => $left): ?>
            <span class="ruler"
                  style="margin-left: <?= StringHelper::normalizeNumber($left) ?>%"><b><?= sprintf('%.1f ms',
                        $ms) ?></b></span>
        <?php endforeach; ?>
        <div class="control">
            <button type="button" class="inline btn-link">
                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" height="16" viewBox="0 0 14 16" width="14">
                    <path
                        d="M7 9l3 3H8v3H6v-3H4l3-3zm3-6H8V0H6v3H4l3 3 3-3zm4 2c0-.55-.45-1-1-1h-2.5l-1 1h3l-2 2h-7l-2-2h3l-1-1H1c-.55 0-1 .45-1 1l2.5 2.5L0 10c0 .55.45 1 1 1h2.5l1-1h-3l2-2h7l2 2h-3l1 1H13c.55 0 1-.45 1-1l-2.5-2.5L14 5z"></path>
                </svg>
            </button>
            <button type="button" class="open btn-link">
                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" height="16" viewBox="0 0 14 16" width="14">
                    <path
                        d="M11.5 7.5L14 10c0 .55-.45 1-1 1H9v-1h3.5l-2-2h-7l-2 2H5v1H1c-.55 0-1-.45-1-1l2.5-2.5L0 5c0-.55.45-1 1-1h4v1H1.5l2 2h7l2-2H9V4h4c.55 0 1 .45 1 1l-2.5 2.5zM6 6h2V3h2L7 0 4 3h2v3zm2 3H6v3H4l3 3 3-3H8V9z"></path>
                </svg>
            </button>
        </div>
    </div>
    <?php if (!Yii::$app->request->isPjax && $panel->svg->hasPoints()): ?>
        <div class="debug-timeline-panel__memory"
             style="height: <?= StringHelper::normalizeNumber($panel->svg->y) ?>px;">
            <div class="scale" style="bottom: 100%;"><?= sprintf('%.2f MB', $panel->memory / 1048576) ?></div>
            <?= $panel->svg; ?>
        </div>
    <?php endif; ?>
    <div class="debug-timeline-panel__items">
        <?php Pjax::begin([
            'formSelector' => '#debug-timeline-search',
            'linkSelector' => false,
            'options' => ['id' => 'debug-timeline-panel__pjax']
        ]); ?>
        <?php if (($models = $dataProvider->models) === []): ?>
            <div class="debug-timeline-panel__item empty">
                <span>No results found.</span>
            </div>
        <?php else: ?>
            <?php foreach ($models as $key => $model): ?>
                <?php
                $memory = null;
                if (!empty($model['memory'])) {
                    $diff = null;
                    if ($model['memoryDiff'] !== 0) {
                        $diff = ' title="' . (($model['memoryDiff'] > 0) ? '+' : '-') . sprintf('%.3f',
                                $model['memoryDiff'] / 1048576) . '""';
                    }
                    $memory = ' / <span class="memory"' . $diff . '>' . sprintf('%.2f',
                            $model['memory'] / 1048576) . ' MB</span>';
                }
                ?>
                <div class="debug-timeline-panel__item">
                    <?php if ($model['child']): ?>
                        <span class="ruler ruler-start"
                              style="height: <?= StringHelper::normalizeNumber($model['child'] * 21) ?>px; margin-left: <?= StringHelper::normalizeNumber($model['css']['left']) ?>%"></span>
                    <?php endif; ?>
                    <?= Html::tag('a', '
                        <span class="category">' . Html::encode($model['category']) . ' <span>' . sprintf('%.1f ms',
                            $model['duration']) . '</span>' . $memory . '</span>', [
                        'tabindex' => $key + 1,
                        'title' => $model['info'],
                        'class' => $dataProvider->getCssClass($model),
                        'style' => 'background-color: ' . $model['css']['color'] . ';margin-left:' . StringHelper::normalizeNumber($model['css']['left'] . '%;width:' . $model['css']['width']) . '%',
                        'data-memory' => $dataProvider->getMemory($model)
                    ]); ?>
                    <?php if ($model['child']): ?>
                        <span class="ruler ruler-end"
                              style="height: <?= StringHelper::normalizeNumber($model['child'] * 21) ?>px; margin-left: <?= StringHelper::normalizeNumber($model['css']['left'] + $model['css']['width']) . '%'; ?>"></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php Pjax::end(); ?>
    </div>
</div>
