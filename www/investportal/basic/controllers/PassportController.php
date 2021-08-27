<?php
namespace app/controllers;

use Yii;
use yii/web/controllers;
use yii/web/View;

class PassportController extends Controller{
	public function actionService(){
		
		return ['passport/page'];
	}
}
?>
