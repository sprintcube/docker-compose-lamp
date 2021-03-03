<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\components\search\matchers;

/**
 * Checks if the given value is greater than or equal the base one.
 *
 * @author Dmitriy Bashkarev <dmitriy@bashkarev.com>
 * @since 2.0.7
 */
class GreaterThanOrEqual extends Base
{
    /**
     * {@inheritdoc}
     */
    public function match($value)
    {
        return $value >= $this->baseValue;
    }
}
