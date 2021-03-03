<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\models\router;

use yii\base\Model;
use yii\log\Logger;

/**
 * CurrentRoute model
 *
 * @author Dmitriy Bashkarev <dmitriy@bashkarev.com>
 * @since 2.0.8
 */
class CurrentRoute extends Model
{
    /**
     * @var array logged messages.
     */
    public $messages = [];
    /**
     * @var string logged route.
     */
    public $route = '';
    /**
     * @var string logged action.
     */
    public $action = '';
    /**
     * @var string|null info message.
     */
    public $message;
    /**
     * @var array logged rules.
     * ```php
     * [
     *  [
     *      'rule' => (string),
     *      'match' => (bool),
     *      'parent'=> parent class (string)
     *  ]
     * ]
     * ```
     */
    public $logs = [];
    /**
     * @var int count, before match.
     */
    public $count = 0;
    /**
     * @var bool
     */
    public $hasMatch = false;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $last = null;
        foreach ($this->messages as $message) {
            if ($message[1] === Logger::LEVEL_TRACE && is_string($message[0])) {
                $this->message = $message[0];
            } elseif (isset($message[0]['rule'], $message[0]['match'])) {
                if (!empty($last['parent']) && $last['parent'] === $message[0]['rule']) {
                    continue;
                }
                $this->logs[] = $message[0];
                ++$this->count;
                if ($message[0]['match']) {
                    $this->hasMatch = true;
                }
                $last = $message[0];
            }
        }
    }
}
