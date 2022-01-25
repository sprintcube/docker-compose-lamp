<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class ObjectFilter extends ActiveRecord{
	
	public function rules(){
		return [
			[['name','field','type'],'required']
		];
	}
	public static function tableName(){ return 'objectdata_filters'; }
}
?>
