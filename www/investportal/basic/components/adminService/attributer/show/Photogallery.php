<?php
namespace app\components\adminService\attributer\show;

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
			$queryFind = 'SELECT '. $pm['field'] .' FROM '. $attributeId;
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
										
		header("HTTP/1.1 ". $statusServiceCode);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return ["response" => $serviceResponse];
	}
}

?>
