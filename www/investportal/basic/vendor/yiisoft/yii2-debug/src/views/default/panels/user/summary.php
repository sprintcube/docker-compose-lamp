<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $panel yii\debug\panels\UserPanel */
?>
<div class="yii-debug-toolbar__block">
    <a href="<?= $panel->getUrl() ?>">
        <?php if (!isset($panel->data['id'])): ?>
            <span class="yii-debug-toolbar__label">Guest</span>
        <?php else: ?>
            <?php if ($panel->getUser()->isGuest || $panel->userSwitch->isMainUser()): ?>
                <?= Html::encode($panel->getName()) ?> <span
                    class="yii-debug-toolbar__label yii-debug-toolbar__label_info"><?= $panel->data['id'] ?></span>
            <?php else: ?>
                <?= Html::encode($panel->getName()) ?> switching <span
                    class="yii-debug-toolbar__label yii-debug-toolbar__label_warning"><?= $panel->data['id'] ?></span>
            <?php endif; ?>
            <?php if ($panel->canSwitchUser()): ?>
                <span class="yii-debug-toolbar__switch-icon yii-debug-toolbar__userswitch"
                      id="yii-debug-toolbar__switch-users">
            </span>
            <?php endif; ?>
        <?php endif; ?>
    </a>
</div>
