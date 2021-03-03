<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/* @var $panel yii\debug\panels\MailPanel */
/* @var $searchModel yii\debug\models\search\Mail */
/* @var $dataProvider yii\data\ArrayDataProvider */

$listView = new ListView([
    'dataProvider' => $dataProvider,
    'itemView' => '_item',
    'layout' => "{summary}\n{items}\n{pager}\n",
]);
$listView->sorter = ['options' => ['class' => 'mail-sorter']];
?>

<h1>Email messages</h1>

<div class="row mb-2">
    <div class="col-3 col-lg-2">
        <?= Html::button('Form filtering', [
            'class' => ['btn', 'btn-outline-secondary'],
            'type' => 'button',
            'data' => [
                'toggle' => 'collapse',
                'target' => '#email-form'
            ],
            'aria-expanded' => 'false',
            'aria-controls' => 'email-form'
        ]) ?>
    </div>
    <div class="col-9 col-lg-10">
        <?= $listView->renderSorter() ?>
    </div>
</div>

<div id="email-form" class="collapse">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['default/view', 'tag' => Yii::$app->request->get('tag'), 'panel' => 'mail'],
    ]); ?>
    <div class="form-row">
        <?= $form->field($searchModel, 'from', ['options' => ['class' => ['form-group', 'col-lg-6']]])->textInput() ?>

        <?= $form->field($searchModel, 'to', ['options' => ['class' => ['form-group', 'col-lg-6']]])->textInput() ?>

        <?= $form->field($searchModel, 'reply', ['options' => ['class' => ['form-group', 'col-lg-6']]])->textInput() ?>

        <?= $form->field($searchModel, 'cc', ['options' => ['class' => ['form-group', 'col-lg-6']]])->textInput() ?>

        <?= $form->field($searchModel, 'bcc', ['options' => ['class' => ['form-group', 'col-lg-6']]])->textInput() ?>

        <?= $form->field($searchModel, 'charset', ['options' => ['class' => ['form-group', 'col-lg-6']]])->textInput() ?>

        <?= $form->field($searchModel, 'subject', ['options' => ['class' => ['form-group', 'col-lg-6']]])->textInput() ?>

        <?= $form->field($searchModel, 'body', ['options' => ['class' => ['form-group', 'col-lg-6']]])->textInput() ?>

        <div class="form-group col-12">
            <?= Html::submitButton('Filter', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?= $listView->run() ?>
