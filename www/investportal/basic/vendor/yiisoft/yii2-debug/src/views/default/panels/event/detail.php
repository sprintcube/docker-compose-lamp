<?php

use yii\grid\GridView;

/* @var $panel yii\debug\panels\EventPanel */
/* @var $searchModel yii\debug\models\search\Event */
/* @var $dataProvider yii\data\ArrayDataProvider */
?>
<h1>Events</h1>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'id' => 'log-panel-detailed-event',
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
            'attribute' => 'time',
            'value' => function ($data) {
                $timeInSeconds = floor($data['time']);
                $millisecondsDiff = (int)(($data['time'] - (int)$timeInSeconds) * 1000);
                return date('H:i:s.', $timeInSeconds) . sprintf('%03d', $millisecondsDiff);
            },
            'headerOptions' => [
                'class' => 'sort-numerical'
            ]
        ],
        [
            'attribute' => 'name',
            /*'headerOptions' => [
                'class' => 'sort-numerical'
            ],*/
        ],
        [
            'attribute' => 'class',
        ],
        [
            'header' => 'Sender',
            'attribute' => 'senderClass',
            'value' => function ($data) {
                return $data['senderClass'];
            },
        ],
        [
            'header' => 'Static',
            'attribute' => 'isStatic',
            'format' => 'boolean',
        ],
    ],
]); ?>
