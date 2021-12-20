<?php
namespace app\components\adminService\attributer\update;

use Yii;
use yii\base\Component;

сlass Photogallery extends Component{
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
		$serviceResponse = [];
		
		if($q['photogallery']){
			$formats = $q['format'];
			$isPhotoCount = ($q['count'] > 4 || $q['count'] == 4) ? TRUE : FALSE;
											

			switch($isPhotoCount){
				case FALSE:
					$statusServiceCode = '415 Unsupported Media Type';
					$serviceResponse[] = 'Invalid number of photos (the correct minimum value is 4)';
				break;
				default:
					$jsonReport = ['imageFormats' => $formats, 'imageCounts' => $q['count']];
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
		
		header("HTTP/1.1 ". $statusServiceCode);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return ["response" => $serviceResponse];
	}
}

?>
