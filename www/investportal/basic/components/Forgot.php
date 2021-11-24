<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use app\models\User;

class Forgot extends Component{
	public $signQuery;
	
	public function init(){
		parent::init();
		$this->signQuery = [];
	}

	public function proccess($signQuery = null){
		if($signQuery != null){
			$this->signQuery = $signQuery;
		}

		$forgotModel = User::find();
		$login = $this->signQuery['portalId'];
		$newPass = sha1($this->signQuery['password']);
		$isAccept = $this->signQuery['isAccept'] === TRUE;

		$isLogin = $inModel->where(['login' => $login])->all() || $inModel->where(['email' => $login])->all() || $inModel->where(['phone' => $login])->all();

		if($isLogin){
			if($isAccept){
				$forgotModel->filterWhere(['or',['login' => $login],['email' => $login],['phone' => $login]]);
				$forgotModel->password = $newPass;

				if($forgotModel->save()){ echo 'Access restore success!'; }
				else{ 
					header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
					echo 'The portal accounting service is temporarily unavailable! Try again later;-('; 
				}
			}
			else{ 
				header($_SERVER['SERVER_PROTOCOL'] ." 500 Internal Server Error");
				echo 'Account data for restore not is accept!'; 
			}	
		}
		else{
			$validError = [];
			header('Content-type: application/json;charset=UTF-8');

			if(!$isLogin){ $validError[]['validError'] = 'The login you entered no exists'; }

			header($_SERVER['SERVER_PROTOCOL'] ." 400 Bad Request");
			echo Json::encode($validError);
		}
		
	}
}

?>
