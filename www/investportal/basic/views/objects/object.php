<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Selection of the object";
?>
<main class="main" style="background-color:   #eff3f4;">
	 <section class="section" id="link-switcher">
            <a href="<?php echo Url::to(['site/index']); ?>">Main</a> <span id="delimeter"> / </span> <a href="#">Investment Objects</a> <span id="delimeter"> / </span> <a href="<?php echo Url::to(['objects/object']); ?>" class="active">Selection of the object</a>
	 </section>
	<section class="section" id="objects">
			<div class="projects-search-form"></div>
			<div class="objects-list"></div>
			<section id="services" data-text="Service" class="section">
				<header>
					<hr color="#0079bf" size="4" width="37px" align="left"/>
					<h2>Services</h2>
					<strong>& Collaborations</strong>
					<a href=""></a>
					<?php if(!Yii::$app->user->isGuest) { ?><button class="add-but">Add Object</button><?php } ?>
				 </header>
				 <main>
						<header id="slider-controller-adaptive"><img src="/images/icons/slider-contorls/back.png" alt="Назад"></header>
						<header id="slider-controller"><img src="/images/icons/slider-contorls/back.png" alt="Назад"></header>
						<main id="slider-view-adaptive"></main>
						<main id="slider-view"></main>
						<footer id="slider-controller-adaptive"><img src="/images/icons/slider-contorls/go.png" alt="Вперёд"></footer>
						<footer id="slider-controller"><img src="/images/icons/slider-contorls/go.png" alt="Вперёд"></footer> 
				 </main>
			</section>
	</section>
</main>
        
