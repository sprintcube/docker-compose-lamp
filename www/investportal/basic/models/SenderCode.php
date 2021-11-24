<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class SenderCode extends ActiveRecord{
	public function rules(){
		return [
			[['phone','code','service'],'required'],
			[
				['phone','match','pattern' => '/^([+]?[0-9\s-\(\)]{3,25})*$/i'],
				['code','number', 'max' => 4],
				['service','match','pattern' => '/^[a-z]+$/']
			]
		];
	}
	public static function tableName(){ return '{{senderCodes}}'; }
}
?>
