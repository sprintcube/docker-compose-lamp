<?php
namespace app\components\adminService\attributer\send;

use Yii;
use yii\base\Component;

require_once '../../../components/php-thrift-sql/ThriftSQL.phar';

сlass Attribute extends Component{
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
		
		$groupCreate = $pm['group'];
										
										
		$cache = Yii::$app->cacheRedis;
		$key = 'readyAttribute';
										
		if($groupCreate == 'meta'){
											
			$send = $cache->lpush($key, $attributeId);
											 
			if(!$send){
				$statusServiceCode = '503 Service Unavailable';
				$serviceResponse[] = 'Redis Service Error!';
			}
			else{ $serviceResponse[] = 'Ready proccess success!'; } 
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

					if($hive->getIterator(concat($queryHeader,$queryBody,$queryFooter)) && $hadoop->mkdirs($dirNew)){ $serviceResponse[] = ['Creator proccess success!', 'New attribute table created!']; }
					else{
						$statusServiceCode = '502 Bad Gateway';
						$serviceResponse[] =  'Services gateway!';
					}
				}
			}
												
	    }
		
		header("HTTP/1.1 ". $statusServiceCode);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return ["response" => $serviceResponse];
	}
}

?>
