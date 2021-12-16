<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class User extends ActiveRecord{
	public function rules(){
		return [
			[['login','password','firstname','surname','email','phone','country'],'required'],
			[
				['login','match','pattern' => ' /^[a-zA-Z0-9_.]{1,30}$/'],
				['firstname','match','pattern' => '/^[a-zA-Z]+$/'],
				['surname','match','pattern' => '/^[a-zA-Z]+$/'],
				['email','match','pattern' => '/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9]{1}[-0-9\.]{1,}[0-9]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u'],
				['phone','match','pattern' => '/^([+]?[0-9\s-\(\)]{3,25})*$/i']
			]
		];
	}
	public static function tableName(){ return 'users'; }
}
?>
