<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;
use yii\web\Url;
use yii\helpers\Json;
use linslin\yii2\curl;
use org\apache\hadoop\WebHDFS;

use app\models\ObjectAttribute;
use app\models\ObjectFilter;

class AdminController extends Controller{
	public function beforeAction($action) { 
		$this->enableCsrfValidation = false; 
		return parent::beforeAction($action); 
	}
    public function actionIndex(){
		$sessionData = Yii::$app->session;
		if(!$sessionData->isActive && !$sessionData->get('adminUser')){ header('Location: /admin/auth'); }
		else{
			$pgUI = '';
			$this->layout = "adminPortal";
			$this->view->registerCssFile("/css/admin/admin.css");

			if($_GET['svc']){
				$service = $_GET['svc'];

				if($service == "dataManagment"){
					if($_GET['subSVC']){
						 switch($_GET['subSVC']){
							 case "filters": 
								$service = 'dataFilters'; 
								$this->view->registerJsFile("/js/react/admin/addons/validParams.js", ['position' => View::POS_END]);
							break;
							 default: $service = 'dataAttributes'; break;
						 }
					 }
				}

				$pgUI = $service;
			}
			else{ $pgUI = 'dashboard'; }

			$this->view->registerCssFile("/css/admin/pages/". $pgUI .".css");
			

			return $this->render('admin',['pgUI' => $pgUI]);
		}
	}
	public function actionAuth(){
		$sessionData = Yii::$app->session;
		if($sessionData->isActive && $sessionData->get('adminUser')){ header('Location: '. Url::to(['admin/index'])); }
		else{
			$this->layout = "adminAuth";
			$this->view->registerCssFile("/css/admin/auth.css");
			
			if($_POST['username']){
				$u = $_POST['username'];
				$p = $_POST['pass'];
				
				$query = ['admin' => $u, 'password' => $p];
				
				Yii::$app->asLogin->proccess($query);
			}
		}
	
		return $this->render('auth');
	}
	public function actionAdminDataFiltersSendService($svc){
		$hadoop = [new ObjectFilter, ObjectFilter::find()];
		$hive = [new ObjectAttribute, ObjectAttribute::find()];
		
		$storage = (new WebHDFS('localhost', 50070, 'root', 'namenode', 9870, ''));

		$serviceResponse = array();
		
		if(!empty($_POST['svcQuery'])){
				$q = Json::decode($_POST['svcQuery']);
				$pm = $q['parameters'];
				$serviceResponse = array();
				
				if($svc == "Filters"){
					
					$attributeId = strtolower($pm['attribute']);
					
					$isDataState = (new curl\Curl)->post(((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] .'/admin/api/dataServices/filters/notEmptyAttribute/show');
					$attributeGenerator = (new curl\Curl)->post(((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] .'/admin/api/dataServices/filters/Attribute/send');
					
					$microQuery = [
						'generatorQuery' => [
							'parameters' => [ 
								'attribute' => $attributeId,
								'group' => 'data' 
							]
						],
						'validQuery' => [
							'parameters' => [ 'attribute' => $attributeId ]
						]
					];
					
					$idsSend = $isDataState->setOption(CURLOPT_POSTFIELDS, http_build_query(['svcQuery' => JSON::encode($microQuery['validQuery'])]));
					
					$resValid = JSON::decode($idsSend);
					
					if($resValid['avabillityData'] == 1){ $agSend = $attributeGenerator->setOption(CURLOPT_POSTFIELDS, http_build_query(['svcQuery' => JSON::encode($microQuery['generatorQuery'])])); }
					
					
					if((!$idsSend && !$agSend) || !$idsSend || !$agSend){
						\Yii::$app->response->statusCode = 502;
						$serviceResponse[] = 'Operation service error!';
					}
					
					
					switch($pm['type']){
						case "int": $dataType = 'int'; break;
						case "precentable": $dataType = 'precentable'; break;
						case "cost": $dataType = 'cost'; break;
						case "smartDatasets": $dataType = 'smartDatasets'; break;
						case "photogallery": $dataType = 'photogallery'; break;
						case "selecting": $dataType = 'selecting'; break;
						default: $dataType = 'text'; break;
					}
					
					$hadoop[0]->name = $attributeId;
					$hadoop[0]->field = $pm['field'];
					$hadoop[0]->type = $dataType;
					
					if($hadoop[0]->save()){ $serviceResponse[] = 'New filter in current attribute table created!'; }
					else{
						\Yii::$app->response->statusCode = 503;
						$serviceResponse[] = 'DBA Service Error!';
					}
					
				}
				else if($svc == "Datasets"){
					$attributeId = strtolower($pm['attribute']);
									
					if($pm['isSmartDS']){
						//При наличии нескольких датасетов и не только...

						$datasets = JSON::Decode($pm['smartDS']);
						
						$resData = [];
						$jsonList = [];

						for($i = 0; $i < count($datasets['file']); $i++){
							$sendCurData = $datasets[$i]['file'];
							$query = explode(',', $sendCurData);

							if(strrpos($query[0], 'application/json')){ $newDataFile = $i .".json"; }
							else if(strrpos($query[0], 'application/xml')){ $newDataFile = $i .".xml"; }
							else if(strrpos($query[0], 'application/vnd.ms-excel')){ $newDataFile = $i .".csv"; }

												
							if($storage->createWithData('/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile, base64_decode($query[1]))){ $responseData[][] = 'Send proccess success!'; }
							else{ $resData[] = 'Bad Data Storage gateway!'; }
												
							$jsonList[]['df'] = $newDataFile;
						}
											
						$jsonResponse = Json::encode(['response' => $jsonList]);

						$sendP = $hadoop[0]->updateAll(['parameters' => $jsonResponse],['name' => $attributeId, 'field' => $pm['field'], 'type' => 'photogallery']);
												
						if($sendP){ $serviceResponse[] = ['Datasets in current attribute creating!', $resData]; }
						else{
							\Yii::$app->response->statusCode = 503;
							$serviceResponse[] = 'DBA Service Error!';
						}
					}
					else{
						$dataset = $pm['dataset'];
						$query = explode(',', $dataset);
												
						
						$jsonReport = [];
												
						if(strrpos($query[0], 'application/json')){ $newDataFile = "single.json"; }
						else if(strrpos($query[0], 'application/xml')){ $newDataFile = "single.xml"; }
						else if(strrpos($query[0], 'application/vnd.ms-excel')){ $newDataFile = "single.csv"; }
												
						if($storage->createWithData('/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile, base64_decode($query[1]))){ $serviceResponse[] = 'Send proccess success!'; }
						else{
							\Yii::$app->response->statusCode = 502;
							$serviceResponse[] = 'Bad Data Storage gateway!';
						}

						$jsonReport['ds'] = $newDataFile;
						$jsonResponse = Json::encode(['response' => $jsonReport]);
												
						$sendP = $hadoop[0]->updateAll(['parameters' => $jsonResponse],['name' => $attributeId, 'field' => $pm['field'], 'type' => 'photogallery']);
													
						if($sendP){ $serviceResponse[] = 'Datasets in current attribute creating!'; }
						else{
							\Yii::$app->response->statusCode = 503;
							$serviceResponse[] = 'DBA Service Error!';
						}			
					}
				}
				else if($svc == "Photogallery"){
					if($q['photogallery']){
						$attributeId = strtolower($pm['attribute']);
						$formats = $q['format'];
						$isPhotoCount = ($q['count'] > 4 || $q['count'] == 4) ? TRUE : FALSE;
											
						
											
						switch($isPhotoCount){
							case FALSE:
								\Yii::$app->response->statusCode = 415;
								$serviceResponse[] = 'Invalid number of photos (the correct minimum value is 4)';
								break;
							default:
								$jsonReport = ['imageFormats' => $formats, 'imageCounts' => $q['count']];
								$jsonResponse = ['response' => $jsonReport];
													
								$sendP = $hadoop[0]->updateAll(['parameters' => $jsonResponse],['name' => $attributeId, 'field' => $pm['field'], 'type' => 'photogallery']);
														
								if($sendP){ $serviceResponse[] = 'New photogallery in current attribute table creating!'; }
								else{
									\Yii::$app->response->statusCode = 503;
									$serviceResponse[] = 'DBA Service Error!';
								}
							break;
						}	
					}
					else{
						\Yii::$app->response->statusCode = 404;
						$serviceResponse[] = 'Query not found!';
					}
				}
				else if($svc == "Attribute"){
					$attributeId = strtolower($pm['attribute']);
					
					$groupCreate = $pm['group'];
					$key = 'readyAttribute';
										
										
					if($groupCreate == 'meta'){
						
						$send = Yii::$app->redis->executeCommand('rpush', [$key, $attributeId]);
											 
						if(!$send){
							\Yii::$app->response->statusCode = 503;
							$serviceResponse[] = 'Redis Service Error!';
						}
						else{ $serviceResponse[] = 'Ready proccess success!'; }			
					}
					else if($groupCreate == 'data'){
						$cache = Yii::$app->cache->redis;						
						$al = Yii::$app->redis->executeCommand('lrange', ['readyAttribute', -100, 100]);
												
						for($i = 0; $i < $al; $i++){
							if($attributeId == $al[$i]){ 
								$cache->lrem($key, $i + 1, $attributeId);
															
														
								$dirNew = 'user/ip-data/FiltersAttributes/' . $attributeId;
								
								$hive[0]->name = $attributeId;

								if($storage->mkdirs($dirNew) && $hive[0]->save()){ $serviceResponse = ['Creator proccess success!', 'New attribute table created!']; }
								else{
									\Yii::$app->response->statusCode = 502;
									$serviceResponse[] = 'Services gateway!';
								}
							}
						}
												
					}
										
				}
				else{
					\Yii::$app->response->statusCode = 404;
					$serviceResponse[] = "Not command found!";
				}
		}
		else{
			\Yii::$app->response->statusCode = 405;
			$serviceResponse[] = "Query not found!";
		}
		
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $serviceResponse;
		
	}
	public function actionAdminDataFiltersUpdateService($svc){
		$hadoop = [new ObjectFilter, ObjectFilter::find()];
		$hive = [new ObjectAttribute, ObjectAttribute::find()];
		
		$storage = (new WebHDFS('localhost', 50070, 'root', 'namenode', 9870, ''));
		$serviceResponse = array();
		
		if(!empty($_POST['svcQuery'])){
				$q = Json::decode($_POST['svcQuery']);
				$pm = $q['parameters'];
				
				if($svc == "Filters"){
					$attributeId = strtolower($pm['attribute']);
					
										
					switch($pm['type']){
						case "int": $dataType = 'int'; break;
						case "precentable": $dataType = 'precentable'; break;
						case "cost": $dataType = 'cost'; break;
						case "smartDatasets": $dataType = 'smartDatasets'; break;
						case "photogallery": $dataType = 'photogallery'; break;
						case "selecting": $dataType = 'selecting'; break;
						default: $dataType = 'text'; break;
					}
										
				
										
					$updateP = $hadoop[0]->updateAll(['type' => $dataType], ['name' => $attributeId, 'field' => $pm['field']]);
											
					if($updateP){ $serviceResponse[] = 'The filter in current attribute table updated!'; }
					else{
						\Yii::$app->response->statusCode = 503;
						$serviceResponse[] = 'DBA Service Error!';
					}	
				}
				else if($svc == "Photogallery"){
					if($q['photogallery']){
						$attributeId = strtolower($pm['attribute']);
						$formats = $q['format'];
						$isPhotoCount = ($q['count'] > 4 || $q['count'] == 4) ? TRUE : FALSE;
											
						

						switch($isPhotoCount){
							case FALSE:
								\Yii::$app->response->statusCode = 415;
								$serviceResponse[] = 'Invalid number of photos (the correct minimum value is 4)';
								break;
							default:
								$jsonReport = ['imageFormats' => $formats, 'imageCounts' => $q['count']];
								$jsonResponse = ['response' => $jsonReport];
													
								if($hadoop[0]->updateAll(['parameters' => $jsonResponse],['name' => $attributeId, 'field' => $pm['field'], 'type' => 'photogallery'])){
									$serviceResponse[] = 'Photogallery in current attribute updated!';
								}
								else{
									\Yii::$app->response->statusCode = 503;
									$serviceResponse[] = 'DBA Service Error!';
								}
								break;
						}
					}
					else{
						\Yii::$app->response->statusCode = 404;
						$serviceResponse[] = 'Query not found!';
					}
				}
				else if($svc == "Datasets"){
					$attributeId = strtolower($pm['attribute']);
									
					if($pm['isSmartDS']){
						//При наличии нескольких датасетов и не только...

						$datasets = JSON::decode($pm['smartDS']);
						
						$resData = [];
						$jsonList = [];
											
						if($storage->delete('/FiltersAttributes/data/'. $attributeId .'/*')){ $resData[] = 'Delete proccess success!'; }
						else{
							\Yii::$app->response->statusCode = 502;
							$resData[] = 'Bad Data Storage gateway!';
						}
											
						for($i = 0; $i < count($datasets['file']); $i++){
							$sendCurData = $datasets[$i]['file'];
							$query = explode(',', $sendCurData);
												
												
												
							if(strrpos($query[0], 'application/json')){ $newDataFile = $i .".json"; }
							else if(strrpos($query[0], 'application/xml')){ $newDataFile = $i .".xml"; }
							else if(strrpos($query[0], 'application/vnd.ms-excel')){ $newDataFile = $i .".csv"; }

							if($storage->createWithData('/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile, $query[0])){
								$resData[][] = 'Send proccess success!';
							}
							else{ $resData[][] = 'Bad Data Storage gateway!'; }
												
												
							$jsonList['df'][] = $newDataFile;
												
						}
											
						$jsonResponse = Json::encode(['response' => $jsonList]);
											
						if($hadoop[0]->updateAll(['parameters' => $jsonResponse],['name' => $attributeId, 'field' => $pm['field'], 'type' => 'photogallery'])){
							$serviceResponse[] = ['Datasets in current attribute updated!', $resData];
						}
						else{
							\Yii::$app->response->statusCode = 503;
							$serviceResponse[] = 'DBA Service Error!';
						}		
					}
					else{
						$dataset = $pm['dataset'];
						
						$resData = [];
						$jsonReport = [];
												
						$query = explode(',', $dataset);
												
						if($storage->delete('/FiltersAttributes/data/'. $attributeId .'/*')){ $resData[] = 'Delete proccess success!'; }
						else{ $resData[] = 'Bad Data Storage gateway!'; }
												
						if(strrpos($query[0], 'application/json')){ $newDataFile = "single.json"; }
						else if(strrpos($query[0], 'application/xml')){ $newDataFile = "single.xml"; }
						else if(strrpos($query[0], 'application/vnd.ms-excel')){ $newDataFile = "single.csv"; }

						$file = fopen( $newDataFile, 'wb' );
						fwrite($file, base64_decode($query[1]));
						fclose($file);

						$jsonReport['ds'] = $newDataFile;
						$jsonResponse = Json::encode(['response' => $jsonReport]);

						if($storage->create('/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile)){
							$resData[][] = 'Send proccess success!';
						}
						else{ $resData[][] = 'Bad Data Storage gateway!'; }
												
						if($hadoop[0]->updateAll(['parameters' => $jsonResponse],['name' => $attributeId, 'field' => $pm['field'], 'type' => 'photogallery'])){
							$serviceResponse[] = ['Datasets in current attribute updated!', $resData];
						}
						else{
							\Yii::$app->response->statusCode = 503;
							$serviceResponse[] = 'DBA Service Error!';
						}
					}
				}
				else if($svc == "Attribute"){
					$attributeId = strtolower($pm['attribute']);
					$attributeNewId = strtolower($pm['newAttribute']);
					
					
					$dir = 'user/ip-data/FiltersAttributes/'. $attributeId;
					$dirUpdate = 'user/ip-data/FiltersAttributes/'. $attributeNewId;
											
					if($storage->rename($dir,$dirUpdate)){ 
						$serviceResponse[] = 'Update proccess success!'; 
						$serviceResponse[] = 'Current attribute table update!';
					}
					else{
						\Yii::$app->response->statusCode = 502;
						$serviceResponse[] = 'Bad Data Storage gateway!';
						$serviceResponse[] = 'DBA Service Error!';
					}
									
				}
				else{
					\Yii::$app->response->statusCode = 404;
					$serviceResponse[] = "Not command found!";
				}
								
		}
		else{
			\Yii::$app->response->statusCode = 405;
			$serviceResponse[] = "Query not found!";
		}
		
		
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $serviceResponse;
	}
	public function actionAdminDataFiltersDeleteService($svc){
		$hadoop = [new ObjectFilter, ObjectFilter::find()];
		$hive = [new ObjectAttribute, ObjectAttribute::find()];
		
		$storage = (new WebHDFS('localhost', 50070, 'root', 'namenode', 9870, ''));

		$serviceResponse = array();
		
		if(!empty($_POST['svcQuery'])){
				$q = Json::decode($_POST['svcQuery']);
				$pm = $q['parameters'];
				
				if($svc == "Datasets"){
					$attributeId = strtolower($pm['attribute']);
									
					$resData = '';
					
										
					if($storage->delete('/FiltersAttributes/data/'. $attributeId .'/*')){ $resData = 'Delete proccess success!'; }
					else{ $resData = 'Bad Data Storage gateway!'; }
										
					if($hadoop[0]->updateAll(['parameter' => ''],['type' => 'smartDatasets', 'field' => $pm['field'], 'name' => $attributeId])){
						$serviceResponse[] = ['Datasets in current attribute deleted!', $resData];
					}
					else{
						\Yii::$app->response->statusCode = 502;
						$serviceResponse[] = 'DBA Service Error';
					}				
				}
				else if($svc == "Photogallery"){
					if($q['photogallery']){
						$attributeId = strtolower($pm['attribute']);			
											
						if($hadoop[0]->updateAll(['parameter' => ''],['type' => 'photogallery', 'field' => $pm['field'], 'name' => $attributeId])){
							$serviceResponse[] = 'Photogallery in current attribute deleted!';
						}
						else{
							\Yii::$app->response->statusCode = 502;
							$serviceResponse[] = 'DBA Service Error';
						}
					}
					else{
						\Yii::$app->response->statusCode = 404;
						$serviceResponse[] = 'Query not found!';
					}	
				}
				else if($svc == "Attribute"){
					$attributeId = strtolower($pm['attribute']);

					$dir = 'user/ip-data/FiltersAttributes/' . $attributeId;

					if($hadoop[0]->deleteAll(['name' => $attributeId]) && $hive[0]->deleteAll(['name' => $attributeId]) && $storage->delete($dir,'*')){ 
						$serviceResponse[] = 'Current attribute table deleted!'; 
						$serviceResponse[] = 'Delete proccess success!';
					}
					else{
							\Yii::$app->response->statusCode = 502;
							$serviceResponse[] = 'DBA Service Error!';
							$serviceResponse[] = 'Bad Data Storage gateway!';
					}	
				}
				else{
					\Yii::$app->response->statusCode = 404;
					$serviceResponse[] = "Not command found!";
				}
		}
		else{
			\Yii::$app->response->statusCode = 405;
			$serviceResponse[] = "Query not found!";
		}
		
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $serviceResponse;
	}
	public function actionAdminDataFiltersResService($svc){
		$hadoop = [new ObjectFilter, ObjectFilter::find()];
		$hive = [new ObjectAttribute, ObjectAttribute::find()];

		$storage = (new WebHDFS('localhost', 50070, 'root', 'namenode', 9870, ''));
		$serviceResponse = array();
		
		if(!empty($_POST['svcQuery'])){
				$q = Json::decode($_POST['svcQuery']);
				$pm = $q['parameters'];
				
				if($svc == "Filters"){
					$currentAttribute = strtolower($pm['attribute']);
					$query = ['name' => $currentAttribute];
					
					$filters = [];

					$result = $hadoop[1]->where($query)->all();

					foreach( $result as $rowNum => $row ) { $filters[] = $row; }

					$serviceResponse = $filters;
										
					if(!$result){
						\Yii::$app->response->statusCode = 503;
						$serviceResponse[] = 'DBA Service Error!';
					}
				}
				else if($svc == "Datasets"){
					$attributeId = strtolower($pm['attribute']);
					if($pm['isSmartDS']){
											$queryFind = ['field' => $pm['DFSField'], 'type' => 'smartDatasets', 'name' => $attributeId];
											
											$datasets = [];

											$result = $hadoop[0]->where($queryFind)->all();

											foreach( $result as $rowNum => $row ) { $datasets[] = $row; }

											$serviceResponse[] = $datasets;

											if(!$result){
												\Yii::$app->response->statusCode = 503;
												$serviceResponse[] = 'DBA Service Error!';
											}
					}
					else{
						$queryFind = ['field' => $pm['datafield'], 'type' => 'smartDataset', 'name' => $attributeId];
						
						$ds = [];


						$result = $hadoop[0]->where($queryFind)->all();

						foreach( $result as $rowNum => $row ) { $ds[] = $row; }

						$serviceResponse = $ds;

						if(!$result){
							\Yii::$app->response->statusCode = 503;
							$serviceResponse[] = 'DBA Service Error!';
						}	
					}	
			}
			else if($svc == "Photogallery"){
				
				if($q['photogallery']){
					$attributeId = strtolower($pm['attribute']);
					$queryFind = ['field' => $pm['photofield'], 'type' => 'photogallery', 'name' => $attributeId];
					$datasets = [];

					$result = $hadoop[0]->where($queryFind)->all();

					foreach( $result as $rowNum => $row ) { $datasets[] = $row; }

					$serviceResponse = $datasets;
												
					if(!$result){
						\Yii::$app->response->statusCode = 503;
						$serviceResponse[] = 'DBA Service Error!';
					}
				}
				else{
					\Yii::$app->response->statusCode = 404;
					$serviceResponse[] = 'Query not found!';
				}	
			}
			else if($svc = "notEmptyAttribute"){
				$attributeId = strtolower($pm['attribute']);
				
				$isFilters = ObjectFilter::findAll(['name' => $attributeId]);
				
				if(!$isFilters){ $state = 1; }
				else{ $state = 0; }
				
				$serviceResponse['avabillityData'] = $state;
			}	
			else{
				\Yii::$app->response->statusCode = 404;
				$serviceResponse[] = "Not command found!";	
			}
		}
		else if($svc == "Attributes"){
				$tables = [];
		
				$result = $hadoop[1]->all();
				$ready = $hive[1]->all();
				$al = Yii::$app->redis->executeCommand('lrange', ['readyAttribute', -100, 100]);
					
				$df = array_merge($ready, $result, $al);

				foreach( $df as $rowNum => $row ) { $tables[] = $row; }

				$serviceResponse = $tables;
		}
		else{
			\Yii::$app->response->statusCode = 405;
			$serviceResponse[] = "Query not found!";
		}
		
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $serviceResponse;
		
	}
}
?>
