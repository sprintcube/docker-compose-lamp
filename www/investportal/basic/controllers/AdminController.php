<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;
use yii\web\Url;
use yii\helpers\Json;

use org\apache\hadoop\WebHDFS;

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
	public function actionAdminService($svc, $subSVC){
		$serviceResponse = NULL;
		$statusServiceCode = '200 OK';
		$hadoop = (new WebHDFS('localhost', 50070, 'root', 'namenode', 9870, ''));

		$hive =  new \Ytake\PrestoClient\ClientSession('http://localhost:8080/', 'hive');
		
		if($svc == 'dataServices'){
				if($subSVC == "filters"){
					if($_POST['svcQuery']){
						$q = Json::decode($_POST['svcQuery']);
						$pm = $q['parameters'];

						
						if($q['command'] == 0){
								//Команда добавления данных в текущий фрагмент
								
								if($q['command']['subCMD'] == "sendFilters"){
											$attributeId = lowercase($pm['attribute']);
											$responseData = '';
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

										    
											if((new \Ytake\PrestoClient\StatementClient($hive, concat($queryHeader,$queryBody)))->execute()){ $responseData = 'New filter in current attribute table created!'; }
											else{
												$resCode = '503 Service Unavailable';
												$responseData = 'DBA Service Error!';
											}
											
											
											$statusServiceCode = $resCode;
											$serviceResponse .= $responseData;
								}
								if($q['command']['subCMD'] == "sendDatasets"){
									$attributeId = lowercase($pm['attribute']);
									
									if($pm['isSmartDS']){
											//При наличии нескольких датасетов и не только...

											$datasets = JSON::Decode($pm['smartDS']);
											$responseData = [];
											$resData = [];
											$jsonList = [];

											for($i = 0; $i < count($datasets['file']); $i++){
												$sendCurData = $datasets[$i]['file'];
												$query = explode(',', $sendCurData);

												if(strrpos($query[0], 'application/json')){ $newDataFile = $i .".json"; }
												else if(strrpos($query[0], 'application/xml')){ $newDataFile = $i .".xml"; }
												else if(strrpos($query[0], 'application/vnd.ms-excel')){ $newDataFile = $i .".csv"; }

												
												if($hadoop->createWithData('/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile, base64_decode($query[1]))){ $responseData[][] = 'Send proccess success!'; }
												else{
													$resData[] = 'Bad Data Storage gateway!';
												}
												
												$jsonList[]['df'] = $newDataFile;
											}
											
											$jsonResponse = Json::encode(['response' => $jsonList]);

											if($pm['fieldID']){ $sendP = (new \Ytake\PrestoClient\StatementClient($hive, 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID']))->execute(); }
											else{ $sendP = (new \Ytake\PrestoClient\StatementClient($hive, 'INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $jsonResponse .')'))->execute(); }
												
											if($sendP){ $responseData = ['Datasets in current attribute creating!', $resData]; }
											else{
												$resCode = '503 Service Unavailable';
												$responseData[] = 'DBA Service Error!';
											}
											
											$statusServiceCode = $resCode;
											$serviceResponse .= $responseData;
									}
									else{
											    $dataset = $pm['dataset'];
												$query = explode(',', $dataset);
												
												$responseData = '';
												$jsonReport = [];
												
												if(strrpos($query[0], 'application/json')){ $newDataFile = "single.json"; }
												else if(strrpos($query[0], 'application/xml')){ $newDataFile = "single.xml"; }
												else if(strrpos($query[0], 'application/vnd.ms-excel')){ $newDataFile = "single.csv"; }
												
												if($hadoop->createWithData('/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile, base64_decode($query[1]))){ $responseData[] = 'Send proccess success!'; }
												else{
													$resCode = '502 Bad Gateway';
													$responseData[] = 'Bad Data Storage gateway!';
												}

												$jsonReport['ds'] = $newDataFile;
												$jsonResponse = Json::encode(['response' => $jsonReport]);
												
												if($pm['fieldID']){ $sendP = (new \Ytake\PrestoClient\StatementClient($hive, 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID']))->execute(); }
												else{ $sendP = (new \Ytake\PrestoClient\StatementClient($hive, 'INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $jsonResponse .')'))->execute(); }
													
												if($sendP){ $responseData = 'Datasets in current attribute creating!'; }
												else{
													$resCode = '503 Service Unavailable';
													$responseData = 'DBA Service Error!';
												}
												
												$statusServiceCode = $resCode;
												$serviceResponse .= $responseData;
									}
								}
								if($q['command']['subCMD'] == "sendPhotogallery"){
									if($q['photogallery']){
											$attributeId = lowercase($pm['attribute']);
											$formats = $q['format'];
											$isPhotoCount = ($q['count'] > 4 || $q['count'] == 4) ? TRUE : FALSE;
											
											$responseData = '';
											
											switch($isPhotoCount){
												case FALSE:
													$resCode = 415;
													$responseData = 'Invalid number of photos (the correct minimum value is 4)';
													break;
												default:
													$jsonReport = ['imageFormats' => $formats, 'imageCounts' => $q['count']];
													$jsonResponse = ['response' => $jsonReport];
													
													if($pm['fieldID']){ $sendP = (new \Ytake\PrestoClient\StatementClient($hive, 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID']))->execute(); }
													else{ $sendP = (new \Ytake\PrestoClient\StatementClient($hive, 'INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $jsonResponse .')'))->execute(); }
														
													if($sendP){ $responseData = 'New photogallery in current attribute table creating!'; }
													else{
														$resCode = '503 Service Unavailable';
														$responseData = 'DBA Service Error!';
													}
													break;
											}
											
											$statusServiceCode = $resCode;
											$serviceResponse .= $responseData;
									}
									else{
											$resCode = '404 Not Found';
											$responseData[] = 'Query not found!';
											
											$statusServiceCode = $resCode;
											$serviceResponse .= $responseData;
									}
								}
								if($q['command']['subCMD'] == "sendParameters"){
									$responseData = '';
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
										
									if((new \Ytake\PrestoClient\StatementClient($hive, $dataQuery))->execute()){ $responseData = 'Parameters send success!'; }
									else{
											$resCode = '502 Bad Gateway';
											$responseData = 'DBA Service Error';
									}
									
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
								
								if($q['command']['subCMD'] == "sendAttribute"){
										$attributeId = lowercase($pm['attribute']);
										$responseData = '';
										$groupCreate = $pm['group'];
										
										$cache = Yii::$app->cacheRedis;
										$key = 'readyAttribute';
										
										
										if($groupCreate == 'meta'){
											
											 $send = $cache->lpush($key, $attributeId);
											 
											 if(!$send){
												 $resCode = '503 Service Unavailable';
												 $responseData = 'Redis Service Error!';
											 }
											 else{
												 $responseData = 'Ready proccess success!';
											 } 
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

														if((new \Ytake\PrestoClient\StatementClient($hive, concat($queryHeader,$queryBody,$queryFooter)))->execute() && $hadoop->mkdirs($dirNew)){ $responseData = ['Creator proccess success!', 'New attribute table created!']; }
														else{
															$resCode = '502 Bad Gateway';
															$responseData = 'Services gateway!';
														}
													}
												}
												
										}
										$statusServiceCode = $resCode;
										$serviceResponse .= $responseData;
								}
								else{
									$resCode = '404 Not Found';
									$responseData = "Not subcommand found!";
									
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
						}
						else if($q['command'] == 1){
								//Команда обновления данных в текущем фрагменте
								
								if($q['command']['subCMD'] == "updateFilters"){
									$attributeId = lowercase($pm['attribute']);
									$responseData = '';
										
									switch($pm['newType']){
												case "intField": case "precentableField": $dataType = 'int'; break;
												case "costField": $dataType = 'float'; break;
												case "smartDatasets": case "photogalleryField": $dataType = 'json'; break;
												case "selectingField": $dataType = 'varchar(255)'; break;
												default: $dataType = 'text'; break;
									}
										
									$queryHeader = 'ALTER TABLE '. $attributeId;
									$queryBody = '\tRENAME COLUMN '. $pm['field'] .' to '. $pm['newField'];
										
									$updateP = ((new \Ytake\PrestoClient\StatementClient($hive, concat($queryHeader,$queryBody)))->execute() && (new \Ytake\PrestoClient\StatementClient($hive, concat($queryHeader,'\tALTER COLUMN '. $pm['newField'] .' '. $dataType)))->execute());
										
									if($updateP){ $responseData = 'The filter in current attribute table updated!'; }
									else{
											$resCode = '503 Service Unavailable';
											$responseData = 'DBA Service Error!';
									}
									
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
								if($q['command']['subCMD'] == "updatePhotogallery"){
									if($q['photogallery']){
											$attributeId = lowercase($pm['attribute']);
											$formats = $q['format'];
											$isPhotoCount = ($q['count'] > 4 || $q['count'] == 4) ? TRUE : FALSE;
											
											$responseData = '';

											switch($isPhotoCount){
												case FALSE:
													$statusServiceCode = 415;
													$responseData = 'Invalid number of photos (the correct minimum value is 4)';
													break;
												default:
													$jsonReport = ['imageFormats' => $formats, 'imageCounts' => $q['count']];
													$jsonResponse = ['response' => $jsonReport];
													
													if((new \Ytake\PrestoClient\StatementClient($hive, 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID']))->execute()){
														$responseData = 'Photogallery in current attribute updated!';
													}
													else{
														$resCode = '503 Service Unavailable';
														$responseData = 'DBA Service Error!';
													}
													break;
											}
											
											$statusServiceCode = $resCode;
											$serviceResponse .= $responseData;
									}
									else{
											$resCode = '404 Not Found';
											$responseData = 'Query not found!';
											
											$statusServiceCode = $resCode;
											$serviceResponse .= $responseData;
									}
								}
								if($q['command']['subCMD'] == "updateDatasets"){
									$attributeId = lowercase($pm['attribute']);
									
									if($pm['isSmartDS']){
											//При наличии нескольких датасетов и не только...

											$datasets = JSON::decode($pm['smartDS']);
											$responseData = [];
											$resData = [];
											$jsonList = [];
											
											if($hadoop->delete('/FiltersAttributes/data/'. $attributeId .'/*')){
												$resData[] = 'Delete proccess success!';
											}
											else{
												$resCode = '502 Bad Gateway';
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
												else{
													$resData[][] = 'Bad Data Storage gateway!';
												}
												
												
												$jsonList['df'][] = $newDataFile;
												
											}
											
											$jsonResponse = Json::encode(['response' => $jsonList]);
											
											if((new \Ytake\PrestoClient\StatementClient($hive, 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID']))->execute()){
												$responseData = ['Datasets in current attribute updated!', $resData];
											}
											else{
												$resCode = '503 Service Unavailable';
												$responseData[] = 'DBA Service Error!';
											}
											
											$statusServiceCode = $resCode;
											$serviceResponse .= $responseData;
											
										}
										else{
											    $dataset = $pm['dataset'];
											    $responseData = [];
												$resData = [];
											    $jsonReport = [];
												
												$query = explode(',', $dataset);
												
												if($hadoop->delete('/FiltersAttributes/data/'. $attributeId .'/*')){
													$resData[] = 'Delete proccess success!';
												}
												else{
													$resData[] = 'Bad Data Storage gateway!';
												}
												
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
												else{
													$resData[][] = 'Bad Data Storage gateway!';
												}
												
												if((new \Ytake\PrestoClient\StatementClient($hive, 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID']))->execute()){
													$responseData = ['Datasets in current attribute updated!', $resData];
												}
												else{
													$resCode = '503 Service Unavailable';
													$responseData[] = 'DBA Service Error!';
												}
												
												$statusServiceCode = $resCode;
												$serviceResponse .= $responseData;

										}
								}
								if($q['command']['subCMD'] == "updateParameters"){
									$attributeId = lowercase($pm['attribute']);
									
									$responseData = '';
										
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
										
									if((new \Ytake\PrestoClient\StatementClient($hive, $dataQuery))->execute()){ $responseData[] = 'Parameters update success'; }
									else{
										$resCode = '502 Bad Gateway';
										$responseData = 'DBA Service Error';
									}
									
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
								
								if($q['command']['subCMD'] == "updateAttribute"){
									$attributeId = lowercase($pm['attribute']);
									$attributeNewId = lowercase($pm['newAttribute']);
									$groupCreate = $pm['group'];
									
									$responseData = '';

									$dir = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeId;
									$dirUpdate = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeNewId;
											
									if($hadoop->rename($dir,$dirUpdate)){ $responseData = 'Update proccess success!'; }
									else{
										$resCode = '502 Bad Gateway';
										$responseData = 'Bad Data Storage gateway!';
									}

									if($groupCreate == 'data'){
											$updateP = ((new \Ytake\PrestoClient\StatementClient($hive, 'ALTER TABLE '. $attributeId .' RENAME TO '. $attributeNewId))->execute() && (new \Ytake\PrestoClient\StatementClient($hive, 'ALTER TABLE '. $attributeNewId .' SET LOCATION "hdfs://73ddd75d66e6:9866/'. $dirUpdate .'"'))->execute());
												
											if($updateP){$responseData = 'Current attribute table update!'; }
											else{
												$resCode = '503 Service Unavailable';
												$responseData = 'DBA Service Error!';
											}
									}
									
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
								else{
									$resCode = '404 Not Found';
									$responseData[] = "Not subcommand found!";
									
									
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
						}
						else if($q['command'] == 2){
								//Команда удаления данных из текущего фрагмента
								
								if($q['command']['subCMD'] == "deleteFilters"){
										$attributeId = lowercase($pm['attribute']);
										$queryHeader = 'ALTER TABLE '. $attributeId .' (\n';
										$queryBody = '\tDROP COLUMN '. $pm['field'];
										
										$responseData = '';
										
										if((new \Ytake\PrestoClient\StatementClient($hive, concat($queryHeader,$queryBody)))->execute()){
											$responseData = 'The filter in current attribute table deleted!';
										}
										else{
											$resCode = '503 Service Unavailable';
											$responseData = 'DBA Service Error!';
										}
										
										$statusServiceCode = $resCode;
										$serviceResponse .= $responseData;
								}
								if($q['command']['subCMD'] == "deleteDatasets"){
									$attributeId = lowercase($pm['attribute']);
									
									$resData = '';
									$responseData = [];
										
									if($hadoop->delete('/FiltersAttributes/data/'. $attributeId .'/*')){
											$resData = 'Delete proccess success!';
									}
									else{
											$resData = 'Bad Data Storage gateway!';
									}
										
									if((new \Ytake\PrestoClient\StatementClient($hive, 'UPDATE '. $attributeId .' SET '. $pm['field'] .'=\' \' WHERE id='. $pm['fieldID']))->execute()){
											$responseData = ['Datasets in current attribute deleted!', $resData];
									}
									else{
											$resCode = '502 Bad Gateway';
											$responseData[] = 'DBA Service Error';
									}
									
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
								if($q['command']['subCMD'] == "deletePhotogallery"){
									if($q['photogallery']){
											$attributeId = lowercase($pm['attribute']);
											
											$responseData = '';
											
											if((new \Ytake\PrestoClient\StatementClient($hive, 'UPDATE '. $attributeId .' SET '. $pm['field'] .'=\' \' WHERE id='. $pm['fieldID']))->execute()){
												$responseData = 'Photogallery in current attribute deleted!';
											}
											else{
												$resCode = '502 Bad Gateway';
												$responseData = 'DBA Service Error';
											}
									}
									else{
											$resCode = '404 Not Found';
											$responseData = 'Query not found!';
									}
									
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
								if($q['command']['subCMD'] == "deleteParameters"){
									$attributeId = lowercase($pm['attribute']);
									$responseData = '';
									
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
										
									if((new \Ytake\PrestoClient\StatementClient($hive, $dataQuery))->execute()){ $responseData = $successMessage; }
									else{
											$resCode = '502 Bad Gateway';
											$responseData = 'DBA Service Error';
									}
									
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
								
								if($q['command']['subCMD'] == "deleteAttribute"){
									$attributeId = lowercase($pm['attribute']);
										$groupCreate = $pm['group'];
										
										$responseData = '';

					
										$dir = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeId;

										if($groupCreate == 'data'){
											if((new \Ytake\PrestoClient\StatementClient($hive, 'DROP TABLE '. $attributeId))->execute()){
												$responseData = 'Current attribute table deleted!';
											}
											else{
												$resCode = '503 Service Unavailable';
												$responseData = 'DBA Service Error!';
											}
										}
											
										if($hadoop->delete($dir,'*')){ $responseData = 'Delete proccess success!'; }
										else{
											$resCode = '502 Bad Gateway';
											$responseData = 'Bad Data Storage gateway!';
										}
										
										$statusServiceCode = $resCode;
										$serviceResponse .= $responseData;
								}
								else{
									$resCode = '404 Not Found';
									$responseData[] = "Not subcommand found!";
									
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
						}
						else if($q['command'] == 3){
								//Команда вывода данных в текущем фрагменте
								
								if($q['command']['subCMD'] == "showFilters"){
									$currentAttribute = lowercase($pm['attribute']);
									$query = 'SHOW COLUMNS FROM '. $currentAttribute;
									$responseData = [];
									$filters = [];

									$result = (new \Ytake\PrestoClient\StatementClient($hive, $query))->execute();

									foreach( $result->getResults() as $rowNum => $row ) { $filters[] = $row; }

									$responseData[] = $filters;
										
									if(!$result){
											$resCode = '503 Service Unavailable';
											$responseData[] = 'DBA Service Error!';
									}
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
								if($q['command']['subCMD'] == "showDatasets"){
										$attributeId = lowercase($pm['attribute']);
										if($pm['isSmartDS']){
											$queryFind = 'SELECT '. $pm['DFSField'] .' FROM '. $attributeId;
											$responseData = [];
											$datasets = [];

											$result = (new \Ytake\PrestoClient\StatementClient($hive, $queryFind))->execute();

											foreach( $result->getResults() as $rowNum => $row ) { $datasets[] = $row['response']; }

											$responseData[] = $datasets;

											if(!$result){
												$resCode = '503 Service Unavailable';
												$responseData[] = 'DBA Service Error!';
											}
										}
										else{
											$queryFind = 'SELECT '. $pm['datafield'] .' FROM '. $attributeId;
											$responseData = [];
											$ds = [];


											$result = (new \Ytake\PrestoClient\StatementClient($hive, $queryFind))->execute();

											foreach( $result->getResults() as $rowNum => $row ) {
												$ds[] = $row['response'];
											}

											$responseData[] = $ds;

											if(!$result){
												$resCode = '503 Service Unavailable';
												$responseData[] = 'DBA Service Error!';
											}
											
										}
										$statusServiceCode = $resCode;
										$serviceResponse .= $responseData;
								}
								if($q['command']['subCMD'] == "sendPhotogallery"){
									$responseData = [];
									if($q['photogallery']){
											$attributeId = lowercase($pm['attribute']);
											$queryFind = 'SELECT '. $pm['photofield'] .' FROM '. $attributeId;
											$datasets = [];

											$result = (new \Ytake\PrestoClient\StatementClient($hive, $queryFind))->execute();

											foreach( $result->getResults() as $rowNum => $row ) { $datasets[] = $row['response']; }

											$responseData[] = $datasets;
												
											if(!$result){
												$resCode = '503 Service Unavailable';
												$responseData[] = 'DBA Service Error!';
											}
									}
									else{
											$resCode = '404 Not Found';
											$responseData[] = 'Query not found!';
									}
									
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
								if($q['command']['subCMD'] == "sendParameters"){
									$attributeId = lowercase($pm['attribute']);
									$responseData = [];
									$tables = [];
									switch($pm['dataParam']){
											case "cost": $dataQuery = 'SELECT '. $pm['costQuery'] .' FROM '. $attributeId; break;
											case "text": $dataQuery = 'SELECT '. $pm['textQuery'] .' FROM '. $attributeId; break;
											case "selecting": $dataQuery = 'SELECT '. $pm['selectingQuery'] .' FROM '. $attributeId; break;
											case "precentable": $dataQuery = 'SELECT '. $pm['precentableQuery'] .' FROM '. $attributeId; break;
											case "smartDataset": case "photogallery": $dataQuery = 'SELECT '. $pm['dQuery'] .' FROM '. $attributeId; break;
											default: $dataQuery = 'SELECT '. $pm['intQuery'] .' FROM '. $attributeId; break;
									}

									$result = (new \Ytake\PrestoClient\StatementClient($hive, $dataQuery))->execute();

									foreach( $result->getResults() as $rowNum => $row ) { $tables[] = $row; }

									$responseData[] = $tables;

									if(!$result){
										$resCode = '502 Bad Gateway';
										$responseData[] = 'DBA Service Error!';	
									}
									
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
								if($q['command']['subCMD'] == "showTableColumns"){
										$attributeId = lowercase($pm['attribute']);
										$query = 'SELECT COUNT(*) as columncount FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = \''. $attributeId .'\'';
										$responseData = [];
										$tables = [];
										$result = (new \Ytake\PrestoClient\StatementClient($hive, $query))->execute();

										if($result){
											foreach( $result->getResults() as $rowNum => $row ) { $tables[] = $row; }
										}
										else{ $tables[] = [0]; }

										$responseData[] = $tables;
										
										if(!$result){
											$resCode = '503 Service Unavailable';
											$responseData[] = 'DBA Service Error!';
										}
										
										$statusServiceCode = $resCode;
										$serviceResponse .= $responseData;
								}
								
								
								if($q['command']['subCMD'] == "showAttributes"){
										$query = 'SHOW TABLES';
										$tables = [];
										$responseData = [];
										$result = (new \Ytake\PrestoClient\StatementClient($hive, $query))->execute();

										foreach( $result->getResults() as $rowNum => $row ) { $tables[] = $row; }

										$responseData[] = $tables;
										
										if(!$result){
											$resCode = '503 Service Unavailable';
											$responseData[] = 'DBA Service Error!';
										}
										
										$statusServiceCode = $resCode;
										$serviceResponse .= $responseData;
								}
								else{
									$resCode = '404 Not Found';
									$responseData[] = "Not subcommand found!";
									
									$statusServiceCode = $resCode;
									$serviceResponse .= $responseData;
								}
								
						}
						else{
							$resCode = '404 Not Found';
							$responseData = "Not command found!";
							
							$statusServiceCode = $resCode;
							$serviceResponse .= $responseData;
						}
					}
				}
				else{
					$resCode = '404 Not Found';
					$responseData = "Not query found!";
					
					$statusServiceCode = $resCode;
					$serviceResponse .= $responseData;
				}
		}
		else{
			$resCode = '404 Not Found';
			$responseData = "Not service found!";
			
			$statusServiceCode = $resCode;
			$serviceResponse .= $responseData;
		}
			
		
		header($_SERVER['SERVER_PROTOCOL'] ." ". $statusServiceCode);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return ["response" => var_dump($serviceResponse)];
		
		
	}
}
?>
