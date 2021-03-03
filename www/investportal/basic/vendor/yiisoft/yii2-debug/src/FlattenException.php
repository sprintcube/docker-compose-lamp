<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug;

/**
 * FlattenException wraps a PHP Exception to be able to serialize it.
 * Implements the Throwable interface
 * Basically, this class removes all objects from the trace.
 * Ported from Symfony components @link https://github.com/symfony/symfony/blob/master/src/Symfony/Component/Debug/Exception/FlattenException.php
 *
 * @author Dmitry Bashkarev <dmitry@bashkarev.com>
 * @since 2.0.10
 */
class FlattenException
{
    /**
     * @var string
     */
    protected $message;
    /**
     * @var mixed|int
     */
    protected $code;
    /**
     * @var string
     */
    protected $file;
    /**
     * @var int
     */
    protected $line;

    /**
     * @var FlattenException|null
     */
    private $_previous;
    /**
     * @var array
     */
    private $_trace;
    /**
     * @var string
     */
    private $_toString;
    /**
     * @var string
     */
    private $_class;


    /**
     * FlattenException constructor.
     * @param \Exception $exception
     */
    public function __construct(\Exception $exception)
    {
        $this->setMessage($exception->getMessage());
        $this->setCode($exception->getCode());
        $this->setFile($exception->getFile());
        $this->setLine($exception->getLine());
        $this->setTrace($exception->getTrace());
        $this->setToString($exception->__toString());
        $this->setClass(get_class($exception));

        $previous = $exception->getPrevious();
        if ($previous instanceof \Exception) {
            $this->setPrevious(new self($previous));
        }
    }

    /**
     * Gets the Exception message
     * @return string the Exception message as a string.
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Gets the Exception code
     * @return mixed|int the exception code as integer.
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Gets the file in which the exception occurred
     * @return string the filename in which the exception was created.
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Gets the line in which the exception occurred
     * @return int the line number where the exception was created.
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Gets the stack trace
     * @return array the Exception stack trace as an array.
     */
    public function getTrace()
    {
        return $this->_trace;
    }

    /**
     * Returns previous Exception
     * @return FlattenException the previous `FlattenException` if available or null otherwise.
     */
    public function getPrevious()
    {
        return $this->_previous;
    }

    /**
     * Gets the stack trace as a string
     * @return string the Exception stack trace as a string.
     */
    public function getTraceAsString()
    {
        $remove = "Stack trace:\n";
        $len = strpos($this->_toString, $remove);
        if ($len === false) {
            return '';
        }
        return substr($this->_toString, $len + strlen($remove));
    }

    /**
     * String representation of the exception
     * @return string the string representation of the exception.
     */
    public function __toString()
    {
        return $this->_toString;
    }

    /**
     * @return string the name of the class in which the exception was created.
     */
    public function getClass()
    {
        return $this->_class;
    }

    /**
     * @param string $message the Exception message as a string.
     */
    protected function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param mixed|int $code the exception code as integer.
     */
    protected function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $file the filename in which the exception was created.
     */
    protected function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @param int $line the line number where the exception was created.
     */
    protected function setLine($line)
    {
        $this->line = $line;
    }

    /**
     * @param array $trace the Exception stack trace as an array.
     */
    protected function setTrace($trace)
    {
        $this->_trace = [];
        foreach ($trace as $entry) {
            $class = '';
            $namespace = '';
            if (isset($entry['class'])) {
                $parts = explode('\\', $entry['class']);
                $class = array_pop($parts);
                $namespace = implode('\\', $parts);
            }

            $this->_trace[] = [
                'namespace' => $namespace,
                'short_class' => $class,
                'class' => isset($entry['class']) ? $entry['class'] : '',
                'type' => isset($entry['type']) ? $entry['type'] : '',
                'function' => isset($entry['function']) ? $entry['function'] : null,
                'file' => isset($entry['file']) ? $entry['file'] : null,
                'line' => isset($entry['line']) ? $entry['line'] : null,
                'args' => isset($entry['args']) ? $this->flattenArgs($entry['args']) : [],
            ];
        }
    }

    /**
     * @param string $string the string representation of the thrown object.
     */
    protected function setToString($string)
    {
        $this->_toString = $string;
    }

    /**
     * @param FlattenException $previous previous Exception.
     */
    protected function setPrevious(FlattenException $previous)
    {
        $this->_previous = $previous;
    }

    /**
     * @param string $class the name of the class in which the exception was created.
     */
    protected function setClass($class)
    {
        $this->_class = $class;
    }

    /**
     * Allows you to sterilize the Exception trace arguments
     * @param array $args
     * @param int $level recursion level
     * @param int $count number of records counter
     * @return array arguments tracing.
     */
    private function flattenArgs($args, $level = 0, &$count = 0)
    {
        $result = [];
        foreach ($args as $key => $value) {
            if (++$count > 10000) {
                return ['array', '*SKIPPED over 10000 entries*'];
            }
            if ($value instanceof \__PHP_Incomplete_Class) {
                // is_object() returns false on PHP<=7.1
                $result[$key] = ['incomplete-object', $this->getClassNameFromIncomplete($value)];
            } elseif (is_object($value)) {
                $result[$key] = ['object', get_class($value)];
            } elseif (is_array($value)) {
                if ($level > 10) {
                    $result[$key] = ['array', '*DEEP NESTED ARRAY*'];
                } else {
                    $result[$key] = ['array', $this->flattenArgs($value, $level + 1, $count)];
                }
            } elseif (null === $value) {
                $result[$key] = ['null', null];
            } elseif (is_bool($value)) {
                $result[$key] = ['boolean', $value];
            } elseif (is_int($value)) {
                $result[$key] = ['integer', $value];
            } elseif (is_float($value)) {
                $result[$key] = ['float', $value];
            } elseif (is_resource($value)) {
                $result[$key] = ['resource', get_resource_type($value)];
            } else {
                $result[$key] = ['string', (string)$value];
            }
        }

        return $result;
    }

    /**
     * @param \__PHP_Incomplete_Class $value
     * @return string the real class name of an incomplete class
     */
    private function getClassNameFromIncomplete(\__PHP_Incomplete_Class $value)
    {
        $array = new \ArrayObject($value);

        return $array['__PHP_Incomplete_Class_Name'];
    }
}
