<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;
use yii\web\Url;
use yii\helpers\Json;


class AdminController extends Controller{
	public function beforeAction($action) { 
		$this->enableCsrfValidation = false; 
		return parent::beforeAction($action); 
	}
    public function actionIndex(){
		$sessionData = Yii::$app->session;
		if(!$sessionData->isActive && !$sessionData->get('adminUser')){ header('Location: /admin/auth'); }
		else{
			$pgUI = '';
			$this->layout = "adminPortal";
			$this->view->registerCssFile("/css/admin/admin.css");

			if($_GET['svc']){
				$service = $_GET['svc'];

				if($service == "dataManagment"){
					if($_GET['subSVC']){
						 switch($_GET['subSVC']){
							 case "filters": 
								$service = 'dataFilters'; 
								$this->view->registerJsFile("/js/react/admin/addons/validParams.js", ['position' => View::POS_END]);
							break;
							 default: $service = 'dataAttributes'; break;
						 }
					 }
				}

				$pgUI = $service;
			}
			else{ $pgUI = 'dashboard'; }

			$this->view->registerCssFile("/css/admin/pages/". $pgUI .".css");
			

			return $this->render('admin',['pgUI' => $pgUI]);
		}
	}
	public function actionAuth(){
		$sessionData = Yii::$app->session;
		if($sessionData->isActive && $sessionData->get('adminUser')){ header('Location: '. Url::to(['admin/index'])); }
		else{
			$this->layout = "adminAuth";
			$this->view->registerCssFile("/css/admin/auth.css");
			
			if($_POST['username']){
				$u = $_POST['username'];
				$p = $_POST['pass'];
				
				$query = ['admin' => $u, 'password' => $p];
				
				Yii::$app->asLogin->proccess($query);
			}
		}
	
		return $this->render('auth');
	}
	public function actionAdminService($svc, $subSVC){
		$serviceResponse = [];
		$statusServiceCode = '200 OK';
		switch($svc){
			case "dataServices":
				if($subSVC == "filters"){
					if($_POST['svcQuery']){
						$q = Json::decode($_POST['svcQuery']);
						$pm = $q['parameters'];
						
						switch($q['command']){
							case 0:
								//Команда добавления данных в текущий фрагмент

								switch($q['command']['subCMD']){
									case "sendFilters":
										
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'type' => $pm['type'],
											'field' => $pm['field']
										];
											
										Yii::$app->adminAPI->send->f->run($q);
							
									break;
									case "sendDatasets":
										$q = [];
										if($pm['isSmartDS']){
											$q = [
												'attribute' => lowercase($pm['attribute']),
												'isSmartDS' => $pm['isSmartDS'],
												'field' => $pm['field']
											];
										}
										else{
											$q = [
												'attribute' => lowercase($pm['attribute']),
												'dataset' => $pm['dataset'],
												'field' => $pm['field']
											];
										}
											
										Yii::$app->adminAPI->send->ds->run($q);
										
									break;
									case "sendPhotogallery":
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'field' => $pm['field']
										];
										$p = [
											'photogallery' => $q['photogallery'],
											'format' => $q['format'],
											'count' => $q['count']
										];
											
										Yii::$app->adminAPI->send->p->run($q, $p);
									break;
									case "sendParameters":
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'dataParam' => $pm['dataParam'],
											'field' => $pm['field']
										];
										
										$p = [
											'costData' => $q['costData'],
											'intData' => $q['intData'],
											'textData' => $q['textData'],
											'selectingData' => $q['selectingData'],
											'precentableData' => $q['precentableData']
										];
											
										Yii::$app->adminAPI->send->pm->run($q, $p);
										
									break;
									default:
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'group' => $pm['group'],
											'metadata' => $pm['metadata']
										];
											
										Yii::$app->adminAPI->send->a->run($q);
									break;
								}

								
							break;
							case 1:
								//Команда обновления данных в текущем фрагменте

								switch($q['command']['subCMD']){
									case "updateFilters":
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'type' => $pm['type'],
											'field' => $pm['field'],
											'newType' => $pm['newType'],
											'newField' => $pm['newField']
										];
											
										Yii::$app->adminAPI->update->f->run($q);
									break;
									case "updatePhotogallery":
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'field' => $pm['field'],
											'fieldId' => $pm['fieldId']
										];
										$p = [
											'photogallery' => $q['photogallery'],
											'format' => $q['format'],
											'count' => $q['count']
										];
											
										Yii::$app->adminAPI->update->p->run($q, $p);
									break;
									case "updateDatasets":
										$q = [];
										if($pm['isSmartDS']){
											$q = [
												'attribute' => lowercase($pm['attribute']),
												'isSmartDS' => $pm['isSmartDS'],
												'field' => $pm['field'],
												'fieldId' => $pm['fieldId']
											];
										}
										else{
											$q = [
												'attribute' => lowercase($pm['attribute']),
												'isSmartDS' => $pm['isSmartDS'],
												'field' => $pm['field'],
												'fieldId' => $pm['fieldId']
											];
										}
											
										Yii::$app->adminAPI->update->ds->run($q);
									break;
									case "updateParameters":
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'dataParam' => $pm['dataParam'],
											'field' => $pm['field'],
											'fieldId' => $pm['fieldId']
										];
										
										$p = [
											'costData' => $q['costData'],
											'intData' => $q['intData'],
											'textData' => $q['textData'],
											'selectingData' => $q['selectingData'],
											'precentableData' => $q['precentableData']
										];
											
										Yii::$app->adminAPI->update->pm->run($q, $p);
									break;
									default:
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'attributeNewId' => lowercase($pm['attributeNewId'])
										];
											
										Yii::$app->adminAPI->update->a->run($q);
									break;
								}
							break;
							case 2:
								//Команда удаления данных из текущего фрагмента

								switch($q['command']['subCMD']){
									case "deleteFilters":
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'field' => $pm['field']
										];
											
										Yii::$app->adminAPI->delete->f->run($q);
									break;
									case "deleteDatasets":
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'field' => $pm['field'],
											'fieldId' => $pm['fieldId']
										];
											
										Yii::$app->adminAPI->delete->ds->run($q);
									break;
									case "deletePhotogallery":
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'field' => $pm['field'],
											'fieldId' => $pm['fieldId']
										];
											
										Yii::$app->adminAPI->delete->p->run($q);
									break;
									case "deleteParameters":
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'field' => $pm['field'],
											'fieldId' => $pm['fieldId']
										];
											
										Yii::$app->adminAPI->delete->pm->run($q);
									break;
									default:
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'group' => $pm['group']
										];
											
										Yii::$app->adminAPI->delete->a->run($q);
									break;
								}
							break;
							case 3:
								//Команда вывода данных в текущем фрагменте

								switch($q['command']['subCMD']){
									case "showFilters":
										$q = ['attribute' => lowercase($pm['attribute'])];
											
										Yii::$app->adminAPI->show->f->run($q);
									break;
									case "showDatasets":
										$q = [];
										if($pm['isSmartDS']){
											$q = [
												'attribute' => lowercase($pm['attribute']),
												'isSmartDS' => $pm['isSmartDS'],
												'field' => $pm['field']
											];
										}
										else{
											$q = [
												'attribute' => lowercase($pm['attribute']),
												'dataset' => $pm['dataset'],
												'field' => $pm['field']
											];
										}
											
										Yii::$app->adminAPI->show->ds->run($q);
									break;
									case "showPhotogallery":
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'field' => $pm['field']
										];
											
										Yii::$app->adminAPI->show->p->run($q);
									break;
									case "showParameters":
										$q = [
											'attribute' => lowercase($pm['attribute']),
											'dataParam' => $pm['dataParam'],
											'field' => $pm['field'],
											'costData' => $pm['costData'],
											'intData' => $pm['intData'],
											'textData' => $pm['textData'],
											'selectingData' => $pm['selectingData'],
											'precentableData' => $pm['precentableData']
										];
										
										
											
										Yii::$app->adminAPI->show->pm->run($q);
									break;
									case "showTableColumns":
										$q = ['attribute' => lowercase($pm['attribute'])];
											
										Yii::$app->adminAPI->show->tc->run($q);
									break;
									default:
										$q = ['attribute' => lowercase($pm['attribute'])];
											
										Yii::$app->adminAPI->show->a->run($q);
									break;
								}
								
							break;
						}
					}
				}
				else{
					$statusServiceCode = '404 Not Found';
					$serviceResponse = "Not query found!";
				}
			break;
			
		}
		
		
		
	}
}
?>
