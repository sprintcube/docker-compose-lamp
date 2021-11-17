<?php

use yii\helpers\Html;

/* @var $currentRoute yii\debug\models\router\CurrentRoute */
/* @var $routerRules yii\debug\models\router\RouterRules */
/* @var $actionRoutes yii\debug\models\router\ActionRoutes */

$items = [
    'nav' => [],
    'content' => []
];

$items['nav'][] = 'Current Route';
$items['content'][] = $this->render('current', ['currentRoute' => $currentRoute]);

$items['nav'][] = 'Router Rules';
$items['content'][] = $this->render('rules', ['routerRules' => $routerRules]);

$items['nav'][] = 'Action Routes';
$items['content'][] = $this->render('actions', ['actionRoutes' => $actionRoutes]);

?>
<h1>Router</h1>

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
    <li class="nav-item">
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
            <span class="badge badge-<?= $routerRules->prettyUrl ? 'success' : 'light' ?>">
                Pretty URL <?= $routerRules->prettyUrl ? 'Enabled' : 'Disabled' ?>
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
            <span class="badge badge-<?= $routerRules->strictParsing ? 'success' : 'light' ?>">
                Strict Parsing <?= $routerRules->strictParsing ? 'Enabled' : 'Disabled' ?>
            </span>
        </a>
    </li>
    <?php if ($routerRules->suffix): ?>
        <li class="nav-item">
            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
            <span class="badge badge-warning">
                Global Suffix: <?= $routerRules->suffix ?>
            </span>
            </a>
        </li>
    <?php endif; ?>
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
