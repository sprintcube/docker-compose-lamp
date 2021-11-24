<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use app\models\User;

class SignIn extends Component{
	public $type;
	public $signQuery;
	
	public function init(){
		parent::init();

		$this->signQuery = [];
		$this->type = '';
	}

	public function proccess($signQuery = null, $type = ''){
		if($signQuery != null && $type != ''){
			$this->signQuery = $signQuery;
			$this->type = $type;
		}

		$model = User::find();
		$isValid = FALSE;
		
		$vLogin = '';
		$vPhone = '';
		$vMail = '';
		$vPass = '';

		if($type === 'fbService'){
			$token = $this->signQuery['fbToken'];
		}
		else{
			$login = $this->signQuery['portalId'];
			$pass = sha1($this->signQuery['password']);

			foreach($model as $authData){
				$vLogin = $authData->login;
				$vPhone = $authData->phone;
				$vMail = $authData->email;
				$vPass = $authData->password;
				
				if(($vLogin == $login || $vPhone == $login || $vMail == $login) && $vPass == $pass){ 
					$isValid = TRUE; 
					break;
				}
			}

			if($isValid){
				$auth = User::findOne(['login' => $login])->orWhere(['email' => $login, 'phone' => $login])->all();

				if(Yii::$app->user->login($auth)){ echo 'Authorization success!'; }
				else{ 
					header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
					echo 'The portal accounting service is temporarily unavailable! Try again later;-('; 
				}
			}
			else{
					$validError = [];
					header('Content-type: application/json;charset=UTF-8');

					if(!$vLogin == $login || !$vPhone == $login || !$vMail == $login){ $validError[]['validError'] = 'The login you entered no exists'; }
					if(!$vPass == $pass){ $validError[]['validError'] = 'The password you entered exists'; }

					header($_SERVER['SERVER_PROTOCOL'] ." 400 Bad Request");
					echo Json::encode($validError);
			}
		}
	}
}

?>
