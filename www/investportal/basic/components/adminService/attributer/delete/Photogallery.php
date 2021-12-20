<?php
namespace app\components\adminService\attributer\delete;

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
		$serviceResponse = '';
		
		if($q['photogallery']){
											
			if($hive->getIterator('UPDATE '. $attributeId .' SET '. $pm['field'] .'=\' \' WHERE id='. $pm['fieldID'])){
				$serviceResponse = 'Photogallery in current attribute deleted!';
			}
			else{
				$statusServiceCode = '502 Bad Gateway';
				$serviceResponse = 'DBA Service Error';
			}
		}
		else{
			$statusServiceCode = '404 Not Found';
			$serviceResponse = 'Query not found!';
		}
		
		header("HTTP/1.1 ". $statusServiceCode);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return ["response" => $serviceResponse];
	}
}

?>
