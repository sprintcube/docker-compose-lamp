<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Investment requests";
?>

<main class="main" style="background-color:   #eff3f4;">
	<section class="section" id="link-switcher">
            <a href="<?php echo Url::to(['site/index']); ?>">Main</a> <span id="delimeter"> / </span> <a href="<?php echo Url::to(['passport/service']); ?>" class="active">Passport</a>
	</section>
	<section class="section" id="passport"></section>
</main>
