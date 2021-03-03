<?php
/* @var $panel yii\debug\panels\LogPanel */
/* @var $searchModel yii\debug\models\search\Log */
/* @var $dataProvider yii\data\ArrayDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\log\Logger;

?>
    <h1>Log Messages</h1>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'id' => 'log-panel-detailed-grid',
    'options' => ['class' => ['detail-grid-view', 'table-responsive', 'logs-messages-table']],
    'filterModel' => $searchModel,
    'filterUrl' => $panel->getUrl(),
    'rowOptions' => static function ($model) {
        $options = [
            'id' => 'log-' . $model['id']
        ];
        switch ($model['level']) {
            case Logger::LEVEL_ERROR : Html::addCssClass($options, 'table-danger'); break;
            case Logger::LEVEL_WARNING : Html::addCssClass($options, 'table-warning'); break;
            case Logger::LEVEL_INFO : Html::addCssClass($options, 'table-success'); break;
        }
        return $options;
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
    'columns' => [
        [
            'attribute' => 'id',
            'label' => '#',
            'contentOptions' => [
                'class' => 'word-break-keep'
            ]
        ],
        [
            'attribute' => 'time',
            'value' => static function ($data) {
                $timeInSeconds = $data['time'] / 1000;
                $millisecondsDiff = (int)(($timeInSeconds - (int)$timeInSeconds) * 1000);

                return date('H:i:s.', $timeInSeconds) . sprintf('%03d', $millisecondsDiff);
            },
            'headerOptions' => [
                'class' => 'sort-numerical'
            ],
            'contentOptions' => [
                'class' => 'word-break-keep'
            ]
        ],
        [
            'attribute' => 'time_since_previous',
            'value' => static function ($data) {
                $diffInMs = $data['time'] - $data['time_of_previous'];
                $diffInSeconds = $diffInMs / 1000;
                $diffInMinutes = $diffInSeconds / 60;
                $diffInHours = $diffInMinutes / 60;

                $diffMs = $diffInMs % 1000;
                $diffSeconds = $diffInSeconds % 60;
                $diffMinutes = $diffInMinutes % 60;
                $diffHours = (int)$diffInHours;

                $formattedDiff = [];
                if ($diffHours > 0) {
                    $formattedDiff[] = $diffHours . 'h';
                }
                if ($diffMinutes > 0) {
                    $formattedDiff[] = $diffMinutes . 'm';
                }
                if ($diffSeconds > 0) {
                    $formattedDiff[] = $diffSeconds . 's';
                }
                $formattedDiff[] = $diffMs . 'ms';
                $formattedDiff = implode('&nbsp;', $formattedDiff);

                if ($data['id_of_previous'] === null) {
                    $previous = Html::tag('span', '< ', ['class' => 'button']);
                } else {
                    $previous = Html::a('< ', '#log-' . $data['id_of_previous'], ['class' => 'button']);
                }

                if ($data['id_of_next'] === null) {
                    $next = Html::tag('span', ' >', ['class' => 'button']);
                } else {
                    $next = Html::a(' >', '#log-' . $data['id_of_next'], ['class' => 'button']);
                }

                return
                    '<div class="since-previous">' .
                    $previous .
                    $formattedDiff .
                    $next .
                    '</div>';
            },
            'format' => 'raw',
            'headerOptions' => [
                'class' => 'sort-numerical'
            ]
        ],
        [
            'attribute' => 'level',
            'value' => static function ($data) {
                return Logger::getLevelName($data['level']);
            },
            'filter' => [
                Logger::LEVEL_TRACE => ' Trace ',
                Logger::LEVEL_INFO => ' Info ',
                Logger::LEVEL_WARNING => ' Warning ',
                Logger::LEVEL_ERROR => ' Error ',
            ],
        ],
        'category',
        [
            'attribute' => 'message',
            'value' => static function ($data) use ($panel) {
                $message = Html::encode(is_string($data['message']) ? $data['message'] : VarDumper::export($data['message']));
                if (!empty($data['trace'])) {
                    $message .= Html::ul($data['trace'], [
                        'class' => 'trace',
                        'item' => static function ($trace) use ($panel) {
                            return '<li>' . $panel->getTraceLine($trace) . '</li>';
                        }
                    ]);
                }
                return $message;
            },
            'format' => 'raw',
            'options' => [
                'width' => '50%',
            ],
        ],
    ],
]);
