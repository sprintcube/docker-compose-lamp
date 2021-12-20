<?php
namespace app\components\adminService\attributer\delete;

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
										
		if($hive->getIterator($dataQuery)){ $serviceResponse = $successMessage; }
		else{
			$statusServiceCode = '502 Bad Gateway';
			$serviceResponse = 'DBA Service Error';
		}
		
		header("HTTP/1.1 ". $statusServiceCode);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return ["response" => $serviceResponse];
	}
}

?>
