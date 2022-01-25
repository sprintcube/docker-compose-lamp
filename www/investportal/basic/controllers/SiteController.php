<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;
use yii\web\Json;
use linslin\yii2\curl\Curl;
use yii\web\NotFoundHttpException;

class SiteController extends Controller{
	public function beforeAction($action) { 
		$this->enableCsrfValidation = false; 
		return parent::beforeAction($action); 
	}
	public function actionIndex(){
		$this->view->registerCssFile("https://unpkg.com/swiper/swiper-bundle.min.css");
		$this->view->registerJsFile("https://unpkg.com/swiper/swiper-bundle.min.js", ['position' => View::POS_HEAD]);
		$this->view->registerCssFile("/css/inpage_codes/homepage_styles.css");
		$this->view->registerJsFile("/js/inpage_codes/homepage_script.js", ['position' => View::POS_END]);
		

		return $this->render('index');
	}

	public function actionAccountService($service){
		$q = Json::decode($_POST['serviceQuery']);
		
		if($service == "signIn"){
			if($_POST['serviceQuery']){
					$sign = $q['asq']; //Authoriation service query
					$type = $q['asqt']; //ASQ Type

					Yii::$app->portalLogin->proccess($sign,$type);
			}
			else{ 
					header("HTTP/1.1 405 Method Not Allowed");
					\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
					return 'Query conflict'; 
			}
		}
		
		if($service == "signUp"){
			if($_POST['serviceQuery']){
					$sign = $q['rsq']; //Registration service query
					$type = $q['rsqt']; //RSQ Type

					Yii::$app->portalReg->proccess($sign,$type);
			}
			else{ 
					header("HTTP/1.1 405 Method Not Allowed");
					\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
					return 'Query conflict'; 
			}
		}
		if($service == "forgot"){
			if($_POST['serviceQuery']){
					$sign = $q['fsq']; //Forgot service query

					Yii::$app->portalPass->proccess($sign);	
			}
			else{ 
					header("HTTP/1.1 405 Method Not Allowed");
					\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
					return 'Query conflict'; 
			}
		}
		if($service == "autoAuth"){
			if($_POST['serviceQuery']){
					$sign = $q['fsq']; //Forgot service query
					Yii::$app->autoLogin->proccess($sign);	
			}
			else{ 
					header("HTTP/1.1 405 Method Not Allowed");
					\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
					return 'Query conflict'; 
			}
		}
		if($service == "signOut"){
			if(!Yii::$app->user->isGuest){ Yii::$app->portalExit->proccess(); }
			else{ 
					header("HTTP/1.1 405 Method Not Allowed");
					\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
					return 'Service conflict'; 
			}
		}
		
		
		if($service == "getInfo"){
			if($_POST['serviceQuery']){
					$userId = $q['investPortalID'];
					$serviceResponse = [];

					$userData = User::find()->where('or',['login' => $userId],['email' => $userId],['phone' => $userId])->all();

					if($userData){
						
						foreach($userData as $data){
							$serviceResponse[] = [
								'fn' => $data->firstname,
								'sn' => $data->surname,
								'un' => $data->login,
								'em' => $data->email,
								'phone' => $data->phone,
								'region' => $data->country
							];
						}
						
						\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
						return $serviceResponse;

						
					}
					else{
						header("HTTP/1.1 404 Not Found");
						\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
						return 'User not found';
					}
					
			}
			else{ 
					header("HTTP/1.1 405 Method Not Allowed");
					\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
					return 'Query conflict'; 
			}
		}
		else{
				header("HTTP/1.1 404 Not Found");
				\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
				return 'Service not found'; 
		}
	}
	public function actionServiceCodeCenter($service){
		$q = json_decode($_POST['serviceQuery']);
		
		if($service == "signUp"){
			if($_POST['serviceQuery']){
					$sign = $q['rsq']; //Registration service query

					if($sign['service'] === 'Inbox'){
						$query = json_encode(['fsq' => ['svc' => 'SignUp']]);

						$wsInit = new Curl();
						$query = $wsInit->post((!empty($_SERVER['HTTPS'])) ? 'https' : 'http' . '://' . $_SERVER['HTTP_HOST'] ."/accounts/accept/codeGenerator")->setOption(CURLOPT_POSTFIELDS, http_build_query(array('serviceQuery' => $query)));

						$sign['code'] = $query;
					}

					if($sign['service'] === 'Inbox'){ Yii::$app->smsCoder->sendCode('SignUp', $sign['phone'], $sign['code']); }
					else if($sign['service'] === 'Valid'){ Yii::$app->smsCoder->validCode('SignUp', $sign['phone'], $sign['code']); }
					else{ 
						header("HTTP/1.1 403 Forbidden");
						\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
						return 'Operation conflict'; 
					}
			}
			else{ 
					header("HTTP/1.1 405 Method Not Allowed");
					\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
					return 'Query conflict'; 
			}
		}
		if($service == "forgot"){
				if($_POST['serviceQuery']){
					$sign = $q['fsq']; //Forgot service query

					if($sign['service'] === 'Inbox'){
						$query = json_encode(['fsq' => ['svc' => 'Forgot']]);

						$wsInit = new Curl();
						$query = $wsInit->post((!empty($_SERVER['HTTPS'])) ? 'https' : 'http' . '://' . $_SERVER['HTTP_HOST'] ."/accounts/accept/codeGenerator")->setOption(CURLOPT_POSTFIELDS, http_build_query(array('serviceQuery' => $query)));

						$sign['code'] = $query;
					}

					if($sign['service'] === 'Inbox'){ Yii::$app->smsCoder->sendCode('Forgot', $sign['phone']); }
					else if($sign['service'] === 'Valid'){ Yii::$app->smsCoder->validCode('Forgot', $sign['phone'], $sign['code']); }
					else{ 
						header("HTTP/1.1 403 Forbidden");
						\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
						return 'Operation conflict'; 
					}
					
				}
				else{ 
					header("HTTP/1.1 405 Method Not Allowed");
					\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
					return 'Query conflict'; 
				}
		}
		
		if($service == "codeGenerator"){
				if($_POST['serviceQuery']){
					$source = $q['fsq'];

					$generateCode = [
						ceil(getRandomFromRange(1000,9999)),
						ceil(getRandomFromRange(2000,4600))
					];

					$isSignUp = $source['svc'] === 'SignUp' ? TRUE : FALSE;
					$isForgot = $source['svc'] === 'Forgot' ? TRUE : FALSE;

					if($isSignUp){ $newCode = $generateCode[0]; }
					else if($isForgot){ $newCode = $generateCode[1]; }

					return $newCode;
					
				}
				else{ 
					header("HTTP/1.1 405 Method Not Allowed");
					\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
					return 'Query conflict'; 
				}
		}
		else{
				header("HTTP/1.1 404 Not Found");
				\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
				return 'Service not found'; 
		}
	}
	
	public function actionAccountFacebookService(){
		if(!empty($_GET['svcUserQuery'])){
			$platform = trim($_GET['svcUserQuery']);
			
			\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			
			switch($platform){
				case 'web':
					$webResponse = array();
					
					
					
					return $webResponse;
				break;
				default:
					header("HTTP/1.1 405 Method Not Allowed");
					\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
					return 'Query conflict'; 
				break;
			}
		}
		else{
			header("HTTP/1.1 404 Not Found");
			\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
			return 'Service not found';
		}
	}
}
?>
