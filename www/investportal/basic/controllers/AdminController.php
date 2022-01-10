<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;
use yii\web\Url;
use yii\helpers\Json;

use org\apache\hadoop\WebHDFS;
use Clouding\Presto\Presto;

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
		$hadoop = (new WebHDFS('localhost', 50070, 'root', 'namenode', 9870, ''));
		$hive = new Presto();

		$hive->addConnection([
			'host' => 'datacluster.investportal.aplex:8080',
			'catalog' => 'hive',
			'schema' => 'default'
		]);

		$hive->setAsGlobal();

		$serviceResponse = array();
		
		if(!empty($_POST['svcQuery'])){
				$q = Json::decode($_POST['svcQuery']);
				$pm = $q['parameters'];
				$serviceResponse = array();
				
				if($svc == "Filters"){
					$attributeId = strtolower($pm['attribute']);
					
					switch($pm['type']){
						case "intField": 
						case "precentableField": $dataType = 'int'; break;
						case "costField": $dataType = 'float'; break;
						case "smartDatasets": case "photogalleryField": $dataType = 'json'; break;
						case "selectingField": $dataType = 'varchar(255)'; break;
						default: $dataType = 'text'; break;
					}
					$queryHeader = 'ALTER TABLE '. $attributeId;
					$queryBody = '\tADD COLUMN '. $pm['field'] .' '. $dataType;

										    
					if(Presto::query(concat($queryHeader,$queryBody))->get()){ $serviceResponse[] = 'New filter in current attribute table created!'; }
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

												
							if($hadoop->createWithData('/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile, base64_decode($query[1]))){ $responseData[][] = 'Send proccess success!'; }
							else{ $resData[] = 'Bad Data Storage gateway!'; }
												
							$jsonList[]['df'] = $newDataFile;
						}
											
						$jsonResponse = Json::encode(['response' => $jsonList]);

						if($pm['fieldID']){ $sendP = Presto::query('UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID'])->getAssoc(); }
						else{ $sendP = Presto::query('INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $jsonResponse .')')->getAssoc(); }
												
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
												
						if($hadoop->createWithData('/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile, base64_decode($query[1]))){ $serviceResponse[] = 'Send proccess success!'; }
						else{
							\Yii::$app->response->statusCode = 502;
							$serviceResponse[] = 'Bad Data Storage gateway!';
						}

						$jsonReport['ds'] = $newDataFile;
						$jsonResponse = Json::encode(['response' => $jsonReport]);
												
						if($pm['fieldID']){ $sendP = Presto::query('UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID'])->getAssoc(); }
						else{ $sendP = Presto::query('INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $jsonResponse .')')->getAssoc(); }
													
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
													
								if($pm['fieldID']){ $sendP = Presto::query('UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID'])->getAssoc(); }
								else{ $sendP = Presto::query('INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $jsonResponse .')')->getAssoc(); }
														
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
				else if($svc == "Parameters"){
					
					switch($pm['dataParam']){
											case "cost":
												$costQuery = $q['costData'];

												if(strrpos($costQuery['val'],'>') || strrpos($costQuery['val'],'<')){
													if(strrpos($costQuery['val'],'>')){
														$dataArray = explode('>', $costQuery['val']);

														$firstVal = (float) $dataArray[0];
														$doubleVal = (float) $dataArray[1];

														$module = ($firstVal + $doubleVal) / 2;
													}
													else{
														$dataArray = explode('<', $costQuery['val']);

														$firstVal = (float) $dataArray[0];
														$doubleVal = (float) $dataArray[1];

														$module = ($doubleVal - $firstVal) * 2;
													}

													$response = $module;
												}
												else {
													$response = (float) $costQuery['val'];
												}
												
												if($pm['fieldID']){ $dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $response .' WHERE id='. $pm['fieldID']; }
												else{ $dataQuery = 'INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $response .')'; }
												break;
											case "text":
												$textQuery = $q['textData'];
												$responseText = '';

												$fieldParams = [
													'ph' => $textQuery['placeholer'],
													'ml' => $textQuery['maxLength']
												];

												if($fieldParams['ph']){ $responseText .= (string) $fieldParams['ph'] ."\!"; }
												if($fieldParams['ml']){ $responseText .= (int) $fieldParams['ml'] ."\!"; }

												$response = "[". $responseText ."]";
												
												if($pm['fieldID']){ $dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $response .' WHERE id='. $pm['fieldID']; }
												else{ $dataQuery = 'INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $response .')'; }
												break;
											case "selecting":
												$selectingQuery = $q['selectingData'];

												$firstVariant = $selectingQuery[0];
												$doubleVariant = $selectingQuery[1];

												$response = "[". $firstVariant .",". $doubleVariant ."]";
												if($pm['fieldID']){ $dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $response .' WHERE id='. $pm['fieldID']; }
												else{ $dataQuery = 'INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $response .')'; }
												break;
											case "precentable":
												$pQuery = $q['precentableData'];
												
												if(strrpos($pQuery['val'],'>') || strrpos($pQuery['val'],'<')){
													if(strrpos($pQuery['val'],'>')){
														$dataArray = explode('>', $pQuery['val']);

														$firstVal = (int) $dataArray[0];
														$doubleVal = (int) $dataArray[1];

														$module = ($firstVal + $doubleVal) / 2;
													}
													else{
														$dataArray = explode('<', $pQuery['val']);

														$firstVal = (int) $dataArray[0];
														$doubleVal = (int) $dataArray[1];

														$module = ($doubleVal - $firstVal) * 2;
													}

													$response = $module;
												}
												else {
													$response = (int) $pQuery['val'];
												}
												
												if($pm['fieldID']){ $dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $response .' WHERE id='. $pm['fieldID']; }
												else{ $dataQuery = 'INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $response .')'; }
												break;
											default:
												$intQuery = $q['intData'];

												if(strrpos($intQuery['val'],'>') || strrpos($intQuery['val'],'<')){
													if(strrpos($intQuery['val'],'>')){
														$dataArray = explode('>', $intQuery['val']);

														$firstVal = (int) $dataArray[0];
														$doubleVal = (int) $dataArray[1];

														$module = ($firstVal + $doubleVal) / 2;
													}
													else{
														$dataArray = explode('<', $intQuery['val']);

														$firstVal = (int) $dataArray[0];
														$doubleVal = (int) $dataArray[1];

														$module = ($doubleVal - $firstVal) * 2;
													}

													$response = $module;
												}
												else {
													$response = (int) $intQuery['val'];
												}
												
												if($pm['fieldID']){ $dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $response .' WHERE id='. $pm['fieldID']; }
												else{ $dataQuery = 'INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $response .')'; }
												break;
					}
										
					if(Presto::query($dataQuery)->get()){ $serviceResponse[] = 'Parameters send success!'; }
					else{
						\Yii::$app->response->statusCode = 502;
						$serviceResponse[] = 'DBA Service Error';
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
												
						$al = $cache->lrange($key, 0, 1);
												
						for($i = 0; $i < $al; $i++){
							if($attributeId == $al[$i]){ 
								$cache->lrem($key, $i + 1, $attributeId); 
														
								$queryHeader = 'CREATE TABLE IF NOT EXISTS '. $attributeId .' (\n';
								$queryBody = '';
								$queryFooter = ')\nROW FORMAT DELIMITED\nFIELDS TERMINATED BY \';\'';

								for($i = 0; $i < count($pm['metaData']); $i++){
									$fieldName = $pm['metaData'][$i]['name'];
									$fieldType = $pm['metaData'][$i]['type'];

									switch($fieldType){
											case "intField": $dataType = 'int'; break;
											case "costField": $dataType = 'float'; break;
											case "smartDatasets": case "photogalleryField": $dataType = 'json'; break;
											case "selectingField": $dataType = 'varchar(255)'; break;
											default: $dataType = 'text'; break;
									}

																
									$queryBody .= $fieldName .''. $dataType;
								}
															
														
								$dirNew = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeId;

								if(Presto::query(concat($queryHeader,$queryBody,$queryFooter))->get() && $hadoop->mkdirs($dirNew)){ $serviceResponse[] = ['Creator proccess success!', 'New attribute table created!']; }
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
		$hadoop = (new WebHDFS('localhost', 50070, 'root', 'namenode', 9870, ''));
		$hive = new Presto();

		$hive->addConnection([
			'host' => 'datacluster.investportal.aplex:8080',
			'catalog' => 'hive',
			'schema' => 'default'
		]);

		$hive->setAsGlobal();

		$serviceResponse = array();
		
		if(!empty($_POST['svcQuery'])){
				$q = Json::decode($_POST['svcQuery']);
				$pm = $q['parameters'];
				
				if($svc == "Filters"){
					$attributeId = strtolower($pm['attribute']);
					
										
					switch($pm['newType']){
						case "intField": case "precentableField": $dataType = 'int'; break;
						case "costField": $dataType = 'float'; break;
						case "smartDatasets": case "photogalleryField": $dataType = 'json'; break;
						case "selectingField": $dataType = 'varchar(255)'; break;
						default: $dataType = 'text'; break;
				}
										
				$queryHeader = 'ALTER TABLE '. $attributeId;
				$queryBody = '\tRENAME COLUMN '. $pm['field'] .' to '. $pm['newField'];
										
				$updateP = (Presto::query(concat($queryHeader,$queryBody))->get() && Presto::query(concat($queryHeader,'\tALTER COLUMN '. $pm['newField'] .' '. $dataType))->get());
										
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
													
								if(Presto::query('UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID'])->get()){
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
											
						if($hadoop->delete('/FiltersAttributes/data/'. $attributeId .'/*')){ $resData[] = 'Delete proccess success!'; }
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

							if($hadoop->createWithData('/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile, $query[0])){
								$resData[][] = 'Send proccess success!';
							}
							else{ $resData[][] = 'Bad Data Storage gateway!'; }
												
												
							$jsonList['df'][] = $newDataFile;
												
						}
											
						$jsonResponse = Json::encode(['response' => $jsonList]);
											
						if(Presto::query('UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID'])->get()){
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
												
						if($hadoop->delete('/FiltersAttributes/data/'. $attributeId .'/*')){ $resData[] = 'Delete proccess success!'; }
						else{ $resData[] = 'Bad Data Storage gateway!'; }
												
						if(strrpos($query[0], 'application/json')){ $newDataFile = "single.json"; }
						else if(strrpos($query[0], 'application/xml')){ $newDataFile = "single.xml"; }
						else if(strrpos($query[0], 'application/vnd.ms-excel')){ $newDataFile = "single.csv"; }

						$file = fopen( $newDataFile, 'wb' );
						fwrite($file, base64_decode($query[1]));
						fclose($file);

						$jsonReport['ds'] = $newDataFile;
						$jsonResponse = Json::encode(['response' => $jsonReport]);

						if($hadoop->create('/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile)){
							$resData[][] = 'Send proccess success!';
						}
						else{ $resData[][] = 'Bad Data Storage gateway!'; }
												
						if(Presto::query('UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID'])->get()){
							$serviceResponse[] = ['Datasets in current attribute updated!', $resData];
						}
						else{
							\Yii::$app->response->statusCode = 503;
							$serviceResponse[] = 'DBA Service Error!';
						}
					}
				}
				else if($svc == "Parameters"){
					$attributeId = strtolower($pm['attribute']);
									
					
										
					switch($pm['dataParam']){
											case "cost":
												$costQuery = $q['costData'];

												if(strrpos($costQuery['val'],'>') || strrpos($costQuery['val'],'<')){
													if(strrpos($costQuery['val'],'>')){
														$dataArray = explode('>', $costQuery['val']);

														$firstVal = (float) $dataArray[0];
														$doubleVal = (float) $dataArray[1];

														$module = ($firstVal + $doubleVal) / 2;
													}
													else{
														$dataArray = explode('<', $costQuery['val']);

														$firstVal = (float) $dataArray[0];
														$doubleVal = (float) $dataArray[1];

														$module = ($doubleVal - $firstVal) * 2;
													}

													$response = $module;
												}
												else {
													$response = (float) $costQuery['val'];
												}
												
												$dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $response .' WHERE id='. $pm['fieldID'];
												break;
											case "text":
												$textQuery = $q['textData'];
												$responseText = '';

												$fieldParams = [
													'ph' => $textQuery['placeholer'],
													'ml' => $textQuery['maxLength']
												];

												if($fieldParams['ph']){ $responseText .= (string) $fieldParams['ph'] ."\!"; }
												if($fieldParams['ml']){ $responseText .= (int) $fieldParams['ml'] ."\!"; }

												$response = "[". $responseText ."]";
												
												$dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $response .' WHERE id='. $pm['fieldID'];
												break;
											case "selecting":
												$selectingQuery = $q['selectingData'];

												$firstVariant = $selectingQuery[0];
												$doubleVariant = $selectingQuery[1];

												$response = "[". $firstVariant .",". $doubleVariant ."]";
												$dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $response .' WHERE id='. $pm['fieldID'];
												break;
											case "precentable":
												$pQuery = $q['precentableData'];
												
												if(strrpos($pQuery['val'],'>') || strrpos($pQuery['val'],'<')){
													if(strrpos($pQuery['val'],'>')){
														$dataArray = explode('>', $pQuery['val']);

														$firstVal = (int) $dataArray[0];
														$doubleVal = (int) $dataArray[1];

														$module = ($firstVal + $doubleVal) / 2;
													}
													else{
														$dataArray = explode('<', $pQuery['val']);

														$firstVal = (int) $dataArray[0];
														$doubleVal = (int) $dataArray[1];

														$module = ($doubleVal - $firstVal) * 2;
													}

													$response = $module;
												}
												else {
													$response = (int) $pQuery['val'];
												}
												
												$dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $response .' WHERE id='. $pm['fieldID'];
												break;
											default:
												$intQuery = $q['intData'];

												if(strrpos($intQuery['val'],'>') || strrpos($intQuery['val'],'<')){
													if(strrpos($intQuery['val'],'>')){
														$dataArray = explode('>', $intQuery['val']);

														$firstVal = (int) $dataArray[0];
														$doubleVal = (int) $dataArray[1];

														$module = ($firstVal + $doubleVal) / 2;
													}
													else{
														$dataArray = explode('<', $intQuery['val']);

														$firstVal = (int) $dataArray[0];
														$doubleVal = (int) $dataArray[1];

														$module = ($doubleVal - $firstVal) * 2;
													}

													$response = $module;
												}
												else {
													$response = (int) $intQuery['val'];
												}
												
												$dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $response .' WHERE id='. $pm['fieldID'];
												break;
						}
										
					if(Presto::query($dataQuery)->get()){ $serviceResponse[] = 'Parameters update success'; }
					else{
						\Yii::$app->response->statusCode = 502;
						$serviceResponse[] = 'DBA Service Error';
					}	
				}
				else if($svc == "Attribute"){
					$attributeId = strtolower($pm['attribute']);
					$attributeNewId = strtolower($pm['newAttribute']);
					$groupCreate = $pm['group'];
									
					

					$dir = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeId;
					$dirUpdate = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeNewId;
											
					if($hadoop->rename($dir,$dirUpdate)){ $serviceResponse[] = 'Update proccess success!'; }
					else{
						\Yii::$app->response->statusCode = 502;
						$serviceResponse[] = 'Bad Data Storage gateway!';
					}

					if($groupCreate == 'data'){
						$updateP = (Presto::query('ALTER TABLE '. $attributeId .' RENAME TO '. $attributeNewId)->get() && Presto::query('ALTER TABLE '. $attributeNewId .' SET LOCATION "hdfs://73ddd75d66e6:9866/'. $dirUpdate .'"')->get());
												
						if($updateP){ $serviceResponse[] = 'Current attribute table update!'; }
						else{
							\Yii::$app->response->statusCode = 503;
							$serviceResponse[] = 'DBA Service Error!';
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
	public function actionAdminDataFiltersDeleteService($svc){
		$hadoop = (new WebHDFS('localhost', 50070, 'root', 'namenode', 9870, ''));
		$hive = new Presto();

		$hive->addConnection([
			'host' => 'datacluster.investportal.aplex:8080',
			'catalog' => 'hive',
			'schema' => 'default'
		]);

		$hive->setAsGlobal();

		$serviceResponse = array();
		
		if(!empty($_POST['svcQuery'])){
				$q = Json::decode($_POST['svcQuery']);
				$pm = $q['parameters'];
				
				if($svc == "Filters"){
					$attributeId = strtolower($pm['attribute']);
					$queryHeader = 'ALTER TABLE '. $attributeId .' (\n';
					$queryBody = '\tDROP COLUMN '. $pm['field'];
										
					
										
					if(Presto::query(concat($queryHeader,$queryBody))->get()){
						$serviceResponse[] = 'The filter in current attribute table deleted!';
					}
					else{
						\Yii::$app->response->statusCode = 503;
						$serviceResponse[] = 'DBA Service Error!';
					}			
				}
				else if($svc == "Datasets"){
					$attributeId = strtolower($pm['attribute']);
									
					$resData = '';
					
										
					if($hadoop->delete('/FiltersAttributes/data/'. $attributeId .'/*')){ $resData = 'Delete proccess success!'; }
					else{ $resData = 'Bad Data Storage gateway!'; }
										
					if(Presto::query('UPDATE '. $attributeId .' SET '. $pm['field'] .'=\' \' WHERE id='. $pm['fieldID'])->get()){
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
											
						if(Presto::query('UPDATE '. $attributeId .' SET '. $pm['field'] .'=\' \' WHERE id='. $pm['fieldID'])->get()){
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
				else if($svc == "Parameters"){
					$attributeId = strtolower($pm['attribute']);
					
									
					switch($pm['dataParam']){
											case "cost":
												$dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'=\' 0,01\' WHERE id='. $pm['fieldID'];
												$successMessage = 'Current cost parameters is deleted!';
												
												break;
											case "text":
												$dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'=\' \' WHERE id='. $pm['fieldID'];
												$successMessage = 'Current text field parameters is deleted!';
												
												break;
											case "selecting":
												$dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'=\' []\' WHERE id='. $pm['fieldID'];
												$successMessage = 'Current selecting parameters is deleted!';
												break;
											case "precentable":
												$dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'=\' 0\' WHERE id='. $pm['fieldID'];
												$successMessage = 'Current precentable parameters is deleted!';
												break;
											default:
												$dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'=\' 0\' WHERE id='. $pm['fieldID'];
												$successMessage = 'Current integer parameters is deleted!';
												break;
					}
										
					if(Presto::query($dataQuery)->get()){ $serviceResponse[] = $successMessage; }
					else{
						\Yii::$app->response->statusCode = 502;
						$serviceResponse[] = 'DBA Service Error';
					}	
				}
				else if($svc == "Attribute"){
					$attributeId = strtolower($pm['attribute']);
					$groupCreate = $pm['group'];

					$dir = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeId;

					if($groupCreate == 'data'){
						if(Presto::query('DROP TABLE '. $attributeId)->get()){ $serviceResponse[] = 'Current attribute table deleted!'; }
						else{
							\Yii::$app->response->statusCode = 503;
							$serviceResponse[] = 'DBA Service Error!';
						}
					}
											
					if($hadoop->delete($dir,'*')){ $serviceResponse[] = 'Delete proccess success!'; }
					else{
						\Yii::$app->response->statusCode = 502;
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
		$hadoop = (new WebHDFS('localhost', 50070, 'root', 'namenode', 9870, ''));
		$hive = new Presto();

		$hive->addConnection([
			'host' => 'datacluster.investportal.aplex:8080',
			'catalog' => 'hive',
			'schema' => 'default'
		]);

		$hive->setAsGlobal();

		$serviceResponse = array();
		
		if(!empty($_POST['svcQuery'])){
				$q = Json::decode($_POST['svcQuery']);
				$pm = $q['parameters'];
				
				if($svc == "Filters"){
					$currentAttribute = strtolower($pm['attribute']);
					$query = 'SHOW COLUMNS FROM '. $currentAttribute;
					
					$filters = [];

					$result = Presto::query($query)->getAssoc();

					foreach( $result->getResults() as $rowNum => $row ) { $filters[] = $row; }

					$serviceResponse[] = $filters;
										
					if(!$result){
						\Yii::$app->response->statusCode = 503;
						$serviceResponse[] = 'DBA Service Error!';
					}
				}
				else if($svc == "Datasets"){
					$attributeId = strtolower($pm['attribute']);
					if($pm['isSmartDS']){
											$queryFind = 'SELECT '. $pm['DFSField'] .' FROM '. $attributeId;
											
											$datasets = [];

											$result = Presto::query($queryFind)->getAssoc();

											foreach( $result->getResults() as $rowNum => $row ) { $datasets[] = $row['response']; }

											$serviceResponse[] = $datasets;

											if(!$result){
												\Yii::$app->response->statusCode = 503;
												$serviceResponse[] = 'DBA Service Error!';
											}
					}
					else{
						$queryFind = 'SELECT '. $pm['datafield'] .' FROM '. $attributeId;
						
						$ds = [];


						$result = Presto::query($queryFind)->getAssoc();

						foreach( $result->getResults() as $rowNum => $row ) { $ds[] = $row['response']; }

						$serviceResponse[] = $ds;

						if(!$result){
							\Yii::$app->response->statusCode = 503;
							$serviceResponse[] = 'DBA Service Error!';
						}	
					}	
			}
			else if($svc == "Photogallery"){
				
				if($q['photogallery']){
					$attributeId = strtolower($pm['attribute']);
					$queryFind = 'SELECT '. $pm['photofield'] .' FROM '. $attributeId;
					$datasets = [];

					$result = Presto::query($queryFind)->getAssoc();

					foreach( $result->getResults() as $rowNum => $row ) { $datasets[] = $row['response']; }

					$serviceResponse[] = $datasets;
												
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
			else if($svc == "Parameters"){
				$attributeId = strtolower($pm['attribute']);
				
				$tables = [];
				switch($pm['dataParam']){
											case "cost": $dataQuery = 'SELECT '. $pm['costQuery'] .' FROM '. $attributeId; break;
											case "text": $dataQuery = 'SELECT '. $pm['textQuery'] .' FROM '. $attributeId; break;
											case "selecting": $dataQuery = 'SELECT '. $pm['selectingQuery'] .' FROM '. $attributeId; break;
											case "precentable": $dataQuery = 'SELECT '. $pm['precentableQuery'] .' FROM '. $attributeId; break;
											case "smartDataset": case "photogallery": $dataQuery = 'SELECT '. $pm['dQuery'] .' FROM '. $attributeId; break;
											default: $dataQuery = 'SELECT '. $pm['intQuery'] .' FROM '. $attributeId; break;
				}

				$result = Presto::query($dataQuery)->getAssoc();

				foreach( $result->getResults() as $rowNum => $row ) { $tables[] = $row; }

				$serviceResponse[] = $tables;

				if(!$result){
					\Yii::$app->response->statusCode = 502;
					$serviceResponse[] = 'DBA Service Error!';	
				}
			}
			else if($svc == "TableColumns"){
				$attributeId = strtolower($pm['attribute']);
				$query = 'SELECT COUNT(*) as columncount FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = \''. $attributeId .'\'';
				
				$tables = [];
				$result = Presto::query($query)->getAssoc();
					

				if($result){ foreach( $result->getResults() as $rowNum => $row ) { $tables[] = $row; } }
				else{ $tables[] = [0]; }

				$serviceResponse[] = $tables;
										
				if(!$result){
					\Yii::$app->response->statusCode = 503;
					$serviceResponse[] = 'DBA Service Error!';
				}
			}
			else{
				\Yii::$app->response->statusCode = 404;
				$serviceResponse[] = "Not command found!";	
			}
		}
		else if($svc == "Attributes"){
				$query = 'SHOW TABLES';
				$tables = [];
		
				$result = Presto::query($query)->getAssoc();
				$ready = Yii::$app->redis->executeCommand('lrange',['readyAttribute', '0 -2']);
					
				$df = array_merge($ready, $result);
					

				foreach( $df as $rowNum => $row ) { $tables[] = $row; }

				$serviceResponse[] = $tables;
											
				if(!$result){
					\Yii::$app->response->statusCode = 503;
					$serviceResponse[] = 'DBA Service Error!';
				}
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
