<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use yii\helpers\Url;
use app\models\Admin;

class adminSignIn extends Component{
	public $signQuery;
	
	
	public function init(){
		parent::init();
		$this->signQuery = [];
	}
	
	public function proccess($signQuery = null){
		if($signQuery != null){ $this->signQuery = $signQuery; }
		
		$svc = $this->adminControl($this->signQuery);
		
		switch($svc['state']){
			case 1: 
				header('Location: '. Url::to(['admin/index']));
			break;
			default:
				$validError = '';

				if($svc['noValidUser']){ $validError .= 'The login you entered no exists\n'; }
				if($svc['noValidPass']){ $validError .= 'The password you entered exists\n'; }
				
				$res = '<script>let problem=alert("'. $validError .'"),res="";res=problem?"/":"/admin/auth",window.location.assign(res);</script>';
				header($_SERVER['SERVER_PROTOCOL'] ." 400 Bad Request");
				echo $res;
			break;
		}
	}
	protected function adminControl($wayData){
		$response = [
			'state' => 3,
			'noValidUser' => FALSE,
			'noValidPass' => FALSE
		];
		
		
		$l = $wayData['admin'];
		$p = sha1($wayData['password']);
		
		$model = Admin::find();
		$isValid = FALSE;
		
		$sessionData = Yii::$app->session;
		
		$vLogin = '';
		$vMail = '';
		$vPass = '';
		
		foreach($model->all() as $authData){
			$vLogin = $authData->login;
			$vMail = $authData->email;
			$vPass = $authData->password;
				
			if(($vLogin == $l || $vMail == $l) && $vPass == $p){ 
				$isValid = TRUE;
				break; 
			}
		}
		
		if($isValid){
			$auth = $sessionData->set('adminUser', $l);

			if($auth){ $response['state'] = 0; }
			else{ $response['state'] = 1; }
		}
		else{
			$response['state'] = 2;
				
			if(!$vLogin == $l || !$vMail == $l){ $response['noValidUser'] = TRUE; }
			if(!$vPass == $p){ $response['noValidPass'] = TRUE; }
					
		}
		
		return $response;
		
		
	}
}

?>
