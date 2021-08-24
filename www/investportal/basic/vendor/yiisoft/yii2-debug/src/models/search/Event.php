<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\models\search;

use yii\data\ArrayDataProvider;
use yii\debug\components\search\Filter;

/**
 * Event
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0.14
 */
class Event extends Base
{
    /**
     * @var bool whether event is static or not.
     */
    public $isStatic;
    public $name;
    public $class;
    public $senderClass;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'class', 'senderClass'], 'string'],
            [['isStatic'], 'boolean'],
            //[['isStatic'], 'filter', 'filter' => function ($value) {return strlen($value) > 0 ? (bool)$value : $value;}],
            [$this->attributes(), 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'class' => 'Class',
            'senderClass' => 'Sender',
            'isStatic' => 'Static',
        ];
    }

    /**
     * Returns data provider with filled models. Filter applied if needed.
     *
     * @param array $params an array of parameter values indexed by parameter names
     * @param array $models data to return provider for
     * @return \yii\data\ArrayDataProvider
     */
    public function search($params, $models)
    {
        $dataProvider = new ArrayDataProvider([
            'allModels' => $models,
            'pagination' => false,
            'sort' => [
                'attributes' => ['time', 'level', 'category', 'message'],
                'defaultOrder' => [
                    'time' => SORT_ASC,
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $filter = new Filter();
        $this->addCondition($filter, 'isStatic');
        $this->addCondition($filter, 'name', true);
        $this->addCondition($filter, 'class', true);
        $this->addCondition($filter, 'senderClass', true);
        $dataProvider->allModels = $filter->filter($models);

        return $dataProvider;
    }
}