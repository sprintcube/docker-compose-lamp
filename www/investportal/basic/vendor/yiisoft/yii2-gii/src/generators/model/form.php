<?php

use yii\gii\generators\model\Generator;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\model\Generator */

echo $form->field($generator, 'db');
echo $form->field($generator, 'useTablePrefix')->checkbox();
echo $form->field($generator, 'useSchemaName')->checkbox();
echo $form->field($generator, 'tableName')->textInput([
    'autocomplete' => 'off',
    'data' => [
        'table-prefix' => $generator->getTablePrefix(), 
        'action' => Url::to(['default/action', 'id' => 'model', 'name' => 'GenerateClassName'])
    ]
]);
echo $form->field($generator, 'standardizeCapitals')->checkbox();
echo $form->field($generator, 'singularize')->checkbox();
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'ns');
echo $form->field($generator, 'baseClass');
echo $form->field($generator, 'generateRelations')->dropDownList([
    Generator::RELATIONS_NONE => 'No relations',
    Generator::RELATIONS_ALL => 'All relations',
    Generator::RELATIONS_ALL_INVERSE => 'All relations with inverse',
]);
echo $form->field($generator, 'generateJunctionRelationMode')->dropDownList([
    Generator::JUNCTION_RELATION_VIA_TABLE => 'Via Table',
    Generator::JUNCTION_RELATION_VIA_MODEL => 'Via Model',
]);
echo $form->field($generator, 'generateRelationsFromCurrentSchema')->checkbox();
echo $form->field($generator, 'generateLabelsFromComments')->checkbox();
echo $form->field($generator, 'generateQuery')->checkbox();
echo $form->field($generator, 'queryNs');
echo $form->field($generator, 'queryClass');
echo $form->field($generator, 'queryBaseClass');
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'messageCategory');
