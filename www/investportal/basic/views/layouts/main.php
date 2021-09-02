<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $content string */

$this->beginPage();

$isUserData = [];

if(!Yii::$app->user->isGuest){
	$isUserData = [
		'link' => Url::to(['passport/service']),
		'title' => 'My passport'
	];
}
else{
	$isUserData = [
		'link' => '',
		'title' => 'Join/Login'
	];
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover" />
		<title><?= Html::encode($this->title) ?></title>
		<?= Html::csrfMetaTags() ?>
		<script src="https://kit.fontawesome.com/97c3285af2.js" crossorigin="anonymous"></script>
<?php if($this->id == 'Objects' && $this->action->id == 'Object'){ ?>	
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js" integrity="sha512-0bEtK0USNd96MnO4XhH8jhv3nyRF0eK87pJke6pkYf3cM0uDIhNJy9ltuzqgypoIFXw3JSuiy04tVk4AjpZdZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css" integrity="sha512-okE4owXD0kfXzgVXBzCDIiSSlpXn3tJbNodngsTnIYPJWjuYhtJ+qMoc0+WUwLHeOwns0wm57Ka903FqQKM1sA==" crossorigin="anonymous" />
<?php } ?>
		<?php $this->head() ?>
	</head>
	<?php $this->beginBody() ?>
	<body>
		<header class="header">
				<div id="header_top">
					<div class="ht_header">
						<img src="/images/icons/investportal.png" alt="InvestPortal" />
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
											<a href="<?php echo $userData['link']; ?>"><?php echo $userData['title']; ?></a>
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
					<a href="" target="_blank"><img src="/images/icons/socials/1-foot.png" alt="SN" /></a>
					<a href="" target="_blank"><img src="/images/icons/socials/2-foot.png" alt="SN" /></a>
					<a href="" target="_blank"><img src="/images/icons/socials/3-foot.png" alt="SN" /></a>
					<a href="" target="_blank"><img src="/images/icons/socials/4-foot.png" alt="SN" /></a>
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
		<script src="/js/app.js"></script>
		<?php if(Yii::$app->user->isGuest && $this->id != 'Passport'){ ?>
			    <div id="auth-lightbox" class="lightbox-closed">
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAADT0lEQVRYhe2WQWwUZRTHf++b3cIFT6SJtE0UOHAoF/GsbDdAgqRLg7deDIkJNIqJXWC3G0xJ2qXUGjWRJnDgpCekpZgeStidclc52AQulRgEDTfgAGxn5nn4htKdGXa7qzf7v8xk3vve/zczb+Z7sKH/u6Sl7NKtHvwgh/AB8BbQE0buo9zDMI/HHJOZP/9bgFKlC3W+QPUokGqcrAEiV5HgJOPZP/49QLHaD/I9sAWkBjqLcA2jt3m+Yu90c7obz7yDaA5kALQDeIqRQcb3/tQ+wMjip2jwDYhBuIIxpxl7/17DNYWb2zHOJMoR0ACVz5jIfNc6QLHaD8yCKCInKe/9uqFxDN4dRjkPKhhz+HVPIhmgVOkiMHeALYh83rJ5PcQUyBPE7KL83l/RFJO4MDBnrTlX2jYHKGe+ApkBfQOC0aSUOEDpVg/wEUgNY07H4sXF4+QXOmPX8wudFBePx647/imQGqpHKVW6mgP4QQ5wQGdjDVeoDoFOk+6o1EHkFzpJd1RAp23OGo1llxGdA1L4kmsOAAcBUJmLRbyVH4EloHcVYtWcXmApzKlXQFhLDjYHENlhj94vsdjUgUes1LJ1EGvNV2pZpg48iq1L6c+2JjuaA6DbANjkPIzHQgicPuC30LgXuIvHvkRzgJQ8CM+61wGAJhZpprTT4Kf2LPSRYB0AYr/VF/62xFr5hU7wq8Bu7KtYAnah/o3ErwPA2/ymPdHYU40DqC7bY2pPonn0nUd7IgnCk3dtTZabAxjmAezGElEq/SHRhos2ps2J1rS1ROabA3jMAR7IAIWb2+tiE33TIEOxbl+FkCGbs0YFdycqOcDD+NejdsmNU6xeAvkY4SrlTPyOWtGIO4MyAFzkXOZYNJy8F/jeKPAU5Qgj7nDb5kU3H5o/xmOdewHA5P6HGBm0+znn24Iounlgwk5IOsiXmb+T0hoPJAX3E0S/BTEgMzj+KcaysU6OrNmJYdLeuQaInKCcufC69HWMZO4hkB/slio1u7HoNVR+ZRN2JHtBN8oehMNADkgDjxEdpNwX6/zWAACG3a10yBnQIZoOpXiIXAYzmjSAtAfwUqVKF4HTDxxC9G00HMuF+wT8jsg8xr/OePZB40Ib2tAr/QNOXDy1WmL6DAAAAABJRU5ErkJggg==" class="close">
        <section class="module-page" data-screen="SignIn">
            <header>
              <h2>Sign in Investportal</h2>
            </header>
            <main>
              <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4Igp3aWR0aD0iMzAiIGhlaWdodD0iMzAiCnZpZXdCb3g9IjAgMCAxNzIgMTcyIgpzdHlsZT0iIGZpbGw6IzAwMDAwMDsiPjxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0ibm9uemVybyIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIHN0cm9rZS1saW5lY2FwPSJidXR0IiBzdHJva2UtbGluZWpvaW49Im1pdGVyIiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIHN0cm9rZS1kYXNoYXJyYXk9IiIgc3Ryb2tlLWRhc2hvZmZzZXQ9IjAiIGZvbnQtZmFtaWx5PSJub25lIiBmb250LXdlaWdodD0ibm9uZSIgZm9udC1zaXplPSJub25lIiB0ZXh0LWFuY2hvcj0ibm9uZSIgc3R5bGU9Im1peC1ibGVuZC1tb2RlOiBub3JtYWwiPjxwYXRoIGQ9Ik0wLDE3MnYtMTcyaDE3MnYxNzJ6IiBmaWxsPSJub25lIj48L3BhdGg+PGcgZmlsbD0iIzM0OThkYiI+PHBhdGggZD0iTTEwMy4yLDEwOC45MzMzM3YtMTEuNDY2NjdjMi41OCwtMS4yNzg1MyA5Ljk1ODgsLTEwLjA2MiAxMC43MzI4LC0xNi45MjQ4YzIuMDI5NiwtMC4xNTQ4IDUuMjE3MzMsLTIuMDE4MTMgNi4xNTc2LC05LjM3NGMwLjUwNDUzLC0zLjk1MDI3IC0xLjUwMjEzLC02LjE2OTA3IC0yLjcxNzYsLTYuODY4NTNjMCwwIDMuMDI3MiwtNS43NTA1MyAzLjAyNzIsLTEyLjY5MzZjMCwtMTMuOTIwNTMgLTUuNDYzODcsLTI1LjggLTE3LjIsLTI1LjhjMCwwIC00LjA3NjQsLTguNiAtMTcuMiwtOC42Yy0yNC4zMjA4LDAgLTM0LjQsMTUuNjAwNCAtMzQuNCwzNC40YzAsNi4zMjk2IDMuMDI3MiwxMi42OTM2IDMuMDI3MiwxMi42OTM2Yy0xLjIxNTQ3LDAuNjk5NDcgLTMuMjIyMTMsMi45MjQgLTIuNzE3Niw2Ljg2ODUzYzAuOTQwMjcsNy4zNTU4NyA0LjEyOCw5LjIxOTIgNi4xNTc2LDkuMzc0YzAuNzc0LDYuODYyOCA4LjE1MjgsMTUuNjQ2MjcgMTAuNzMyOCwxNi45MjQ4djExLjQ2NjY3Yy01LjczMzMzLDE3LjIgLTUxLjYsNS43MzMzMyAtNTEuNiw0NS44NjY2N2gxMzcuNmMwLC00MC4xMzMzMyAtNDUuODY2NjcsLTI4LjY2NjY3IC01MS42LC00NS44NjY2N3oiPjwvcGF0aD48L2c+PC9nPjwvc3ZnPg==" data-service="login" />
              <div class="module-form">
                <form action="#" method="post">
                  <div><label for="investportalid">E-mail, login or phone number</label>
                    <input type="text" name="investportalid" /></div>
                  <div><label for="investportalidaccess">Password</label>
                    <input type="password" name="investportalidaccess" /></div>
                </form>
                <button id="form-submit" data-datacontrol="facebook"><i class="fab fa-facebook"></i>Sign in Facebook</button>
                <button id="form-submit">Sign in</button>
              </div>
            </main>
            <footer>
              <center>
                <ul>
                    <li data-screenlocation="SignUp">Join in</li>
                    <li data-screenlocation="Forgot">Forgot password?</li>
                </ul>
              </center>
            </footer>
        </section>
        <section class="module-page" data-screen="SignUp">
            <header>
              <h2>Create Investportal account</h2>
            </header>
            <main>
              <ul id="reg-content">
                <li data-signstep="0">
                  <div id="atettion">
                <span>Don't waste time entering data in the form! In a few seconds, become a new user of the portal, thanks to the data of your account in Facebook.</span>
                <button id="form-submit" data-datacontrol="facebook"><i class="fab fa-facebook"></i>Fast join with Facebook</button>
              </div>
                  <form action="#" method="post">
                  <div><label for="fn">First name</label>
                    <input type="text" name="fn" /></div>
                    <div><label for="sn">Second name</label>
                    <input type="text" name="sn" /></div>
                    <div><label for="username">Login</label>
                    <input type="text" name="username" /></div>
                  </form>
                </li>
                <li data-signstep="1" style="display: none;">
                  <form action="#" method="post">
                  <div><label for="user-email">E-Mail</label>
                    <input type="email" name="user-email" /></div>
                    <div><label for="user-password">Password</label>
                    <input type="password" name="user-password" /></div>
                    <div><label for="confirm-password">Confirm the password</label>
                    <input type="password" name="confirm-password" /></div>
                    <div><label for="phone">Phone number</label>
                    <input type="tel" name="phone" /></div>
                  </form>
                </li>
                <li data-signstep="2" style="display: none;">
                  <form action="#" method="post">
                  <div><label for="region">Your country</label>
                    <select name="region" id="region">
                      <option>Any Country</option>
                    </select></div>
                    
                    <span id="form-note">
                      Choosing a country when registering a portal account, it will be easier for you with the help of our services not only to sell and buy objects, but also to look for an investor suitable for you on your territory and receive personalized regional selections based on your activity on the portal!
                    </span>
                    
                  </form>
                </li>
                <li data-signstep="3" style="display: none;">
                  <form action="#" method="post">
                  <div><label for="confirm-code">Confirm code</label>
                    <input type="number" name="confirm-code" /></div>
                    
                  </form>
                  <span id="form-note">
                      An SMS message will be sent to your phone number with a confirmation code for registering a portal account. Enter the received code in this field so that you can complete the registration process later.
                    </span>
                </li>
              </ul>
              <div id="reg-footer">
                <button id="form-submit">Countine</button>
              </div>
            </main>
            <footer>
                <center>
                    <ul>
                        <li data-screenlocation="SignIn">Sign in</li>
                        <li data-screenlocation="Forgot">Forgot password?</li>
                    </ul>
                </center>
            </footer>
        </section>
        <section class="module-page" data-screen="Forgot">
            <header>
              <h2>Forgot account password</h2>
            </header>
            <main>
              <img src="data:image/png;base64,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" align="center" data-service="forgot" />
              <ul id="reg-content">
                <li data-signstep="0">
                  <form action="#" method="post">
                  <div><label for="investportal-id">E-mail, login or phone number for access restore</label>
                    <input type="text" name="investportal-id" /></div>
                </form>
                </li>
                <li data-signstep="1" style="display: none;">
                  <span id="form-note">Within some time, you will receive an SMS message with the access code provided by us. You need to start the process after entering the code from this message into the form!</span>
                  <form action="#" method="post">
                  <div><label for="restore-code">Restore code</label>
                    <input type="number" name="restore-code" /></div>
                </form>
                </li>
                <li data-signstep="2" style="display: none;">
                  <div id="atettion"><span>You can input new password for Investportal account access restore</span></div>
                  <form action="#" method="post">
                  <div><label for="new-password">Input new password</label>
                    <input type="password" name="new-password" /></div>
                    <div><label for="confirm-new-password">Confirm the password</label>
                    <input type="password" name="confirm-new-password" /></div>
                </form>
                </li>
              </ul>
              <div id="reg-footer">
                <button id="form-submit">Countine</button>
              </div>
            </main>
            <footer>
              <center>
                <ul>
                    <li data-screenlocation="SignUp">Join in</li>
                    <li data-screenlocation="SignIn">Sign in</li>
                </ul>
              </center>
            </footer>
        </section>
    </div>
    <script src="/js/addons/authmodule/ui/form.js"></script>
    <script src="/js/addons/authmodule/ui/screen.js"></script>
    <script src="/js/addons/authmodule/services/addons/fb.js"></script>
    <script src="/js/addons/authmodule/services/addons/sendCode.js"></script>
    <script src="/js/addons/authmodule/services/signin.js"></script>
    <script src="/js/addons/authmodule/services/signup.js"></script>
    <script src="/js/addons/authmodule/services/forgot.js"></script>
    <script src="/js/addons/authmodule/script.js"></script>
		<?php } ?>
	</body>
	<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
	

