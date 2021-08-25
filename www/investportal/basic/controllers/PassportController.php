<?php
namespace app/controllers;

use Yii;
use yii/web/controllers;

class PassportController extends Controller{
	public function actionService(){
		return ['passport/page'];
	}
}
?>
