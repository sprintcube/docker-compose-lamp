<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $curNews->title . " :: News";
?>

<main class="main" style="background-color:   #eff3f4;">
        <section class="section" id="link-switcher">
            <a href="<?php echo Url::to(['site/index']); ?>">Main</a> <span id="delimeter"> / </span> <a href="<?php echo Url::to(['news/index']); ?>">News</a> <span id="delimeter"> / </span> <a href="<?php echo Url::to(['news/view', ['contentId' => $contentId]]); ?>" class="active">Warnings of 'ghost towns' If staff do not return to the office</a>
        </section>
        <section class="section" id="news">
			<?php echo Html::hiddenInput('newsData', $contentId); ?>
            <div class="news-viewer">
                <div id="left-content">
                    <header>
                        <h2><?php echo $curNews->title; ?></h2>
						<section class="public-date">
								<img src="https://img.icons8.com/fluent-systems-regular/24/0079bf/clock--v1.png" alt="Дата публикации" id="clock" />
								<i><?php echo $curNews->publishedDate; ?></i>
						</section>
                    </header>
                    <main>
					    <section class="title-image"><img src="<?php echo $curNews->titleImage; ?>" alt="Title Image"></section>
                        <section class="news-content"><?php echo Html::encode($user->content); ?></section>
                    </main>
                    <footer>
						 <section class="share">
                            <strong>Share this story</strong>
                            <ul>
                                <li data-channel="facebook"><i class="fab fa-facebook-f" style="color: gray;"></i></li>
                                <li data-channel="youtube"><i class="fab fa-youtube" style="color: gray;"></i></li>
                                <li data-channel="instagram"><i class="fab fa-instagram" style="color: gray;"></i></li>
                                <li data-channel="telegram"><i class="fab fa-telegram" style="color: gray;"></i></li>
                            </ul>
                        </section>
                        <section class="realted">
                            <strong>Realted news</strong>
                            <ul></ul>
                        </section>
                    </footer>
                 </div>
                 <div id="right-content">
                    <div class="realted-news"></div>
                 </div>
             </div>
        </section>
</main>
               
