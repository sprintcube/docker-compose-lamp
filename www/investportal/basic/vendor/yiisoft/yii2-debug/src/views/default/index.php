<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $manifest array */
/* @var $searchModel \yii\debug\models\search\Debug */
/* @var $dataProvider ArrayDataProvider */
/* @var $panels \yii\debug\Panel[] */

$this->title = 'Yii Debugger';
?>
<div class="yii-debug-main-container default-index">
    <div id="yii-debug-toolbar" class="yii-debug-toolbar yii-debug-toolbar_position_top" style="display: none;">
        <div class="yii-debug-toolbar__bar">
            <div class="yii-debug-toolbar__block yii-debug-toolbar__title">
                <a href="#">
                    <img width="30" height="30" alt="" src="<?= \yii\debug\Module::getYiiLogo() ?>">
                </a>
            </div>
            <?php foreach ($panels as $panel): ?>
                <?= $panel->getSummary() ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container-fluid">
        <div class="table-responsive">
            <h1>Available Debug Data</h1>
            <?php

            $codes = [];
            foreach ($manifest as $tag => $vals) {
                if (!empty($vals['statusCode'])) {
                    $codes[] = $vals['statusCode'];
                }
            }
            $codes = array_unique($codes, SORT_NUMERIC);
            $statusCodes = !empty($codes) ? array_combine($codes, $codes) : null;

            $hasDbPanel = isset($panels['db']);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'rowOptions' => function ($model) use ($searchModel, $hasDbPanel) {
                    if ($searchModel->isCodeCritical($model['statusCode'])) {
                        return ['class' => 'table-danger'];
                    }

                    if ($hasDbPanel && $this->context->module->panels['db']->isQueryCountCritical($model['sqlCount'])) {
                        return ['class' => 'table-danger'];
                    }

                    return [];
                },
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
                'columns' => array_filter([
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'tag',
                        'value' => function ($data) {
                            return Html::a($data['tag'], ['view', 'tag' => $data['tag']]);
                        },
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'time',
                        'value' => function ($data) {
                            return '<span class="nowrap">' . Yii::$app->formatter->asDatetime($data['time'],
                                    'yyyy-MM-dd HH:mm:ss') . '</span>';
                        },
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'processingTime',
                        'value' => function ($data) {
                            return isset($data['processingTime']) ? number_format($data['processingTime'] * 1000) .
                                ' ms' : '<span class="not-set">(not set)</span>';
                        },
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'peakMemory',
                        'value' => function ($data) {
                            return isset($data['peakMemory']) ? sprintf('%.3f MB', $data['peakMemory'] /
                                1048576) : '<span class="not-set">(not set)</span>';
                        },
                        'format' => 'html',
                    ],
                    'ip',
                    $hasDbPanel ? [
                        'attribute' => 'sqlCount',
                        'label' => 'Query Count',
                        'value' => function ($data) {
                            /* @var $dbPanel \yii\debug\panels\DbPanel */
                            $dbPanel = $this->context->module->panels['db'];

                            if ($dbPanel->isQueryCountCritical($data['sqlCount'])) {
                                $content = Html::tag('b', $data['sqlCount']) . ' ' . Html::tag('span', '&#x26a0;');

                                return Html::a($content, ['view', 'panel' => 'db', 'tag' => $data['tag']], [
                                    'title' => 'Too many queries. Allowed count is ' . $dbPanel->criticalQueryThreshold,
                                ]);

                            }
                            return $data['sqlCount'];
                        },
                        'format' => 'html',
                    ] : null,
                    [
                        'attribute' => 'mailCount',
                        'visible' => isset($this->context->module->panels['mail']),
                    ],
                    [
                        'attribute' => 'method',
                        'filter' => [
                            'get' => 'GET',
                            'post' => 'POST',
                            'delete' => 'DELETE',
                            'put' => 'PUT',
                            'head' => 'HEAD'
                        ]
                    ],
                    [
                        'attribute' => 'ajax',
                        'value' => function ($data) {
                            return $data['ajax'] ? 'Yes' : 'No';
                        },
                        'filter' => ['No', 'Yes'],
                    ],
                    [
                        'attribute' => 'url',
                        'label' => 'URL',
                    ],
                    [
                        'attribute' => 'statusCode',
                        'value' => function ($data) {
                            $statusCode = $data['statusCode'];
                            if ($statusCode === null) {
                                $statusCode = 200;
                            }
                            if ($statusCode >= 200 && $statusCode < 300) {
                                $class = 'badge-success';
                            } elseif ($statusCode >= 300 && $statusCode < 400) {
                                $class = 'badge-info';
                            } else {
                                $class = 'badge-danger';
                            }
                            return "<span class=\"badge {$class}\">$statusCode</span>";
                        },
                        'format' => 'raw',
                        'filter' => $statusCodes,
                        'label' => 'Status code'
                    ],
                ]),
            ]);
            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    if (window.top == window) {
        document.querySelector('#yii-debug-toolbar').style.display = 'block';
    }
</script>
