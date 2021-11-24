<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;
use yii\helpers\Json;

require_once '../components/php-thrift-sql/ThriftSQL.phar';

class AdminController extends Controller{
	public function beforeAction($action){
	 if (in_array($action->id, ['auth', 'adminService'])) {
		$this->enableCsrfValidation = false;
	 }
	 return parent::beforeAction($action);
	}
    public function actionIndex(){
		if(Yii::$app->admin->isGuest){ header('Location: /admin/auth'); }
		
		
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
	public function actionAuth(){
		
		if(!Yii::$app->admin->isGuest){ header('Location: /admin'); }
		
		$this->layout = "adminAuth";
		$this->view->registerCssFile("/css/admin/auth.css");
		
		if($_POST['username']){
			$u = trim($_POST['username']);
			$p = trim($_POST['pass']);
			
			$query = ['admin' => $u, 'password' => $p];
			
			Yii::$app->asLogin->proccess($query);
		}
		
		return $this->render('auth');
	}
	public function actionAdminService($svc, $subSVC){
		$serviceResponse = [];
		$statusServiceCode = '200 OK';
		switch($svc){
			case "dataServices":
				if($subSVC == "filters"){
					if($_POST['svcQuery']){
						$q = Json::decode($_POST['svcQuery']);
						$pm = $q['parameters'];
						$hadoop = Yii::$app->hdfs(
							'ui-c9q5hn6k05uikro3g9a4-rc1b-dataproc-m-z298o1kwqqpqm1ac-9870.dataproc-ui.yandexcloud.net',
							'8020',
							'ip-data'
						);

						$hive = (new \ThriftSQL\Hive( 'ui-c9q5hn6k05uikro3g9a4-rc1b-dataproc-m-z298o1kwqqpqm1ac-10002.dataproc-ui.yandexcloud.net', 10000, 'ip-data' ))->connect();

						
						
						switch($q['command']){
							case 0:
								//Команда добавления данных в текущий фрагмент

								switch($q['command']['subCMD']){
									case "sendFilters":
											$attributeId = lowercase($pm['attribute']);
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

											if($hive->getIterator(concat($queryHeader,$queryBody))){ $serviceResponse[] = 'New filter in current attribute table created!'; }
											else{
												$statusServiceCode = '503 Service Unavailable';
												$serviceResponse[] = 'DBA Service Error!';
											}
							
									break;
									case "sendDatasets":
										if($pm['isSmartDS']){
											//При наличии нескольких датасетов и не только...

											$attributeId = lowercase($pm['attribute']);
											$datasets = JSON::Decode($pm['smartDS']);
											$jsonList = [];

											for($i = 0; $i < count($datasets['file']); $i++){
												$sendCurData = $datasets[$i]['file'];
												$query = explode(',', $sendCurData);

												if(strrpos($query[0], 'application/json')){ $newDataFile = $i .".json"; }
												else if(strrpos($query[0], 'application/xml')){ $newDataFile = $i .".xml"; }
												else if(strrpos($query[0], 'application/vnd.ms-excel')){ $newDataFile = $i .".csv"; }

												
												if($hadoop->createWithData('user/ip-data/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile, base64_decode($query[1]))){ $serviceResponse[][$i] = 'Send proccess success!'; }
												else{
													$statusServiceCode = '502 Bad Gateway';
													$serviceResponse[][$i] = 'Bad Data Storage gateway!';
												}
												
												$jsonList[]['df'] = $newDataFile;
											}
											
											$jsonResponse = Json::encode(['response' => $jsonList]);

											if($pm['fieldID']){ $sendP = $hive->getIterator('UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID']); }
											else{ $sendP = $hive->getIterator('INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $jsonResponse .')'); }
												
											if($sendP){ $serviceResponse[] = 'Datasets in current attribute creating!'; }
											else{
												$statusServiceCode = '503 Service Unavailable';
												$serviceResponse[] = 'DBA Service Error!';
											}
										}
										else{
											    $attributeId = lowercase($pm['attribute']);
											    $dataset = $pm['dataset'];
												$query = explode(',', $dataset);
												$jsonReport = [];
												
												if(strrpos($query[0], 'application/json')){ $newDataFile = "single.json"; }
												else if(strrpos($query[0], 'application/xml')){ $newDataFile = "single.xml"; }
												else if(strrpos($query[0], 'application/vnd.ms-excel')){ $newDataFile = "single.csv"; }
												
												if($hadoop->createWithData('user/ip-data/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile, base64_decode($query[1]))){ $serviceResponse[][$i] = 'Send proccess success!'; }
												else{
													$statusServiceCode = '502 Bad Gateway';
													$serviceResponse[][$i] = 'Bad Data Storage gateway!';
												}

												$jsonReport['ds'] = $newDataFile;
												$jsonResponse = Json::encode(['response' => $jsonReport]);
												
												if($pm['fieldID']){ $sendP = $hive->getIterator('UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID']); }
												else{ $sendP = $hive->getIterator('INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $jsonResponse .')'); }
													
												if($sendP){ $serviceResponse[] = 'Datasets in current attribute creating!'; }
												else{
													$statusServiceCode = '503 Service Unavailable';
													$serviceResponse[] = 'DBA Service Error!';
												}
										}
									break;
									case "sendPhotogallery":
										if($q['photogallery']){
											$attributeId = lowercase($pm['attribute']);
											$formats = $q['format'];
											$isPhotoCount = ($q['count'] > 4 || $q['count'] == 4) ? TRUE : FALSE;

											switch($isPhotoCount){
												case FALSE:
													$statusServiceCode = 415;
													$serviceResponse[] = 'Invalid number of photos (the correct minimum value is 4)';
												break;
												default:
													$jsonReport = Json::Encode(['imageFormats' => $formats, 'imageCounts' => $q['count']]);
													$jsonResponse = Json::encode(['response' => $jsonReport]);
													
													if($pm['fieldID']){ $sendP = $hive->getIterator('UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID']); }
													else{ $sendP = $hive->getIterator('INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $jsonResponse .')'); }
														
													if($sendP){ $serviceResponse[] = 'New photogallery in current attribute table creating!'; }
													else{
														$statusServiceCode = '503 Service Unavailable';
														$serviceResponse[] = 'DBA Service Error!';
													}
												break;
											}
										}
										else{
											$statusServiceCode = '404 Not Found';
											$serviceResponse[] = 'Query not found!';
										}
									break;
									case "sendParameters":
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
										
										if($hive->getIterator($dataQuery)){ $serviceResponse[] = 'Parameters send success!'; }
										else{
											$statusServiceCode = '502 Bad Gateway';
											$serviceResponse[] = 'DBA Service Error';
										}
									break;
									break;
									default:
										$attributeId = lowercase($pm['attribute']);
										$groupCreate = ['meta','data'];

										for($i = 0; $i < count($groupCreate); $i++){
											

											if($groupCreate[$i] == 'meta'){
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
												
												if($hive->getIterator(concat($queryHeader,$queryBody,$queryFooter))){ $serviceResponse[] = 'New attribute table created!'; }
												else{
													$statusServiceCode = '503 Service Unavailable';
													$serviceResponse[] = 'DBA Service Error!';
												}
											}
											else{
												$dirNew = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeId;

												if($hadoop->mkdirs($dirNew)){ $serviceResponse[] = 'Creator proccess success!'; }
												else{
													$statusServiceCode = '502 Bad Gateway';
													$serviceResponse[] = 'Bad Data Storage gateway!';
												}
												
											}
										}
									break;
								}

								
							break;
							case 1:
								//Команда обновления данных в текущем фрагменте

								switch($q['command']['subCMD']){
									case "updateFilters":
										$attributeId = lowercase($pm['attribute']);
										
										switch($pm['newType']){
												case "intField": case "precentableField": $dataType = 'int'; break;
												case "costField": $dataType = 'float'; break;
												case "smartDatasets": case "photogalleryField": $dataType = 'json'; break;
												case "selectingField": $dataType = 'varchar(255)'; break;
												default: $dataType = 'text'; break;
										}
										
										$queryHeader = 'ALTER TABLE '. $attributeId;
										$queryBody = '\tRENAME COLUMN '. $pm['field'] .' to '. $pm['newField'];
										
										$updateP = ($hive->getIterator(concat($queryHeader,$queryBody)) && $hive->getIterator(concat($queryHeader,'\tALTER COLUMN '. $pm['newField'] .' '. $dataType)));
										
										if($updateP){ $serviceResponse[] = 'The filter in current attribute table updated!'; }
										else{
											$statusServiceCode = '503 Service Unavailable';
											$serviceResponse[] = 'DBA Service Error!';
										}
									break;
									case "updatePhotogallery":
										if($q['photogallery']){
											$attributeId = lowercase($pm['attribute']);
											$formats = $q['format'];
											$isPhotoCount = ($q['count'] > 4 || $q['count'] == 4) ? TRUE : FALSE;

											switch($isPhotoCount){
												case FALSE:
													$statusServiceCode = 415;
													$serviceResponse[] = 'Invalid number of photos (the correct minimum value is 4)';
												break;
												default:
													$jsonReport = Json::Encode(['imageFormats' => $formats, 'imageCounts' => $q['count']]);
													$jsonResponse = Json::encode(['response' => $jsonReport]);
													
													if($hive->getIterator('UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID'])){
														$serviceResponse[] = 'Photogallery in current attribute updated!';
													}
													else{
														$statusServiceCode = '503 Service Unavailable';
														$serviceResponse[] = 'DBA Service Error!';
													}
												break;
											}
									  }
									  else{
										$statusServiceCode = '404 Not Found';
										$serviceResponse[] = 'Query not found!';
									  }
										
									break;
									case "updateDatasets":
										if($pm['isSmartDS']){
											//При наличии нескольких датасетов и не только...

											$attributeId = lowercase($pm['attribute']);
											$datasets = JSON::Decode($pm['smartDS']);
											$jsonList = [];
											
											if($hadoop->delete('user/ip-data/FiltersAttributes/data/'. $attributeId .'/*')){
												$serviceResponse[] = 'Delete proccess success!';
											}
											else{
												$statusServiceCode = '502 Bad Gateway';
												$serviceResponse[] = 'Bad Data Storage gateway!';
											}
											
											for($i = 0; $i < count($datasets['file']); $i++){
												$sendCurData = $datasets[$i]['file'];
												$query = explode(',', $sendCurData);
												
												
												
												if(strrpos($query[0], 'application/json')){ $newDataFile = $i .".json"; }
												else if(strrpos($query[0], 'application/xml')){ $newDataFile = $i .".xml"; }
												else if(strrpos($query[0], 'application/vnd.ms-excel')){ $newDataFile = $i .".csv"; }

												if($hadoop->createWithData('user/ip-data/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile, $query[0])){
													$serviceResponse[] = 'Send proccess success!';
												}
												else{
													$statusServiceCode = '502 Bad Gateway';
													$serviceResponse[] = 'Bad Data Storage gateway!';
												}
												
												
												$jsonList[]['df'] = $newDataFile;
												
											}
											
											$jsonResponse = Json::encode(['response' => $jsonList]);
											
											if($hive->getIterator('UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID'])){
												$serviceResponse[] = 'Datasets in current attribute updated!';
											}
											else{
												$statusServiceCode = '503 Service Unavailable';
												$serviceResponse[] = 'DBA Service Error!';
											}
											
										}
										else{
											    $attributeId = lowercase($pm['attribute']);
											    $dataset = $pm['dataset'];
											    $jsonReport = [];
												$query = explode(',', $dataset);
												
												if($hadoop->delete('user/ip-data/FiltersAttributes/data/'. $attributeId .'/*')){
													$serviceResponse[] = 'Delete proccess success!';
												}
												else{
													$statusServiceCode = '502 Bad Gateway';
													$serviceResponse[] = 'Bad Data Storage gateway!';
												}
												
												if(strrpos($query[0], 'application/json')){ $newDataFile = "single.json"; }
												else if(strrpos($query[0], 'application/xml')){ $newDataFile = "single.xml"; }
												else if(strrpos($query[0], 'application/vnd.ms-excel')){ $newDataFile = "single.csv"; }

												$file = fopen( $newDataFile, 'wb' );
												fwrite($file, base64_decode($query[1]));
												fclose($file);

												$jsonReport['ds'] = $newDataFile;
												$jsonResponse = Json::encode(['response' => $jsonReport]);

												if($hadoop->create('user/ip-data/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile)){
													$serviceResponse[] = 'Send proccess success!';
												}
												else{
													$statusServiceCode = '502 Bad Gateway';
													$serviceResponse[] = 'Bad Data Storage gateway!';
												}
												
												if($hive->getIterator('UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $jsonResponse .' WHERE id='. $pm['fieldID'])){
													$serviceResponse[] = 'Datasets in current attribute updated!';
												}
												else{
													$statusServiceCode = '503 Service Unavailable';
													$serviceResponse[] = 'DBA Service Error!';
												}

										}
									break;
									case "updateParameters":
										$attributeId = lowercase($pm['attribute']);
										
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
										
										if($hive->getIterator($dataQuery)){ $serviceResponse[] = 'Parameters update success'; }
										else{
											$statusServiceCode = '502 Bad Gateway';
											$serviceResponse[] = 'DBA Service Error';
										}
									break;
									default:
										$attributeId = lowercase($pm['attribute']);
										$attributeNewId = lowercase($pm['newAttribute']);
										$groupCreate = ['meta','data'];

					
										for($i = 0; $i < count($groupCreate); $i++){
											$dir = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeId;
											$dirUpdate = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeNewId;
											
											if($hadoop->rename($dir,$dirUpdate)){ $serviceResponse[] = 'Update proccess success!'; }
											else{
												$statusServiceCode = '502 Bad Gateway';
												$serviceResponse[] = 'Bad Data Storage gateway!';
											}

											if($groupCreate[$i] == 'data'){
												$updateP = ($hive->getIterator('ALTER TABLE '. $attributeId .' RENAME TO '. $attributeNewId) && $hive->getIterator('ALTER TABLE '. $attributeNewId .' SET LOCATION "hdfs://73ddd75d66e6:9866/'. $dirUpdate .'"'));
												
												if($updateP){ $serviceResponse[] = 'Current attribute table update!'; }
												else{
													$statusServiceCode = '503 Service Unavailable';
													$serviceResponse[] = 'DBA Service Error!';
												}
											}	
										}
									break;
								}
							break;
							case 2:
								//Команда удаления данных из текущего фрагмента

								switch($q['command']['subCMD']){
									case "deleteFilters":
										$attributeId = lowercase($pm['attribute']);
										$queryHeader = 'ALTER TABLE '. $attributeId .' (\n';
										$queryBody = '\tDROP COLUMN '. $pm['field'];
										
										if($hive->getIterator(concat($queryHeader,$queryBody))){
											$serviceResponse[] = 'The filter in current attribute table deleted!';
										}
										else{
											$statusServiceCode = '503 Service Unavailable';
											$serviceResponse[] = 'DBA Service Error!';
										}
									break;
									case "deleteDatasets":
										$attributeId = lowercase($pm['attribute']);
										
										if($hadoop->delete('user/ip-data/FiltersAttributes/data/'. $attributeId .'/*')){
											$serviceResponse[] = 'Delete proccess success!';
										}
										else{
											$statusServiceCode = '502 Bad Gateway';
											$serviceResponse[] = 'Bad Data Storage gateway!';
										}
										
										if($hive->getIterator('UPDATE '. $attributeId .' SET '. $pm['field'] .'=\' \' WHERE id='. $pm['fieldID'])){
											$serviceResponse[] = 'Datasets in current attribute deleted!';
										}
										else{
											$statusServiceCode = '502 Bad Gateway';
											$serviceResponse[] = 'DBA Service Error';
										}
									break;
									case "deletePhotogallery":
										if($q['photogallery']){
											$attributeId = lowercase($pm['attribute']);
											
											if($hive->getIterator('UPDATE '. $attributeId .' SET '. $pm['field'] .'=\' \' WHERE id='. $pm['fieldID'])){
												$serviceResponse[] = 'Photogallery in current attribute deleted!';
											}
											else{
												$statusServiceCode = '502 Bad Gateway';
												$serviceResponse[] = 'DBA Service Error';
											}
										}
										else{
											$statusServiceCode = '404 Not Found';
											$serviceResponse[] = 'Query not found!';
										}
									break;
									case "deleteParameters":
										$attributeId = lowercase($pm['attribute']);
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
										
										if($hive->getIterator($dataQuery)){ $serviceResponse[] = $successMessage; }
										else{
											$statusServiceCode = '502 Bad Gateway';
											$serviceResponse[] = 'DBA Service Error';
										}
										
									break;
									default:
										$attributeId = lowercase($pm['attribute']);
										$groupCreate = ['meta','data'];

										for($i = 0; $i < count($groupCreate); $i++){
											$dir = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeId;

											if($groupCreate[$i] == 'data'){
												if($hive->getIterator('DROP TABLE '. $attributeId)){
													$serviceResponse[] = 'Current attribute table deleted!';
												}
												else{
													$statusServiceCode = '503 Service Unavailable';
													$serviceResponse[] = 'DBA Service Error!';
												}
											}
											
											if($hadoop->delete($dir,'*')){ $serviceResponse[] = 'Delete proccess success!'; }
											else{
												$statusServiceCode = '502 Bad Gateway';
												$serviceResponse[] = 'Bad Data Storage gateway!';
											}
										}
									break;
								}
							break;
							case 3:
								//Команда вывода данных в текущем фрагменте

								switch($q['command']['subCMD']){
									case "showFilters":
										$currentAttribute = lowercase($pm['attribute']);
										$query = 'SHOW COLUMNS FROM '. $currentAttribute;
										$filters = [];

										$result = $hive->getIterator($query);

										foreach( $result as $rowNum => $row ) {
											$filters[] = $row;
										}

										$serviceResponse[] = $filters;
										
										if(!$result){
											$statusServiceCode = '503 Service Unavailable';
											$serviceResponse[] = 'DBA Service Error!';
										}
									break;
									case "showDatasets":
										$attributeId = lowercase($pm['attribute']);
										if($pm['isSmartDS']){
											$queryFind = 'SELECT '. $pm['DFSField'] .' FROM '. $attributeId;
											$datasets = [];

											$result = $hive->getIterator($queryFind);

											foreach( $result as $rowNum => $row ) {
												$datasets[] = $row['response'];
											}

											$serviceResponse[] = $datasets;

											if(!$result){
												$statusServiceCode = '503 Service Unavailable';
												$serviceResponse[] = 'DBA Service Error!';
											}
										}
										else{
											$queryFind = 'SELECT '. $pm['datafield'] .' FROM '. $attributeId;
											$ds = [];


											$result = $hive->getIterator($queryFind);

											foreach( $result as $rowNum => $row ) {
												$ds[] = $row['response'];
											}

											$serviceResponse[] = $ds;

											if(!$result){
												$statusServiceCode = '503 Service Unavailable';
												$serviceResponse[] = 'DBA Service Error!';
											}
											
										}
									break;
									case "showPhotogallery":
										if($q['photogallery']){
											$attributeId = lowercase($pm['attribute']);
											$queryFind = 'SELECT '. $pm['photofield'] .' FROM '. $attributeId;
											$datasets = [];

											$result = $hive->getIterator($queryFind);

											foreach( $result as $rowNum => $row ) {
												$datasets[] = $row['response'];
											}

											$serviceResponse[] = $datasets;
												
											if(!$result){
												$statusServiceCode = '503 Service Unavailable';
												$serviceResponse[] = 'DBA Service Error!';
											}
										}
										else{
											$statusServiceCode = '404 Not Found';
											$serviceResponse[] = 'Query not found!';
										}
									break;
									case "showParameters":
										$attributeId = lowercase($pm['attribute']);
										switch($pm['dataParam']){
											case "cost": $dataQuery = 'SELECT '. $pm['costQuery'] .' FROM '. $attributeId; break;
											case "text": $dataQuery = 'SELECT '. $pm['textQuery'] .' FROM '. $attributeId; break;
											case "selecting": $dataQuery = 'SELECT '. $pm['selectingQuery'] .' FROM '. $attributeId; break;
											case "precentable": $dataQuery = 'SELECT '. $pm['precentableQuery'] .' FROM '. $attributeId; break;
											case "smartDataset": case "photogallery": $dataQuery = 'SELECT '. $pm['dQuery'] .' FROM '. $attributeId; break;
											default: $dataQuery = 'SELECT '. $pm['intQuery'] .' FROM '. $attributeId; break;
										}

										$result = $hive->getIterator($dataQuery);

										foreach( $result as $rowNum => $row ) {
											$tables[] = $row;
										}

										$serviceResponse[] = $tables;

										if(!$result){
											$statusServiceCode = '502 Bad Gateway';
											$serviceResponse[] = 'DBA Service Error';	
										}
									break;
									default:
										$query = 'SHOW TABLES';
										$tables = [];
										$result = $hive->getIterator($query);

										foreach( $result as $rowNum => $row ) {
											$tables[] = $row;
										}

										$serviceResponse[] = $tables;
										
										if(!$result){
											$statusServiceCode = '503 Service Unavailable';
											$serviceResponse[] = 'DBA Service Error!';
										}
									break;
								}
								
							break;
						}
					}
				}
				else{
					$statusServiceCode = '404 Not Found';
					$serviceResponse[] = "Not query found!";
				}
			break;
			
		}
		$hive->disconnect();
		
		header("HTTP/1.1 ". $statusServiceCode);
		echo Json::encode(["response" => $serviceResponse]);
	}
}
?>
