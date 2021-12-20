<?php
namespace app\components\adminService\attributer\delete;

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
									
		$dir = 'user/ip-data/FiltersAttributes/'. $groupCreate[$i] . '/' . $attributeId;

		if($groupCreate == 'data'){
			if($hive->getIterator('DROP TABLE '. $attributeId)){ $serviceResponse[] = 'Current attribute table deleted!'; }
			else{
				$statusServiceCode = '503 Service Unavailable';
				$serviceResponse[] = 'DBA Service Error!';
			}
		}
											
		if($hadoop->delete($dir,'*')){ $serviceResponse = 'Delete proccess success!'; }
		else{
			$statusServiceCode = '502 Bad Gateway';
			$serviceResponse[] = 'Bad Data Storage gateway!';
		}
		
		header("HTTP/1.1 ". $statusServiceCode);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return ["response" => $serviceResponse];
	}
}

?>
