<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\helpers\StringHelper;

/**
 * Panel is a base class for debugger panel classes. It defines how data should be collected,
 * what should be displayed at debug toolbar and on debugger details view.
 *
 * @property-read string $detail Content that is displayed in debugger detail view. This property is
 * read-only.
 * @property-read string $name Name of the panel. This property is read-only.
 * @property-read string $summary Content that is displayed at debug toolbar. This property is read-only.
 * @property-read string $url URL pointing to panel detail view. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Panel extends Component
{
    /**
     * @var string panel unique identifier.
     * It is set automatically by the container module.
     */
    public $id;
    /**
     * @var string request data set identifier.
     */
    public $tag;
    /**
     * @var Module
     */
    public $module;
    /**
     * @var mixed data associated with panel
     */
    public $data;
    /**
     * @var array array of actions to add to the debug modules default controller.
     * This array will be merged with all other panels actions property.
     * See [[\yii\base\Controller::actions()]] for the format.
     */
    public $actions = [];

    /**
     * @var FlattenException|null Error while saving the panel
     * @since 2.0.10
     */
    protected $error;


    /**
     * @return string name of the panel
     */
    public function getName()
    {
        return '';
    }

    /**
     * @return string content that is displayed at debug toolbar
     */
    public function getSummary()
    {
        return '';
    }

    /**
     * @return string content that is displayed in debugger detail view
     */
    public function getDetail()
    {
        return '';
    }

    /**
     * Saves data to be later used in debugger detail view.
     * This method is called on every page where debugger is enabled.
     *
     * @return mixed data to be saved
     */
    public function save()
    {
        return null;
    }

    /**
     * Loads data into the panel
     *
     * @param mixed $data
     */
    public function load($data)
    {
        $this->data = $data;
    }

    /**
     * @param null|array $additionalParams Optional additional parameters to add to the route
     * @return string URL pointing to panel detail view
     */
    public function getUrl($additionalParams = null)
    {
        $route = [
            '/' . $this->module->id . '/default/view',
            'panel' => $this->id,
            'tag' => $this->tag,
        ];

        if (is_array($additionalParams)) {
            $route = ArrayHelper::merge($route, $additionalParams);
        }

        return Url::toRoute($route);
    }

    /**
     * Returns a trace line
     * @param array $options The array with trace
     * @return string the trace line
     * @since 2.0.7
     */
    public function getTraceLine($options)
    {
        if (!isset($options['text'])) {
            $options['text'] = "{$options['file']}:{$options['line']}";
        }
        $traceLine = $this->module->traceLine;
        if ($traceLine === false) {
            return $options['text'];
        }

        $options['file'] = str_replace('\\', '/', $options['file']);

        foreach ($this->module->tracePathMappings as $old => $new) {
            $old = rtrim(str_replace('\\', '/', $old), '/') . '/';
            if (StringHelper::startsWith($options['file'], $old)) {
                $new = rtrim(str_replace('\\', '/', $new), '/') . '/';
                $options['file'] = $new . substr($options['file'], strlen($old));
                break;
            }
        }

        $rawLink = $traceLine instanceof \Closure ? $traceLine($options, $this) : $traceLine;
        return strtr($rawLink, ['{file}' => $options['file'], '{line}' => $options['line'], '{text}' => $options['text']]);
    }

    /**
     * @param FlattenException $error
     * @since 2.0.10
     */
    public function setError(FlattenException $error)
    {
        $this->error = $error;
    }

    /**
     * @return FlattenException|null
     * @since 2.0.10
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return bool
     * @since 2.0.10
     */
    public function hasError()
    {
        return $this->error !== null;
    }

    /**
     * Checks whether this panel is enabled.
     * @return bool whether this panel is enabled.
     * @since 2.0.10
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * Gets messages from log target and filters according to their categories and levels.
     * @param int $levels the message levels to filter by. This is a bitmap of
     * level values. Value 0 means allowing all levels.
     * @param array $categories the message categories to filter by. If empty, it means all categories are allowed.
     * @param array $except the message categories to exclude. If empty, it means all categories are allowed.
     * @param bool $stringify Convert non-string (such as closures) to strings
     * @return array the filtered messages.
     * @since 2.1.4
     * @see \yii\log\Target::filterMessages()
     */
    protected function getLogMessages($levels = 0, $categories = [], $except = [], $stringify = false)
    {
        $target = $this->module->logTarget;
        $messages = $target->filterMessages($target->messages, $levels, $categories, $except);

        if (!$stringify) {
            return $messages;
        }

        foreach ($messages as &$message) {
            if (!isset($message[0]) || is_string($message[0])) {
                continue;
            }

            // exceptions may not be serializable if in the call stack somewhere is a Closure
            if ($message[0] instanceof \Throwable || $message[0] instanceof \Exception) {
                $message[0] = (string) $message[0];
            } else {
                $message[0] = VarDumper::export($message[0]);
            }
        }

        return $messages;
    }
}
