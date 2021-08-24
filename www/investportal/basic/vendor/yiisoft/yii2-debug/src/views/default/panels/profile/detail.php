<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $panel yii\debug\panels\ProfilingPanel */
/* @var $searchModel yii\debug\models\search\Profile */
/* @var $dataProvider yii\data\ArrayDataProvider */
/* @var $time int */
/* @var $memory int */
?>
    <h1>Performance Profiling</h1>
    <p>
        Total processing time: <b><?= $time ?></b>; Peak memory: <b><?= $memory ?></b>.
        <?= Html::a('Show Profiling Timeline', [
            '/' . $panel->module->id . '/default/view',
            'panel' => 'timeline',
            'tag' => $panel->tag,
        ]) ?>
    </p>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'id' => 'profile-panel-detailed-grid',
    'options' => ['class' => 'detail-grid-view table-responsive'],
    'filterModel' => $searchModel,
    'filterUrl' => $panel->getUrl(),
    'pager' => [
        'linkContainerOptions' => [
            'class' => 'page-item'
        ],
        'linkOptions' => [
            'class' => 'page-link'
        ],
        'disabledListItemSubTagOptions' => [
            'tag' => 'a',
            'href' => 'javascript:;',
            'tabindex' => '-1',
            'class' => 'page-link'
        ]
    ],
    'columns' => [
        [
            'attribute' => 'seq',
            'label' => 'Time',
            'value' => function ($data) {
                $timeInSeconds = $data['timestamp'] / 1000;
                $millisecondsDiff = (int)(($timeInSeconds - (int)$timeInSeconds) * 1000);

                return date('H:i:s.', $timeInSeconds) . sprintf('%03d', $millisecondsDiff);
            },
            'headerOptions' => [
                'class' => 'sort-numerical'
            ]
        ],
        [
            'attribute' => 'duration',
            'value' => function ($data) {
                return sprintf('%.1f ms', $data['duration']);
            },
            'options' => [
                'width' => '10%',
            ],
            'headerOptions' => [
                'class' => 'sort-numerical'
            ]
        ],
        'category',
        [
            'attribute' => 'info',
            'value' => function ($data) {
                return str_repeat('<span class="indent">→</span>', $data['level']) . Html::encode($data['info']);
            },
            'format' => 'html',
            'options' => [
                'width' => '60%',
            ],
        ],
    ],
]);
