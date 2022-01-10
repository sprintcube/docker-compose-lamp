<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $content string */
?>
<!DOCTYPE html>
<html lang="en">
	<?php $this->beginPage() ?>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover" />
		<title><?= Html::encode($this->title) ?></title>
		<?= Html::csrfMetaTags() ?>
		<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.6.0.min.js"></script>
		<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
		<script src="https://unpkg.com/react@17/umd/react.production.min.js"></script>
		<script src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js"></script>
		<script src="https://kit.fontawesome.com/97c3285af2.js" crossorigin="anonymous"></script>
		<?php $this->head() ?>
	</head>
	<body>
		<?php $this->beginBody() ?>
		<div class="admin-portal-page">
		  <header>
			<nav>
			  <a href="/admin">Dashboard</a>
			  <a href="#">News and Events</a>
			  <a href="#">Data Services and Filters</a>
			  <a href="#">Users</a>
			  <a href="#">Portal Managment</a>
			</nav>
			<footer>
			  <ul>
				<li><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAABIUlEQVRIie3UPUoDURTF8ReVkM5aMJ0ktVmDdRrTx1W4AAtJ4xqsUhgCEqwEXUQalxARVEhE0gg/i7zAMGY+SKb0wCvmvjPn/5h734TwryqEJsZYxHWPdpXhH/7qE80qAOMN4WuNqgAscgDzovf3duVXAXjO2Xssf5YMoR0bmtY7jncGREgTI8zjuqss/F+Zwj7OMMATXvCFb9ykvJ1E8ztFwQ1cYpZxud5wGL1dtDBJ7E/i5HU3hR9hmnNz4Sp6ewU+6KUBDyVeOo3eYaI2Qz+u10R9mAYsSwDWn6ee8PcTGRextkQ9/ato5DZopZ+C/Vry4aBEYFonIYRpCOE2caABajH8OtYa0bOdlGvy+daACOlajWR6TFs2jekOoMyL9gutWUn2RJl12AAAAABJRU5ErkJggg=="></li>
				<li><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAAn0lEQVRIid2Uuw0CMRBER4jsWqAFCoEqLnJdJ5oix9RA+ohOOp0W2PUnMBNZq5kd78eW/g7AFcjEkYGLx6Ak+YqHxwCAgspN3SGaKIqhDBYXKzoDYLbOLQ0AbsAUEUQNAO7AucrA8Q5eQNpqum/Rx1tG+U1a9IXfdch913SjW1y6CgNTN9RfZOJoxJ6STiVtkpT3AauCZBGdydNP1nB4A5tWVezafhVfAAAAAElFTkSuQmCC"></li>
			  </ul>
			</footer>
		  </header>
		  <main><?php echo $content; ?></main>
	    </div>
		<?php $this->endBody() ?>
	</body>
	<?php $this->endPage() ?>
</html>
