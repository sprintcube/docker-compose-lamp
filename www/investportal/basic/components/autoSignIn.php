<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use yii\web\Cookie;
use app\models\User;

class autoSignIn extends Component{
	public $signQuery;
	
	public function init(){
		parent::init();
		$this->signQuery = [];
	}

	public function proccess($signQuery = null){
		if($signQuery != null){ $this->signQuery = $signQuery; }

		$model = User::find();
		$cookieData = Yii::$app->response->cookies;
		$login = $this->signQuery['portalId'];
		$isValid = FALSE;
		$auth = NULL;
		
		$cookieData = Yii::$app->response->cookies;
		
		foreach($model->all() as $authData){
			$vLogin = $authData->login;
			
			if($vLogin == $login){ 
				$isValid = TRUE;
				$auth = $cookieData->add(new Cookie([
					'portalUser' => $login
				])); 
				break;
			}
		}
		
		\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
		
		if($isValid && $auth){ return 'First authorization success!'; }
		else{ 
			header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
			return 'The portal accounting service is temporarily unavailable! Try again later;-(';
		}
	}
}
?>
