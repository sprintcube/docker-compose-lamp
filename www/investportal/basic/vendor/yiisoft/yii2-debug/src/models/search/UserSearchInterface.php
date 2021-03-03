<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\models\search;

use yii\data\DataProviderInterface;
use yii\web\IdentityInterface;

/**
 * UserSearchInterface is the interface that should be implemented by a class
 * providing identity information and search method.
 *
 * @author Semen Dubina <yii2debug@sam002.net>
 * @since 2.0.10
 */
interface UserSearchInterface extends IdentityInterface
{
    /**
     * Creates data provider instance with search query applied.
     * @param array $params the data array to load model.
     * @return DataProviderInterface
     */
    public function search($params);
}
