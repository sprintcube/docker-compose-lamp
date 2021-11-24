<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use app\models\User;

class SignUp extends Component{
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

		$upModel = [
			User::find(),
			new User()
		];

		if($type === 'fbService'){
			$token = $this->signQuery['fbToken'];

			
		}
		else{
			$login = $this->signQuery['login'];
			$pass = sha1($this->signQuery['password']);
			$firstname = $this->signQuery['fn'];
			$surname = $this->signQuery['sn'];
			$mail = $this->signQuery['email'];
			$phone = $this->signQuery['phone'];
			$region = $this->signQuery['country'];
			$isAccept = $this->signQuery['isAccept'] === TRUE;

			$validLogin = $upModel[0]->where(['login' => $login])->all();
			$validEMail = $upModel[0]->where(['email' => $mail])->all();
			$validPassword = $upModel[0]->where(['password' => $pass])->all();
			$validPhone = $upModel[0]->where(['phone' => $phone])->all();

			if(!$validLogin && !$validEMail && !$validPassword && !$validPhone){
					if($isAccept){
						$upModel[1]->firstname = $firstname;
						$upModel[1]->surname = $surname;
						$upModel[1]->login = $login;
						$upModel[1]->password = $pass;
						$upModel[1]->email = $mail;
						$upModel[1]->phone = $phone;
						$upModel[1]->country = $region;
						$upModel[1]->isAccept= TRUE;

						if($upModel[1]->save()){ echo 'Registration success!'; }
						else{ 
							header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
							echo 'The portal accounting service is temporarily unavailable! Try again later;-('; 
						}
					}
					else{ 
						header($_SERVER['SERVER_PROTOCOL'] ." 500 Internal Server Error");
						echo 'New account data not is accept!'; 
					}
			}
			else{
					$validError = [];
					header('Content-type: application/json;charset=UTF-8');

					if($validLogin){ $validError[]['validError'] = 'The login you entered exists'; }
					if($validEMail){ $validError[]['validError'] = 'The e-mail you entered exists'; }
					if($validPassword){ $validError[]['validError'] = 'The password you entered exists'; }
					if($validPhone){ $validError[]['validError'] = 'The phone number you entered exists'; }

					header($_SERVER['SERVER_PROTOCOL'] ." 400 Bad Request");
					echo Json::encode($validError);
			}
		}

		

		
		
	}
	
}
?>
