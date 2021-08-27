<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'News';
?>

<main class="main" style="background-color: #eff3f4;">
	 <section class="section" id="link-switcher">
            <a href="<?php echo Url::to(['site/index']); ?>">Main</a> <span id="delimeter"> / </span> <a href="<?php echo Url::to(['news/index']); ?>" class="active">News</a>
     </section>
     <section class="section" id="news">
            <div class="news-feed">
                <header>
                    <h2 class="title">News</h2>
                    <div id="top-feed">
                        <div class="left-content"></div>
                    
						<div class="right-content">
                            <div class="last-feed-cont">
                                <div id="cont-content"></div>
                            </div>
						</div>
					</div>
               </header>
               <main>
                    <div id="light-feed"></div>
               </main>
               <footer>
                    <div id="down-feed"></div>
               </footer>
           </div>
    </section>      
</main>
