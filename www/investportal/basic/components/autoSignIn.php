<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
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
		$login = $this->signQuery['portalId'];
		$isValid = FALSE;
		$auth = NULL;
		
		foreach($model as $authData){
			$vLogin = $authData->login;
			
			if($vLogin == $login){ 
				$isValid = TRUE;
				$auth = User::findOne(['login' => $login])->all(); 
				break;
			}
		}

		if($isValid && Yii::$app->user->login($auth)){ echo 'First authorization success!'; }
		else{ 
			header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
			echo 'The portal accounting service is temporarily unavailable! Try again later;-(';
		}
	}
}
?>
