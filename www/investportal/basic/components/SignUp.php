<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\web\Cookie;
use app\models\User;


$cl = new Client();

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

			if(!empty($token)){
				$params = [
					'client_id'     => '404988774385568',
					'client_secret' => 'f3504d4d2f1ed4a679180a63f6262849',
					'redirect_uri'  => '',
					'code'          => $token
				];
				
				$response = $client->createRequest()
						->setMethod('GET')
						->setUrl('https://graph.facebook.com/oauth/access_token')
						->setData(urldecode(http_build_query($params)))
						->send();
						
				if($type === 'fbService'){
			$token = $this->signQuery['fbToken'];
			
			if(!empty($token)){
				$params = [
					'client_id'     => '404988774385568',
					'client_secret' => 'f3504d4d2f1ed4a679180a63f6262849',
					'redirect_uri'  => '',
					'code'          => $token
				];
				
				$response = $client->createRequest()
						->setMethod('GET')
						->setUrl('https://graph.facebook.com/oauth/access_token')
						->setData(urldecode(http_build_query($params)))
						->send();
						
				if ($response->isOk) {
					$accessData = Json::decode($response);
					$responseService = [];
					
					if($accessData['access_token']){
						$paramsData = array(
							'access_token' => $accessData['access_token'],
							'fields'       => 'id,email,first_name,last_name,location'
						);
						
						$responseProccess = $client->createRequest()
									->setMethod('GET')
									->setUrl('https://graph.facebook.com/me')
									->setData(urldecode(http_build_query($paramsData)))
									->send();
									
						
						$proccessResponse = Json::decode($responseProccess);
						
						
						
						
						$auth = $cookieData->add(new Cookie([
							'fbService' => Json::encode($responseService)
						])); 
						
						\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
						
						if($auth){ return 'Facebook Sync success!'; }
						else{ 
							header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
							return 'The portal sync service is temporarily unavailable! Try again later;-('; 
						}
						
					}
					else{
						header($_SERVER['SERVER_PROTOCOL'] .' 405 Method Not Allowed');
						\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
						return "Error interacting with a third-party service by token";
					}
				}
				
			}
			else{
				header($_SERVER['SERVER_PROTOCOL'] .' 502 Bad Gateway');
				\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
				return "Error executing a request to a third-party service!";
			}
				
				
			}
			else{
				
			}
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
						
						\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
						
						if($upModel[1]->save()){ echo 'Registration success!'; }
						else{ 
							header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
							return 'The portal accounting service is temporarily unavailable! Try again later;-('; 
						}
					}
					else{ 
						header($_SERVER['SERVER_PROTOCOL'] ." 500 Internal Server Error");
						\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
						return 'New account data not is accept!'; 
					}
			}
			else{
					$validError = array();
					

					if($validLogin){ array_push($validError, ['validError' => 'The login you entered exists']); }
					if($validEMail){ array_push($validError, ['validError' => 'The e-mail you entered exists']); }
					if($validPassword){ array_push($validError, ['validError' => 'The password you entered exists']); }
					if($validPhone){ array_push($validError, ['validError' => 'The phone number you entered exists']); }

					header($_SERVER['SERVER_PROTOCOL'] ." 400 Bad Request");
					\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					return $validError;
			}
		}

		

		
		
	}
	
}
?>
