<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use yii\web\Cookie;
use yii\httpclient\Client;
use app\models\User;


$cl = new Client();

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
		
		$cookieData = Yii::$app->response->cookies;
		
		$vLogin = '';
		$vPhone = '';
		$vMail = '';
		$vPass = '';

		if($type === 'fbService'){
			$token = $this->signQuery['fbToken'];
			
			if(!empty($token)){
				$params = [
					'client_id'     => '404988774385568',
					'client_secret' => 'f3504d4d2f1ed4a679180a63f6262849',
					'redirect_uri'  => Yii::$app->facebookPortalIDURI->generate($_SERVER['SERVER_NAME']),
					'code'          => $token
				];
				
				$response = $client->createRequest()
						->setMethod('GET')
						->setUrl('https://graph.facebook.com/oauth/access_token')
						->setData(urldecode(http_build_query($params)))
						->send();
						
				if ($response->isOk) {
					$accessData = Json::Decode($response);
					$responseService = [];
					
					if($accessData['access_token']){
						$paramsData = array(
							'access_token' => $accessData['access_token'],
							'fields'       => 'id,email,name'
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
						
						if($auth){ return 'Facebook Authorization success!'; }
						else{ 
							header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
							return 'The portal accounting service is temporarily unavailable! Try again later;-('; 
						}
						
					}
					else{
						header($_SERVER['SERVER_PROTOCOL'] .' 405 Method Not Allowed');
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
			$login = $this->signQuery['portalId'];
			$pass = sha1($this->signQuery['password']);

			foreach($model->all() as $authData){
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
				$auth = $cookieData->add(new Cookie([
					'portalUser' => $login
				])); 
				
				\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

				if($auth){ return 'Authorization success!'; }
				else{ 
					header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
					return 'The portal accounting service is temporarily unavailable! Try again later;-('; 
				}
			}
			else{
					$validError = [];
					header('Content-type: application/json;charset=UTF-8');

					if(!$vLogin == $login || !$vPhone == $login || !$vMail == $login){ array_push($validError, ['validError' => 'The login you entered no exists']); }
					if(!$vPass == $pass){ array_push($validError, ['validError' => 'The password you entered exists']); }

					header($_SERVER['SERVER_PROTOCOL'] ." 400 Bad Request");
					\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					return Json::encode($validError);
			}
		}
	}
}

?>
