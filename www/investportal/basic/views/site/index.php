<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Welcome to Investportal!';
?>
<main class="main">
	<section id="promo" class="section">
		<header>
			<div class="promo-header">
			   <hr style="position: relative; top: -12%;" width="37px" color="#0079bf" size="4" align="left">
			   <h2 style="color: #ffffff;font-size: 160%;">Investments</h2>
			   <strong style="color: #ffffff;font-size: calc(161%/2);margin-top: -35px;">objects and projects</strong>
			   <?php if(!Yii::$app->user->isGuest) { ?><button class="add-but">Add Object</button><?php } ?>
			   <div class="links"></div>
			</div>
			<div class="marketing-info">
						<header class="banner-block">
							<img src="/images/advbanner.jpg" alt="Реклама">
						</header>
						<footer>
							<ul></ul>
						</footer>
			 </div>
		</header>
		<main class="swiper-container">
                <div class="promo-feed swiper-wrapper">
				</div>

				<div class="swiper-pagination"></div>

                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

                <div class="swiper-scrollbar"></div>
		</main>
		
	</section>
	<section id="news" class="section" data-text="News">
		<header>
                <hr color="#0079bf" size="4" width="37px" align="left"/>
                <h2>News</h2>
                <a href="<?php echo Url::to(['news/index']); ?>">All news</a>
        </header>
        <main>
                <header id="slider-controller-adaptive"><img src="/images/icons/slider-contorls/back.png" alt="Назад"></header>
                <header id="slider-controller"><img src="/images/icons/slider-contorls/back.png" alt="Назад"></header>
                <main id="slider-view-adaptive"></main>
                <main id="slider-view"></main>
                <footer id="slider-controller-adaptive"><img src="/images/icons/slider-contorls/go.png" alt="Вперёд"></footer>
                <footer id="slider-controller"><img src="/images/icons/slider-contorls/go.png" alt="Вперёд"></footer>
	</section>
	<section id="investsearch" class="section">
			<header>
                <hr color="#0079bf" size="4" width="37px" align="left"/>
                <h2>Investors in Search...</h2>
                <?php if(!Yii::$app->user->isGuest) { ?><button class="add-but" style="border: solid 2px gray;">Add Request</button><?php } ?>
                <a href=""></a>
            </header>
            <main class="swiper-container">
                <div class="popular-objects swiper-wrapper">
				</div>

				<div class="swiper-pagination"></div>

                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

                <div class="swiper-scrollbar"></div>
			</main>
			
	</section>
	<section id="eventcalendar" data-text="Events" class="section">
			<header>
                <hr color="#0079bf" size="4" width="37px" align="left"/>
                <h2>Upcoming Events</h2>
			</header>
            <main>
			</main>
	</section>
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
	<section id="analytics" data-text="Analytic" class="section">
		 <header>
                <hr color="#0079bf" size="4" width="37px" align="left"/>
                <h2>Analytics</h2>
                <a href="">All material</a>
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
	<section id="estate" data-text="Investment" class="section">
		 <header>
                <hr color="#0079bf" size="4" width="37px" align="left"/>
                <h2>Real Estate</h2>
                <strong>as Investment</strong>
                <a href="">Entire Offer</a>
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
	<section id="reviews" data-text="Review" class="section">
		 <header>
                <hr color="#0079bf" size="4" width="37px" align="left"/>
                <h2>Reviews</h2>
                <strong>and countries</strong>
                <a href="">All material</a>
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
	
</main>
