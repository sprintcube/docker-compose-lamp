<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use app\models\User;

class SignOut extends Component{
	public function init(){ parent::init(); }

	public function proccess(){
		$out = Yii::$app()->user->logout();

		if($out){ echo 'Sign account out success!'; }
		else{ 
			header($_SERVER['SERVER_PROTOCOL'] ." 409 Conflict");
			echo 'The portal accounting service is temporarily unavailable! Try again later;-('; 
		}
	}

}
?>
