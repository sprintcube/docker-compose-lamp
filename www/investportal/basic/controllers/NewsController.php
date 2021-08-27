<?php
namespace app/controllers;

use Yii;
use yii/web/controllers;
use yii/web/View;

class NewsController extends Controller{
	public function actionIndex(){
		$this->registerCssFile("/css/inpage_codes/news/1.css", 'news-home-ui');
		
		return ['news/index'];
	}
	public function actionView($contentId){
		$this->registerCssFile("/css/inpage_codes/news/2.css", 'news-ui');
		
		return ['news/view'];
	}
}
?>
