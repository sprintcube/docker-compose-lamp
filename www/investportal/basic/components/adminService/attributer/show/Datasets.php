<?php
namespace app\components\adminService\attributer\show;

use Yii;
use yii\base\Component;

require_once '../../../components/php-thrift-sql/ThriftSQL.phar';

сlass Datasets extends Component{
	public $q;
	public function init(){
		parent::init();
		
		$this->query = NULL;
		$this->connector = [
			Yii::$app->hdfs('localhost', '9864'),
			(new \ThriftSQL\Hive( 'localhost', 10000 ))->connect()
		];
	}
	
	public function run($q = NULL){
		
		if($q != NULL){
			$this->query = $q;
			$pm = $this->query;
			$hive = $this->connector[1];
			$hadoop = $this->connector[0];
			
			$attributeId = $pm['attribute'];
		}
		
		$serviceResponse = [];
		$statusServiceCode = '200 OK';
		
		if($pm['isSmartDS']){
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
			$queryFind = 'SELECT '. $pm['field'] .' FROM '. $attributeId;
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
		
		header("HTTP/1.1 ". $statusServiceCode);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return ["response" => $serviceResponse];
	}
}

?>
