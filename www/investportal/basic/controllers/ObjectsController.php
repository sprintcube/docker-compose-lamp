<?php
namespace app/controllers;

use Yii;
use yii/web/controllers;
use yii/web/View;

class ObjectsController extends Controller{
	public function actionIndex(){
		$this->registerCssFile("/css/inpage_codes/objects/1.css", 'objects-home-ui');
		$this->registerJsFile("/js/inpage_codes/objects/1.js", View::POS_END, 'objects-home-ux');
		
		return ['objects/index'];
	}
	public function actionObject(){
		$this->registerCssFile("/css/inpage_codes/objects/2.css", 'objects-filter-ui');
		$this->registerJsFile("/js/inpage_codes/objects/2.js", View::POS_END, 'objects-filter-ux');
		
		return ['objects/object'];
	}
	public function actionView(){
		$this->registerCssFile("/css/inpage_codes/objects/3.css", 'object-ui');
		$this->registerJsFile("/js/inpage_codes/objects/3.js", View::POS_END, 'object-ux');
		
		return ['objects/view'];
	}
}
?>
