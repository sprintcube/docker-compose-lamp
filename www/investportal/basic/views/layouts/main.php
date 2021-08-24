<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $content string */

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover" />
		<title><?= Html::encode($this->title) ?></title>
		<?= Html::csrfMetaTags() ?>
		<script src="https://kit.fontawesome.com/97c3285af2.js" crossorigin="anonymous"></script>
		<?php $this->head() ?>
	</head>
	<body>
		<?php $this->beginBody() ?>
		<header class="header">
				<div id="header_top">
					<div class="ht_header">
						<img src="images/icons/investportal.png" alt="InvestPortal" />
						<span>International Platform <br />for Investors and<br /> Investment Projects</span>
					</div>
				</div>
				<div id="header_bottom">
					<div class="hb_bottom">
						<header>
							<div class="welcome-link">
								<a href="">How the start</a>
							</div>
							<div class="header-informer">
								<header>
									   <div class="exchange-selector">
										 <select name="regexchange" id="regexchange" style="color: #0079bf;">
										   <option value="usd">USD</option>
										   <option value="eur">EUR</option>
										</select>
									   </div>
								</header>
								<main>
									<a href="" target="_blank" style="font-size: 142%;"><i class="fab fa-facebook-f" style="color: gray;"></i></a>
									<a href="" target="_blank" style="font-size: 142%;"><i class="fab fa-youtube" style="color: gray;"></i></a>
									<a href="" target="_blank" style="font-size: 142%;"><i class="fab fa-instagram" style="color: gray;"></i></a>
									<a href="" target="_blank" style="font-size: 142%;"><i class="fab fa-telegram" style="color: gray;"></i></a>
								</main>
								<footer>
									<div class="user-services">
										<div>
											<a href="">Join/Login</a>
										</div>
										<div>
											<a href=""><i class="fas fa-user-check" style="color: #0079bf;"></i></a>
										</div>
									</div>
									<div class="menu-show">
										<a href=""><i class="fas fa-bars" style="color: #0079bf;"></i></a>
									</div>
								</footer>
							</div>
						</header>
						<footer>
							<nav>
								<a href="" id="active">Investment objects</a>
								<a href="">Investors</a>
								<a href="">Services</a>
								<a href="">Experts</a>
								<a href="">Analytics</a>
								<a href="">About</a>
								<a href="">News</a>
								<a href="">Events</a>
								<a href="" id="menu-image"><i class="fas fa-ellipsis-h" style="color: #0079bf;"></i></a>
								<a href="" id="menu-image"><i class="fas fa-search" style="color: #0079bf;"></i></a>
							</nav>     
						</footer>
					</div>
				</div>
				<div id="header_bottom_adaptive">
					<header>
						<ul class="adaptive-buttons">
							<li><i class="fas fa-bars" style="color: #0079bf;"></i></li>
							<li><i class="fas fa-search" style="color: #0079bf;"></i></li>
							<li><i class="fas fa-user-check" style="color: #0079bf;"></i></li>
							<li><i class="fas fa-ellipsis-h" style="color: #0079bf;"></i></li>
						</ul>
					</header>
					<footer>
						<section id="adaptive-window" class="menu" style="display: none;">
							<header>
								<div class="welcome-link">
									<a href="">How the start</a>
								</div>
								<div class="exchange-selector">
									<select name="regexchange" id="regexchange" style="color: #0079bf;">
									  <option value="usd">USD</option>
									  <option value="eur">EUR</option>
								   </select>
								</div>
							</header>
							<main>
								<nav>
									<div class="active"><a href="">Investment objects</a></div>
									<div><a href="">Investors</a></div>
									<div><a href="">Services</a></div>
									<div><a href="">Experts</a></div>
									<div><a href="">Analytics</a></div>
									<div><a href="">About</a></div>
									<div><a href="">News</a></div>
									<div><a href="">Events</a></div>    
								</nav>
							</main>
							<footer>
								<center>
									<a href="" target="_blank" style="font-size: 142%;"><i class="fab fa-facebook-f" style="color: gray;"></i></a>
									<a href="" target="_blank" style="font-size: 142%;"><i class="fab fa-youtube" style="color: gray;"></i></a>
									<a href="" target="_blank" style="font-size: 142%;"><i class="fab fa-instagram" style="color: gray;"></i></a>
									<a href="" target="_blank" style="font-size: 142%;"><i class="fab fa-telegram" style="color: gray;"></i></a>
								</center>
							</footer>
						</section>
					</footer>
				</div>
		</header>
		<?php echo $content; ?>
		<footer class="footer">
			<header>
				<div class="logo">
					<img src="images/icons/investportal-light.png" alt="InvestPortal" />
				</div>
				<span>International Platform <br />for Investors and<br /> Investment Projects</span>
				<main>
					<a href="" target="_blank"><img src="images/icons/socials/1-foot.png" alt="SN" /></a>
					<a href="" target="_blank"><img src="images/icons/socials/2-foot.png" alt="SN" /></a>
					<a href="" target="_blank"><img src="images/icons/socials/3-foot.png" alt="SN" /></a>
					<a href="" target="_blank"><img src="images/icons/socials/4-foot.png" alt="SN" /></a>
				</main>
			</header>
			<main>
				<div id="category_menu">
					<a href="#" class="catname">Low</a>
					<ul>
						<li><a href="">Commercial Law</a></li>
						<li><a href="">Civil and Family Law</a></li>
						<li><a href="">Administrative Law</a></li>
						<li><a href="">Employment Law</a></li>
						<li><a href="">Criminal Law</a></li>
						<li><a href="">Immigration Law</a></li>
						<li><a href="">Accounting Services</a></li>
					</ul>
				</div>
				<div id="category_menu">
					<a href="#" class="catname">Search for Investors</a>
					<ul>
						<li><a href="">Commercial Law</a></li>
						<li><a href="">Civil and Family Law</a></li>
						<li><a href="">Administrative Law</a></li>
						<li><a href="">Employment Law</a></li>
						<li><a href="">Criminal Law</a></li>
						<li><a href="">Immigration Law</a></li>
						<li><a href="">Accounting Services</a></li>
					</ul>
				</div>
				<div id="category_menu">
					<a href="#" class="catname">Analytics</a>
					<ul>
						<li><a href="">Commercial Law</a></li>
						<li><a href="">Civil and Family Law</a></li>
						<li><a href="">Administrative Law</a></li>
						<li><a href="">Employment Law</a></li>
						<li><a href="">Criminal Law</a></li>
						<li><a href="">Immigration Law</a></li>
						<li><a href="">Accounting Services</a></li>
					</ul>
				</div>
				<div id="category_menu">
					<a href="#" class="catname">Evaluation and Audit</a>
					<ul>
						<li><a href="">Commercial Law</a></li>
						<li><a href="">Civil and Family Law</a></li>
						<li><a href="">Administrative Law</a></li>
						<li><a href="">Employment Law</a></li>
						<li><a href="">Criminal Law</a></li>
						<li><a href="">Immigration Law</a></li>
						<li><a href="">Accounting Services</a></li>
					</ul>
				</div>
			</main>
    </footer>

		<?php >
		<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
	

