<?php
namespace app/controllers;

use Yii;
use yii/web/controllers;
use yii/web/View;

class SiteController extends Controller{
	public function actionIndex(){
		$this->registerCssFile("/css/inpage_codes/homepage_styles.css", 'homepage-ui');
		$this->registerJsFile("/js/inpage_codes/homepage_script.js", View::POS_END, 'homepage-ux');

		return ['index'];
	}
}
?>
