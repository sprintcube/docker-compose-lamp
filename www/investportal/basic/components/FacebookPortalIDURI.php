<?php
namespace app\components;

use Yii;
use yii\base\Component;

class FacebookPortalIDURI extends Component{
	public $domain;
	public function init(){
		parent::init();
		$this->domain = '';
	}
	
	public function generate($domain = null){
		if($domain != ''){
			$this->domain = $domain;
		}
		
		switch($this->domain){
			case 'zolotaryow.aplex.ru': $u = 'http://zolotaryow.aplex.ru/investportal/'; break;
			default: $u = 'http://investportal.aplex/'; break;
		}
		
		return $u . '/accounts/fb';
	}
	
}
