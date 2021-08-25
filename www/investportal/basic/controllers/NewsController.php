<?php
namespace app/controllers;

use Yii;
use yii/web/controllers;

class NewsController extends Controller{
	public function actionIndex(){
		return ['news/index'];
	}
	public function actionView($contentId){
		return ['news/view'];
	}
}
?>
