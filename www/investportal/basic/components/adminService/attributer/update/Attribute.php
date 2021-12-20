<?php
namespace app\components\adminService\attributer\update;

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
			$attributeNewId = $pm['attributeNewId'];
		}
		
		$serviceResponse = [];
		$statusServiceCode = '200 OK';
		
		$groupCreate = $pm['group'];


		$dir = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeId;
		$dirUpdate = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeNewId;
											
		if($hadoop->rename($dir,$dirUpdate)){ $serviceResponse[] = 'Update proccess success!'; }
		else{
			$statusServiceCode = '502 Bad Gateway';
			$serviceResponse[] = 'Bad Data Storage gateway!';
		}

		if($groupCreate == 'data'){
			$updateP = ($hive->getIterator('ALTER TABLE '. $attributeId .' RENAME TO '. $attributeNewId) && $hive->getIterator('ALTER TABLE '. $attributeNewId .' SET LOCATION "hdfs://73ddd75d66e6:9866/'. $dirUpdate .'"'));
												
			if($updateP){$serviceResponse[] = 'Current attribute table update!'; }
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
