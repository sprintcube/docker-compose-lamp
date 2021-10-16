<?php
namespace yii\components\SignService;

use yii\base\Component;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use app\models\UserService;

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

						if($upModel[1]->save()){ throw new HttpException(202 ,'Registration success!'); }
						else{ throw new HttpException(409 ,'The portal accounting service is temporarily unavailable! Try again later;-('); }
					}
					else{ throw new HttpException(500 ,'New account data not is accept!'); }
			}
			else{
					$validError = [];
					header('Content-type: application/json;charset=UTF-8');

					if($validLogin){ $validError[]['validError'] = 'The login you entered exists'; }
					if($validEMail){ $validError[]['validError'] = 'The e-mail you entered exists'; }
					if($validPassword){ $validError[]['validError'] = 'The password you entered exists'; }
					if($validPhone){ $validError[]['validError'] = 'The phone number you entered exists'; }

					throw new HttpException(400 ,Json::encode($validError));
			}
		}

		

		
		
	}
	
}
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

		$inModel = User::find();

		if($type === 'fbService'){
			$token = $this->signQuery['fbToken'];
		}
		else{
			$login = $this->signQuery['portalId'];
			$pass = sha1($this->signQuery['password']);

			$isLogin = $inModel->where(['login' => $login])->all() || $inModel->where(['email' => $login])->all() || $inModel->where(['phone' => $login])->all();
			$isPass = $inModel->where(['password' => $pass]);

			if($isLogin && $isPass){
				$auth = User::findOne(['login' => $login]) || User::findOne(['email' => $login]) || User::findOne(['phone' => $login]);

				if(Yii::$app->user->login($auth)){ throw new HttpException(202 ,'Authorization success!'); }
				else{ throw new HttpException(409 ,'The portal accounting service is temporarily unavailable! Try again later;-('); }
			}
			else{
					$validError = [];
					header('Content-type: application/json;charset=UTF-8');

					if(!$isLogin){ $validError[]['validError'] = 'The login you entered no exists'; }
					if(!$isPass){ $validError[]['validError'] = 'The password you entered exists'; }

					throw new HttpException(400 ,Json::encode($validError));
			}
		}
	}
}

class AutoSignIn extends Component{
	public $signQuery;
	
	public function init(){
		parent::init();
		$this->signQuery = [];
	}

	public function proccess($signQuery = null){
		if($signQuery != null){ $this->signQuery = $signQuery; }

		$login = $this->signQuery['portalId'];


		$auth = User::findOne(['login' => $login]) || User::findOne(['email' => $login]) || User::findOne(['phone' => $login]);

		if(Yii::$app->user->login($auth)){ throw new HttpException(202 ,'First authorization success!'); }
		else{ throw new HttpException(409 ,'The portal accounting service is temporarily unavailable! Try again later;-('); }
	}
}

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

				if($forgotModel->save()){ throw new HttpException(202 ,'Access restore success!'); }
				else{ throw new HttpException(409 ,'The portal accounting service is temporarily unavailable! Try again later;-('); }
			}
			else{ throw new HttpException(500 ,'Account data for restore not is accept!'); }	
		}
		else{
			$validError = [];
			header('Content-type: application/json;charset=UTF-8');

			if(!$isLogin){ $validError[]['validError'] = 'The login you entered no exists'; }

			throw new HttpException(400 ,Json::encode($validError));
		}
		
	}
}

class SignOut extends Component{
	public function init(){ parent::init(); }

	public function proccess(){
		$out = Yii::app()->user->logout();

		if($out){ throw new HttpException(202 ,'Sign account out success!'); }
		else{ throw new HttpException(409 ,'The portal accounting service is temporarily unavailable! Try again later;-('); }
	}

}
?>
