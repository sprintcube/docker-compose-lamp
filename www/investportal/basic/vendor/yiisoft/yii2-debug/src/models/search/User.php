<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Search model for implementation of IdentityInterface
 *
 * @author Semen Dubina <yii2debug@sam002.net>
 * @since 2.0.10
 */
class User extends Model
{
    /**
     * @var Model implementation of IdentityInterface
     */
    public $identityImplement = null;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (\Yii::$app->user && \Yii::$app->user->identityClass) {
            $identityImplementation = new \Yii::$app->user->identityClass();
            if ($identityImplementation instanceof Model) {
                $this->identityImplement = $identityImplementation;
            }
        }
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        return $this->identityImplement->__get($name);
    }

    /**
     * {@inheritdoc}
     */
    public function __set($name, $value)
    {
        return $this->identityImplement->__set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [[array_keys($this->identityImplement->getAttributes()), 'safe']];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return $this->identityImplement->attributes();
    }

    /**
     * {@inheritdoc}
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params)
    {
        if ($this->identityImplement instanceof ActiveRecord) {
            return $this->searchActiveDataProvider($params);
        }

        return null;
    }

    /**
     * Search method for ActiveRecord
     * @param array $params the data array to load model.
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    private function searchActiveDataProvider($params)
    {
        /** @var ActiveRecord $model */
        $model = $this->identityImplement;
        $query = $model::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        foreach ($model::getTableSchema()->columns as $attribute => $column) {
            if ($column->phpType === 'string') {
                $query->andFilterWhere(['like', $attribute, $model->getAttribute($attribute)]);
            } else {
                $query->andFilterWhere([$attribute => $model->getAttribute($attribute)]);
            }
        }

        return $dataProvider;
    }
}
