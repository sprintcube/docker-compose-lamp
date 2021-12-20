<?php
namespace app\components\adminService\attributer\send;

use Yii;
use yii\base\Component;

сlass Parameters extends Component{
	public $q;
	public $d;
	public function init(){
		parent::init();
		
		$this->query = [NULL,NULL];
		$this->connector = (new \ThriftSQL\Hive( 'localhost', 10000 ))->connect();
	}
	
	public function run($q = NULL, $d = NULL){
		if($q != NULL){ 
			$this->query[0] = $q; 
			$this->query[1] = $d;
			$pm = $this->query[0];
			$q = $this->query[1];
			
			$hive = $this->connector;
			
			$attributeId = $pm['attribute'];
		}
		
		$statusServiceCode = '200 OK';
		$serviceResponse = '';
		
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
				else { $response = (float) $costQuery['val']; }
												
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
				else { $response = (int) $pQuery['val']; }
												
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
				else { $response = (int) $intQuery['val']; }
												
				if($pm['fieldID']){ $dataQuery = 'UPDATE '. $attributeId .' SET '. $pm['field'] .'='. $response .' WHERE id='. $pm['fieldID']; }
				else{ $dataQuery = 'INSERT INTO '. $attributeId .' ('. $pm['field'] .') VALUES('. $response .')'; }
			}
										
			if($hive->getIterator($dataQuery)){ $serviceResponse = 'Parameters send success!'; }
			else{
				$statusServiceCode = '502 Bad Gateway';
				$serviceResponse = 'DBA Service Error';
			}
			break;
		}
		
		header("HTTP/1.1 ". $statusServiceCode);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return ["response" => $serviceResponse];
	}
}

?>
