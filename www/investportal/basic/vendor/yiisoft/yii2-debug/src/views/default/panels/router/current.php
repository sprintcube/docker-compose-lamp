<?php

use yii\helpers\Html;

/* @var $currentRoute yii\debug\models\router\CurrentRoute */
?>
<h3>
    <?= Yii::$app->i18n->format(
        '{rulesTested, plural, =0{} =1{Tested # rule} other{Tested # rules}}{hasMatch, plural, =0{} other{ before match}}.',
        [
            'rulesTested' => $currentRoute->count,
            'hasMatch' => (int)$currentRoute->hasMatch,
        ],
        'en_US'
    ); ?>
</h3>

<?php if ($currentRoute->message !== null): ?>
    <div class="alert alert-info">
        <?= Html::encode($currentRoute->message) ?>
    </div>
<?php endif; ?>
<?php if (count($currentRoute->logs)): ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Rule</th>
                <th>Parent</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($currentRoute->logs as $i => $log): ?>
                <tr<?= $log['match'] ? ' class="table-success"' : '' ?>>
                    <td><?= $i + 1; ?></td>
                    <td><?= Html::encode($log['rule']) ?></td>
                    <td><?= Html::encode($log['parent']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif;
