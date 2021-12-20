<?php
namespace app\components\adminService\attributer\send;

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
			//При наличии нескольких датасетов и не только...

			$datasets = JSON::Decode($pm['smartDS']);
			$jsonList = [];

			for($i = 0; $i < count($datasets['file']); $i++){
				$sendCurData = $datasets[$i]['file'];
				$query = explode(',', $sendCurData);

				if(strrpos($query[0], 'application/json')){ $newDataFile = $i .".json"; }
				else if(strrpos($query[0], 'application/xml')){ $newDataFile = $i .".xml"; }
				else if(strrpos($query[0], 'application/vnd.ms-excel')){ $newDataFile = $i .".csv"; }

												
				if($hadoop->createWithData('/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile, base64_decode($query[1]))){ $serviceResponse[] = 'Send proccess success!'; }
				else{
					$statusServiceCode = '502 Bad Gateway';
					$serviceResponse[] = 'Bad Data Storage gateway!';
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
			$dataset = $pm['dataset'];
			$query = explode(',', $dataset);
			$jsonReport = [];
												
			$serviceResponse = [];
																								
			if(strrpos($query[0], 'application/json')){ $newDataFile = "single.json"; }
			else if(strrpos($query[0], 'application/xml')){ $newDataFile = "single.xml"; }
			else if(strrpos($query[0], 'application/vnd.ms-excel')){ $newDataFile = "single.csv"; }
												
			if($hadoop->createWithData('/FiltersAttributes/data/'. $attributeId .'/'. $newDataFile, base64_decode($query[1]))){ $serviceResponse[] = 'Send proccess success!'; }
			else{
				$statusServiceCode = '502 Bad Gateway';
				$serviceResponse[] = 'Bad Data Storage gateway!';
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
		
		header("HTTP/1.1 ". $statusServiceCode);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return ["response" => $serviceResponse];
	}
}

?>
