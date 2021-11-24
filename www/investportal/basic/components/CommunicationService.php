<?php
namespace app\components\CommunicationService;

use Yii;
use yii\base\Component;
use yii\web\NotFoundHttpException;
use yii\db\Query;
use yii\models\UserService;

function getRandomFromRange($min, $max) {
    return rand() * ($max - $min) + $min;
}

class SMSCode extends Component{
	public $service;
	public $code;
	public $phone;

	public function init(){
		parent::init();

		$this->service = 'signUp';
		$this->code = 1234;
		$this->phone = '9198298765';
	}
	public function sendCode($service = '', $phone = '', $code = null){
		if($service != '' && $phone != '' && $code != null){
			$this->service = $service;
			$this->phone = $phone;
			$this->code = $code;
		}

		$sms = new SenderCode();
		$message = "";

		switch($this->service){
			case 'Forgot': $message = " - Restore your account access code"; break;
			default: $message = " - Your account registration confirm code"; break;
		}

		$from = 'Investportal<9198298765@vtext.com>';
		$to = $this->phone . '@vtext.com';
		$content = $code . $message . "";

		$sms->period = date('');
		$sms->phone = $this->phone;
		$sms->code = $this->code;
		$sms->service = $this->service;

		$smsStorage = $sms->save();
		$smsMessage = mail($to,'', $content, "From: " . $from ."\n");

		if($smsStorage && $smsMessage){ echo 'SMS code send success!';}
		else{ 
			header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
			echo 'The portal accounting service is temporarily unavailable! Try again later;-('; 
		}

		
		
	}
	public function validCode($service = '', $phone = '', $code = null){
		if($service != '' && $phone != '' && $code != null){
			$this->service = $service;
			$this->phone = $phone;
			$this->code = $code;
		}

		$sms = SenderCode::find();
		$validCode = $sms->where(['and',['code' => $this->code],['phone' => $this->phone],['service' => $this->service]])->all();
		$deleteCode = (new Query)->createCommand()->delete('users', ['and',['code' => $this->code],['phone' => $this->phone],['service' => $this->service]])->execute();

		foreach($validCode as $data){
			if($this->code === $data->code){
				if($deleteCode){ echo 'SMS code is valid!';}
				else{ 
					header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
					echo 'The portal accounting service is temporarily unavailable! Try again later;-('; 
				}
			}
			else{ 
				header($_SERVER['SERVER_PROTOCOL'] ." 403 Forbidden");
				echo 'The code is entered incorrectly and check it carefully, please!'; 
			}
		}

		

		
	}
}
?>
