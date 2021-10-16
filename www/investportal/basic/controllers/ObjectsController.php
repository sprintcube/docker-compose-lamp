<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;

class ObjectsController extends Controller{
	public function actionIndex(){
		$this->view->registerCssFile("/css/objects.css");
		$this->view->registerCssFile("/css/inpage_codes/objects/1.css");
		$this->view->registerJsFile("/js/objects.js", ['position' => View::POS_END]);
	
		return $this->render('objects');
	}
	public function actionObject(){
		$this->view->registerCssFile("/css/objects.css");
		$this->view->registerCssFile("/css/objects/hotels.css");
		$this->view->registerCssFile("/css/inpage_codes/objects/2.css");
		$this->view->registerJsFile("/js/objects.js", ['position' => View::POS_END]);
		$this->view->registerJsFile("/js/objects/hotels.js", ['position' => View::POS_END]);
		
		return $this->render('object');
	}
	public function actionView($objectId){
		$this->view->registerCssFile("/css/objects.css");
		$this->view->registerCssFile("/css/objects/view.css");
		$this->view->registerCssFile("/css/inpage_codes/objects/3.css");
		$this->view->registerJsFile("/js/objects.js", ['position' => View::POS_END]);
		$this->view->registerJsFile("/js/objects/view.js", ['position' => View::POS_END]);
		
		return $this->render('objectsView');
	}
}
?>
