<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;

class adminSignOut extends Component{
	public function init(){ parent::init(); }
	
	public function proccess(){
		
		$sessionData = Yii::$app->session;
		
		if($sessionData->isActive){
			$svc = $this->adminControl($sessionData->destroy() && $sessionData->close());
			switch($svc['state']){
				case 0: header('Location: /admin/auth'); break;
				default:
					$res = '<script>let problem=alert("The portal administration accounting service is temporarily unavailable! Try again later;-("),res="";res=problem?"/":"/",window.location.assign(res);</script>';
					header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
					echo $res;
				break;
			}
		}
		
		
		
		
	}
	protected function adminControl($wayData){
		$response = ['state' => 2];
		
		if($wayData){ $response['state'] = 0; }
		else{ $response['state'] = 1; }
		
		return $response;
		
	}
}

?>
