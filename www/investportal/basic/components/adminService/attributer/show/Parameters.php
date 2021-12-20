<?php
namespace app\components\adminService\attributer\show;

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
		$serviceResponse = [];
		
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
		
		header("HTTP/1.1 ". $statusServiceCode);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return ["response" => $serviceResponse];
	}
}

?>
