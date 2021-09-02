<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;

class PassportController extends Controller{
	public function actionService(){
		$this->registerCssFile("/css/passport.css");
		$this->registerCssFile("/css/inpage_codes/passport/1.css");
		$this->registerJsFile("/js/passport.js", ['position' => POS_END]);
		
		return $this->render('passport/page');
	}
	public function actionAccountedit(){
		$this->registerCssFile("/css/passport.css");
		$this->registerCssFile("/css/inpage_codes/passport/2.css");
		$this->registerJsFile("/js/passport.js", ['position' => POS_END]);
		$this->registerCssFile("/css/passport/profile.css");
		$this->registerJsFile("/js/passport/profile.js", ['position' => POS_END]);
		
		return $this->render('passport/profile');
	}
	public function actionEventsedit(){
		$this->registerCssFile("/css/passport.css");
		$this->registerCssFile("/css/inpage_codes/passport/3.css");
		$this->registerJsFile("/js/passport.js", ['position' => POS_END]);
		$this->registerCssFile("/css/passport/services.css");
		$this->registerJsFile("/js/passport/services.js", ['position' => POS_END]);
		
		return $this->render('passport/services');
	}
	public function actionOffer(){
		$this->registerCssFile("/css/passport.css");
		$this->registerCssFile("/css/inpage_codes/passport/4.css");
		$this->registerJsFile("/js/passport.js", ['position' => POS_END]);
		$this->registerCssFile("/css/passport/offers.css");
		$this->registerJsFile("/js/passport/offers.js", ['position' => POS_END]);
		
		return $this->render('passport/offers');
	}
	public function actionCart(){
		$this->registerCssFile("/css/passport.css");
		$this->registerCssFile("/css/inpage_codes/passport/5.css");
		$this->registerJsFile("/js/passport.js", ['position' => POS_END]);
		$this->registerCssFile("/css/passport/cart.css");
		$this->registerJsFile("/js/passport/cart.js", ['position' => POS_END]);
		
		return $this->render('passport/cart');
	}
}
?>
