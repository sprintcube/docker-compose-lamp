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
		<script src="https://good-adults.ru/js/lib/device.js"></script>
		<script src="https://kit.fontawesome.com/97c3285af2.js" crossorigin="anonymous"></script>
		<?php $this->head() ?>
	</head>
	<body>
		<?php $this->beginBody() ?>
		<?php echo $content; ?>
		<?php $this->endBody() ?>
	</body>
	<?php $this->endPage() ?>
</html>
