<?php
namespace app\components\adminService\attributer\send;

use Yii;
use yii\base\Component;

require_once '../../../components/php-thrift-sql/ThriftSQL.phar';

сlass Filters extends Component{
	public $q;
	public function init(){
		parent::init();
		
		$this->query = NULL;
		$this->connector = (new \ThriftSQL\Hive( 'localhost', 10000 ))->connect();
	}
	
	public function run($q = NULL){
		if($q != NULL){ 
			$this->query = $q; 
			$pm = $this->query;
			$hive = $this->connector;
			
			$attributeId = $pm['attribute'];
		}
		
		$serviceResponse = '';
		$statusServiceCode = '200 OK';
		
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

										    
		if($hive->getIterator(concat($queryHeader,$queryBody))){ $serviceResponse = 'New filter in current attribute table created!'; }
		else{
			$statusServiceCode = '503 Service Unavailable';
			$serviceResponse = 'DBA Service Error!';
		}
		
		header("HTTP/1.1 ". $statusServiceCode);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return ["response" => $serviceResponse];
		
		
	}
}

?>
