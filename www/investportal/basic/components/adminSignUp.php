<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use app\models\Admin;

class adminSignUp extends Component{
	public $signQuery;
	
	
	public function init(){
		parent::init();
		$this->signQuery = [];
	}
	
	public function proccess($signQuery = null){
		if($signQuery != null){ $this->signQuery = $signQuery; }
		
		$svc = $this->adminControl($this->signQuery);
		
		switch($svc['state']){
			case 0: header('Location: /admin'); break;
			case 1:
				$res = '<script>let problem=alert("The portal administration accounting service is temporarily unavailable! Try again later;-("),res="";res=problem?"/":"/admin/auth",window.location.assign(res);</script>';
				header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
				echo $res;
			break;
			default:
				$validError = '';

				if($svc['isLogin']){ $validError .= 'The login you entered exists\n'; }
				if($svc['isEMail']){ $validError .= 'The e-mail you entered exists\n'; }
				if($svc['isPass']){ $validError .= 'The password you entered exists\n'; }
				if($svc['isPhone']){ $validError .= 'The phone number you entered exists\n'; }
				
				$res = '<script>let problem=alert("'. $validError .'"),res="";res=problem?"/admin":"/admin/auth",window.location.assign(res);</script>';
				header($_SERVER['SERVER_PROTOCOL'] ." 400 Bad Request");
				echo $res;
			break;
		}
	}
	private function adminControl($wayData){
		$upModel = [
			Admin::find(),
			new Admin()
		];
		
		$response = [
			'state' => 3,
			'isLogin' => FALSE,
			'isPass' => FALSE,
			'isEMail' => FALSE,
			'isPhone' => FALSE
		];
		
		$login = $wayData['login'];
		$pass = sha1($wayData['password']);
		$firstname = $wayData['fn'];
		$surname = $wayData['sn'];
		$mail = $wayData['email'];
		$phone = $wayData['phone'];
		$region = $wayData['country'];

		$validLogin = $upModel[0]->where(['login' => $login])->all();
		$validEMail = $upModel[0]->where(['email' => $mail])->all();
		$validPassword = $upModel[0]->where(['password' => $pass])->all();
		$validPhone = $upModel[0]->where(['phone' => $phone])->all();

		if(!$validLogin && !$validEMail && !$validPassword && !$validPhone){
			$upModel[1]->firstname = $firstname;
			$upModel[1]->surname = $surname;
			$upModel[1]->login = $login;
			$upModel[1]->password = $pass;
			$upModel[1]->email = $mail;
			$upModel[1]->phone = $phone;
			$upModel[1]->country = $region;


			if($upModel[1]->save()){ $response['state'] = 0; }
			else{ $response['state'] = 1; }
		}
		else{
			$response['state'] = 2;
			
			if($validLogin){ $response['isLogin'] = TRUE; }
			if($validEMail){ $response['isEmail'] = TRUE; }
			if($validPassword){ $response['isPass'] = TRUE; }
			if($validPhone){ $response['isPhone'] = TRUE; }
		}
		
		return $response;
	}
}
?>
