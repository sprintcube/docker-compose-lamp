<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Welcome to Investportal!';
?>
<?php if($_GET['svc']){ ?>
	<?php if($_GET['svc'] == "dataManagment"){ ?>
		<?php
		 if($_GET['subSVC']){
			 switch($_GET['subSVC']){
				 case "filters": echo "<section class=\"data-page\"><header><h2></h2><nav></nav></header><main></main></section>"; break;
				 default: echo "<section class=\"data-page\"><header><h2></h2><nav></nav></header><main></main></section>"; break;
			 }
		 } }
		?>
<?php } } else{?>
	<section class="admin-dashboard">
	  <h1>Dashboard</h1>
	  <header>
		<div id="board-tabs">
		  <section class="active"><span>Basic</span></section>
		  <section><span>Data</span></section>
		  <section><span>Content</span></section>
		</div>
	  </header>
	  <main>
		<ul>
		  <li class="active"></li>
		  <li></li>
		  <li></li>
		</ul>
	  </main>
	</section>
<?php } ?>
