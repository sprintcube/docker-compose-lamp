<?php
namespace app/controllers;

use Yii;
use yii/web/controllers;

class ObjectsController extends Controller{
	public function actionIndex(){
		return ['objects/index'];
	}
	public function actionObject(){
		return ['objects/object'];
	}
	public function actionView(){
		return ['objects/view'];
	}
}
?>
