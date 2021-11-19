<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;

class PassportController extends Controller{
	public function actionService(){
		$this->view->registerCssFile("/css/passport.css");
		$this->view->registerCssFile("/css/inpage_codes/passport/1.css");
		$this->view->registerJsFile("/js/passport.js", ['position' => View::POS_END]);
		
		return $this->render('passportPage');
	}
	public function actionAccountedit(){
		$this->view->registerCssFile("/css/passport.css");
		$this->view->registerCssFile("/css/inpage_codes/passport/2.css");
		$this->view->registerJsFile("/js/passport.js", ['position' => View::POS_END]);
		$this->view->registerCssFile("/css/passport/profile.css");
		$this->view->registerJsFile("/js/passport/profile.js", ['position' => View::POS_END]);
		
		return $this->render('passportProfile');
	}
	public function actionEventsedit(){
		$this->view->registerCssFile("/css/passport.css");
		$this->view->registerCssFile("/css/inpage_codes/passport/3.css");
		$this->view->registerJsFile("/js/passport.js", ['position' => View::POS_END]);
		$this->view->registerCssFile("/css/passport/services.css");
		$this->view->registerJsFile("/js/passport/services.js", ['position' => View::POS_END]);
		
		return $this->render('passportServices');
	}
	public function actionOffer(){
		$this->view->registerCssFile("/css/passport.css");
		$this->view->registerCssFile("/css/inpage_codes/passport/4.css");
		$this->view->registerJsFile("/js/passport.js", ['position' => View::POS_END]);
		$this->view->registerCssFile("/css/passport/offers.css");
		$this->view->registerJsFile("/js/passport/offers.js", ['position' => View::POS_END]);
		
		return $this->render('passportOffers');
	}
	public function actionCart(){
		$this->view->registerCssFile("/css/passport.css");
		$this->view->registerCssFile("/css/inpage_codes/passport/5.css");
		$this->view->registerJsFile("/js/passport.js", ['position' => View::POS_END]);
		$this->view->registerCssFile("/css/passport/cart.css");
		$this->view->registerJsFile("/js/passport/cart.js", ['position' => View::POS_END]);
		
		return $this->render('passportCart');
	}
	
	public function actionPassportservice($type){
		
	}
}
?>
