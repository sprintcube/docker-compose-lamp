<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;

class ObjectsController extends Controller{
	public function actionIndex(){
		$this->registerCssFile("/css/objects.css");
		$this->registerCssFile("/css/inpage_codes/objects/1.css");
		$this->registerJsFile("/js/objects.js", ['position' => POS_END]);
	
		return $this->render('objects/index');
	}
	public function actionObject(){
		$this->registerCssFile("/css/objects.css");
		$this->registerCssFile("/css/objects/hotels.css");
		$this->registerCssFile("/css/inpage_codes/objects/2.css");
		$this->registerJsFile("/js/objects.js", ['position' => POS_END]);
		$this->registerJsFile("/js/objects/hotels.js", ['position' => POS_END]);
		
		return $this->render('objects/object');
	}
	public function actionView($objectId){
		$this->registerCssFile("/css/objects.css");
		$this->registerCssFile("/css/objects/view.css");
		$this->registerCssFile("/css/inpage_codes/objects/3.css");
		$this->registerJsFile("/js/objects.js", ['position' => POS_END]);
		$this->registerJsFile("/js/objects/view.js", ['position' => POS_END]);
		
		return $this->render('objects/view');
	}
}
?>
