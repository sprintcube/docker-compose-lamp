<?php
namespace app/controllers;

use Yii;
use yii/web/controllers;

class NewsController extends Controller{
	public function actionIndex(){
		$this->registerCssFile("/css/inpage_codes/news/1.css", 'news-home-ui');
		$this->registerJsFile("/js/inpage_codes/news/1.js", View::POS_END, 'news-home-ux');
		
		return ['news/index'];
	}
	public function actionView($contentId){
		$this->registerCssFile("/css/inpage_codes/news/2.css", 'news-ui');
		$this->registerJsFile("/js/inpage_codes/news/2.js", View::POS_END, 'news-ux');
		
		return ['news/view'];
	}
}
?>
