<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;
use linslin\yii2\curl\Curl;
use yii\web\NotFoundHttpException;

class SiteController extends Controller{
	public function beforeAction($action){
	 if (in_array($action->id, ['accountService', 'serviceCodeCenter'])) {
		$this->enableCsrfValidation = false;
	 }
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
		$q = json_decode($_POST['serviceQuery']);
		switch($service){
			case "signIn":
				if($_POST['serviceQuery']){
					$sign = $q['asq']; //Authoriation service query
					$type = $q['asqt']; //ASQ Type

					Yii::$app->portalUserService->SignIn->proccess($sign,$type);
				}
				else{ 
					header($_SERVER['SERVER_REQUEST'] . " 405 Method Not Allowed");
					echo 'Query conflict'; 
				}
			break;
			case "signUp":
				if($_POST['serviceQuery']){
					$sign = $q['rsq']; //Registration service query
					$type = $q['rsqt']; //RSQ Type

					Yii::$app->portalUserService->SignUp->proccess($sign,$type);
				}
				else{ 
					header($_SERVER['SERVER_REQUEST'] . " 405 Method Not Allowed");
					echo 'Query conflict'; 
				}
			break;
			case "forgot":
				if($_POST['serviceQuery']){
					$sign = $q['fsq']; //Forgot service query

					Yii::$app->portalUserService->Forgot->proccess($sign);
					
				}
				else{ 
					header($_SERVER['SERVER_REQUEST'] . " 405 Method Not Allowed");
					echo 'Query conflict'; 
				}
			break;
			case "autoAuth":
				if($_POST['serviceQuery']){
					$sign = $q['fsq']; //Forgot service query

					Yii::$app->portalUserService->AutoSignIn->proccess($sign);
					
				}
				else{ 
					header($_SERVER['SERVER_REQUEST'] . " 405 Method Not Allowed");
					echo 'Query conflict'; 
				}
			break;
			case "signOut":
				if(!Yii::$app->user->isGuest){ Yii::$app->portalUserService->SignOut->proccess(); }
				else{ 
					header($_SERVER['SERVER_REQUEST'] . " 405 Method Not Allowed");
					echo 'Service conflict'; 
				}
			break;
			case "getInfo":
				if($_POST['serviceQuery']){
					$userId = $q['investPortalID'];
					$serviceResponse = [];

					$userData = User::find()->where('or',['login' => $userId],['email' => $userId],['phone' => $userId])->all();

					if($userData){
						header('Content-type: application/json; charset=UTF-8');
						
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

						echo Json::encode($serviceResponse);

						
					}
					else{
						header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found");
						echo 'User not found';
					}
					
				}
				else{ 
					header($_SERVER['SERVER_REQUEST'] . " 405 Method Not Allowed");
					echo 'Query conflict'; 
				}
			break;
			default: 
				header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found");
				echo 'Service not found'; 
			break;
		}
	}
	public function actionServiceCodeCenter($service){
		$q = json_decode($_POST['serviceQuery']);
		
		switch($service){
			case "signUp":
				if($_POST['serviceQuery']){
					$sign = $q['rsq']; //Registration service query

					if($sign['service'] === 'Inbox'){
						$query = json_encode(['fsq' => ['svc' => 'SignUp']]);

						$wsInit = new Curl();
						$query = $wsInit->post((!empty($_SERVER['HTTPS'])) ? 'https' : 'http' . '://' . $_SERVER['HTTP_HOST'] ."/accounts/accept/codeGenerator")->setOption(CURLOPT_POSTFIELDS, http_build_query(array('serviceQuery' => $query)));

						$sign['code'] = $query;
					}

					if($sign['service'] === 'Inbox'){ Yii::$app->portalCommunicationService->SMSCode->sendCode('SignUp', $sign['phone'], $sign['code']); }
					else if($sign['service'] === 'Valid'){ Yii::$app->portalCommunicationService->SMSCode->validCode('SignUp', $sign['phone'], $sign['code']); }
					else{ 
						header($_SERVER['SERVER_REQUEST'] . " 403 Forbidden");
						echo 'Operation conflict'; 
					}
				}
				else{ 
					header($_SERVER['SERVER_REQUEST'] . " 405 Method Not Allowed");
					echo 'Query conflict'; 
				}
			break;
			case "forgot":
				if($_POST['serviceQuery']){
					$sign = $q['fsq']; //Forgot service query

					if($sign['service'] === 'Inbox'){
						$query = json_encode(['fsq' => ['svc' => 'Forgot']]);

						$wsInit = new Curl();
						$query = $wsInit->post((!empty($_SERVER['HTTPS'])) ? 'https' : 'http' . '://' . $_SERVER['HTTP_HOST'] ."/accounts/accept/codeGenerator")->setOption(CURLOPT_POSTFIELDS, http_build_query(array('serviceQuery' => $query)));

						$sign['code'] = $query;
					}

					if($sign['service'] === 'Inbox'){ Yii::$app->portalCommunicationService->SMSCode->sendCode('Forgot', $sign['phone']); }
					else if($sign['service'] === 'Valid'){ Yii::$app->portalCommunicationService->SMSCode->validCode('Forgot', $sign['phone'], $sign['code']); }
					else{ 
						header($_SERVER['SERVER_REQUEST'] . " 403 Forbidden");
						echo 'Operation conflict'; 
					}
					
				}
				else{ 
					header($_SERVER['SERVER_REQUEST'] . " 405 Method Not Allowed");
					echo 'Query conflict'; 
				}
			break;
			case "codeGenerator":
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

					echo $newCode;
					
				}
				else{ 
					header($_SERVER['SERVER_REQUEST'] . " 405 Method Not Allowed");
					echo 'Query conflict'; 
				}
			break;
			default: 
				header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found");
				echo 'Service not found'; 
			break;
		}
	}
}
?>
