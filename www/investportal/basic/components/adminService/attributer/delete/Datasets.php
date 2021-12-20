<?php
namespace app\components\adminService\attributer\delete;

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
		
		if($hadoop->delete('/FiltersAttributes/data/'. $attributeId .'/*')){
			$serviceResponse[] = 'Delete proccess success!';
		}
		else{
			$statusServiceCode = '502 Bad Gateway';
			$serviceResponse[] = 'Bad Data Storage gateway!';
		}
										
		if($hive->getIterator('UPDATE '. $attributeId .' SET '. $pm['field'] .'=\' \' WHERE id='. $pm['fieldID'])){
			$serviceResponse[] = 'Datasets in current attribute deleted!';
		}
		else{
			$statusServiceCode = '502 Bad Gateway';
			$serviceResponse[] = 'DBA Service Error';
		}
		
		header("HTTP/1.1 ". $statusServiceCode);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return ["response" => $serviceResponse];
	}
}

?>
