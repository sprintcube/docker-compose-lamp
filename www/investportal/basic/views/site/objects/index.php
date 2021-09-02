<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Investment Objects and Projects";
?>
<main class="main" style="background-color:   #eff3f4;">
	 <section class="section" id="link-switcher">
            <a href="<?php echo Url::to(['site/index']); ?>">Main</a> <span id="delimeter"> / </span> <a href="#">Investment Objects</a> <span id="delimeter"> / </span> <a href="<?php echo Url::to(['objects/index']); ?>" class="active">Investment Objects and Projects</a>
     </section>
     <section class="section" id="objects">
        <div class="projects-search-form"></div>
        <div class="objects-list"></div>
     </section>
        
</main>
