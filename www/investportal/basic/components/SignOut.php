<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;

class SignOut extends Component{
	public function init(){ parent::init(); }

	public function proccess(){
		$cookieData = Yii::$app->response->cookies;
		$out = $cookieData->remove('portalUser');
		
		\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
		
		if($out){ return 'Sign account out success!'; }
		else{ 
			header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
			return 'The portal accounting service is temporarily unavailable! Try again later;-('; 
		}
	}

}
?>
