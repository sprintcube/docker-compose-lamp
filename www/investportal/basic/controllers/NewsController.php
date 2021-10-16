<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;

class NewsController extends Controller{
	public function actionIndex(){
		$this->view->registerCssFile("/css/news.css");
		$this->view->registerJsFile("/js/news.js", ['position' => View::POS_END]);
		$this->view->registerCssFile("/css/inpage_codes/news/1.css");
		
		return $this->render('news');
	}
	public function actionView($contentId){
		$this->view->registerCssFile("/css/news.css");
		$this->view->registerJsFile("/js/news.js", ['position' => View::POS_END]);
		$this->view->registerJsFile("/js/news/view.js", ['position' => View::POS_END]);
		$this->view->registerCssFile("/css/news/view.css");
		$this->view->registerCssFile("/css/inpage_codes/news/2.css");
		
		return $this->render('newsView');
	}
}
?>
