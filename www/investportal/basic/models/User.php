<?php
namespace app\models\UserService;
use yii\db\ActiveRecord;

class User extends ActiveRecord{
	public function rules(){
		return [
			[['login','password','firstname','surname','email','phone','country','fbToken'],'required'],
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
	public static function tableName(){ return 'senderCodes'; }
}

class Admin extends ActiveRecord{
	public function rules(){
		return [
			[['login','password','firstname','surname','email','role','country'],'required'],
			[
				['login','match','pattern' => ' /^[a-zA-Z0-9_.]{1,30}$/'],
				['firstname','match','pattern' => '/^[a-zA-Z]+$/'],
				['surname','match','pattern' => '/^[a-zA-Z]+$/'],
				['email','match','pattern' => '/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9]{1}[-0-9\.]{1,}[0-9]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u']
			]
		];
	}
	public static function tableName(){ return 'portalAdmins'; }
}
?>
