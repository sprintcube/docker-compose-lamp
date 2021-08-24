<?php

use yii\helpers\Html;

/* @var $panel yii\debug\panels\RequestPanel */

echo '<h1>Request</h1>';

$items = [
    'nav' => [],
    'content' => []
];

$parametersContent = '';

if (isset($panel->data['general'])) {
    $parametersContent .= $this->render('table', ['caption' => 'General Info', 'values' => $panel->data['general']]);
}

$parametersContent .= $this->render('table', [
    'caption' => 'Routing',
    'values' => [
        'Route' => $panel->data['route'],
        'Action' => $panel->data['action'],
        'Parameters' => $panel->data['actionParams'],
    ],
]);

if (isset($panel->data['GET'])) {
    $parametersContent .= $this->render('table', ['caption' => '$_GET', 'values' => $panel->data['GET']]);
}

if (isset($panel->data['POST'])) {
    $parametersContent .= $this->render('table', ['caption' => '$_POST', 'values' => $panel->data['POST']]);
}

if (isset($panel->data['FILES'])) {
    $parametersContent .= $this->render('table', ['caption' => '$_FILES', 'values' => $panel->data['FILES']]);
}

if (isset($panel->data['COOKIE'])) {
    $parametersContent .= $this->render('table', ['caption' => '$_COOKIE', 'values' => $panel->data['COOKIE']]);
}

$parametersContent .= $this->render('table', ['caption' => 'Request Body', 'values' => $panel->data['requestBody']]);

$items['nav'][] = 'Parameters';
$items['content'][] = $parametersContent;

$items['nav'][] = 'Headers';
$items['content'][] = $this->render('table',
        ['caption' => 'Request Headers', 'values' => $panel->data['requestHeaders']])
    . $this->render('table', ['caption' => 'Response Headers', 'values' => $panel->data['responseHeaders']]);

if (isset($panel->data['SESSION'], $panel->data['flashes'])) {
    $items['nav'][] = 'Session';
    $items['content'][] = $this->render('table', ['caption' => '$_SESSION', 'values' => $panel->data['SESSION']])
        . $this->render('table', ['caption' => 'Flashes', 'values' => $panel->data['flashes']]);
}

if (isset($panel->data['SERVER'])) {
    $items['nav'][] = '$_SERVER';
    $items['content'][] = $this->render('table', ['caption' => '$_SERVER', 'values' => $panel->data['SERVER']]);
}

?>
<ul class="nav nav-tabs">
    <?php
    foreach ($items['nav'] as $k => $item) {
        echo Html::tag(
            'li',
            Html::a($item, '#r-tab-' . $k, [
                'class' => $k === 0 ? 'nav-link active' : 'nav-link',
                'data-toggle' => 'tab',
                'role' => 'tab',
                'aria-controls' => 'r-tab-' . $k,
                'aria-selected' => $k === 0 ? 'true' : 'false'
            ]),
            [
                'class' => 'nav-item'
            ]
        );
    }
    ?>
</ul>
<div class="tab-content">
    <?php
    foreach ($items['content'] as $k => $item) {
       echo Html::tag('div', $item, [
            'class' => $k === 0 ? 'tab-pane fade active show' : 'tab-pane fade',
            'id' => 'r-tab-' . $k
        ]);
    }
    ?>
</div>
