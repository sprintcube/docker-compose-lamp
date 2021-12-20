<?php
namespace app\components\adminService\attributer\delete;

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
		
		$queryHeader = 'ALTER TABLE '. $attributeId .' (\n';
		$queryBody = '\tDROP COLUMN '. $pm['field'];
										
		$serviceResponse = '';
										
		if($hive->getIterator(concat($queryHeader,$queryBody))){
			$serviceResponse = 'The filter in current attribute table deleted!';
		}
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
