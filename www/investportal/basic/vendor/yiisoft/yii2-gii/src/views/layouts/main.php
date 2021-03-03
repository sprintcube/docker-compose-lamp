<?php

use yii\widgets\Menu;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$asset = yii\gii\GiiAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="none">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <div class="page-container">
        <?php $this->beginBody() ?>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <div class="container">
                <?php echo Html::a(Html::img($asset->baseUrl . '/logo.png'), ['default/index'], [
                    'class' => ['navbar-brand']
                ]); ?>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#gii-nav"
                        aria-controls="gii-nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="gii-nav">
                    <?php
                    echo Menu::widget([
                        'options' => ['class' => ['navbar-nav', 'ml-auto']],
                        'activateItems' => true,
                        'itemOptions' => [
                            'class' => ['nav-item']
                        ],
                        'linkTemplate' => '<a class="nav-link" href="{url}">{label}</a>',
                        'items' => [
                            ['label' => 'Home', 'url' => ['default/index']],
                            ['label' => 'Help', 'url' => 'http://www.yiiframework.com/doc-2.0/ext-gii-index.html'],
                            ['label' => 'Application', 'url' => Yii::$app->homeUrl],
                        ]
                    ]);
                    ?>
                </div>
            </div>
        </nav>
        <div class="container content-container">
            <?= $content ?>
        </div>
        <div class="footer-fix"></div>
    </div>
    <footer class="footer border-top">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <p>A Product of <a href="http://www.yiisoft.com/">Yii Software LLC</a></p>
                </div>
                <div class="col-6">
                    <p class="text-right"><?= Yii::powered() ?></p>
                </div>
            </div>
        </div>
    </footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
