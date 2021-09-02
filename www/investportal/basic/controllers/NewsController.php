<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;

class NewsController extends Controller{
	public function actionIndex(){
		$this->registerCssFile("/css/news.css");
		$this->registerJsFile("/js/news.js", ['position' => View::POS_END]);
		$this->registerCssFile("/css/inpage_codes/news/1.css");
		
		return $this->render('news/index');
	}
	public function actionView($contentId){
		$this->registerCssFile("/css/news.css");
		$this->registerJsFile("/js/news.js", ['position' => View::POS_END]);
		$this->registerJsFile("/js/news/view.js", ['position' => View::POS_END]);
		$this->registerCssFile("/css/news/view.css");
		$this->registerCssFile("/css/inpage_codes/news/2.css");
		
		return $this->render('news/view');
	}
}
?>
