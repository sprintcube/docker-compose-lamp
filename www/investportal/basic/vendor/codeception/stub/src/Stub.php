<?php
namespace Codeception;

use Codeception\Stub\ConsecutiveMap;
use Codeception\Stub\StubMarshaler;
use PHPUnit\Framework\MockObject\Generator;
use PHPUnit\Framework\MockObject\Rule\AnyInvokedCount;
use PHPUnit\Framework\MockObject\Stub\ConsecutiveCalls;
use PHPUnit\Framework\MockObject\Stub\ReturnCallback;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;

class Stub
{
    public static $magicMethods = ['__isset', '__get', '__set'];

    /**
     * Instantiates a class without executing a constructor.
     * Properties and methods can be set as a second parameter.
     * Even protected and private properties can be set.
     *
     * ``` php
     * <?php
     * Stub::make('User');
     * Stub::make('User', ['name' => 'davert']);
     * ?>
     * ```
     *
     * Accepts either name of class or object of that class
     *
     * ``` php
     * <?php
     * Stub::make(new User, ['name' => 'davert']);
     * ?>
     * ```
     *
     * To replace method provide it's name as a key in second parameter
     * and it's return value or callback function as parameter
     *
     * ``` php
     * <?php
     * Stub::make('User', ['save' => function () { return true; }]);
     * Stub::make('User', ['save' => true]);
     * ?>
     * ```
     *
     * **To create a mock, pass current testcase name as last argument:**
     *
     * ```php
     * <?php
     * Stub::make('User', [
     *      'save' => \Codeception\Stub\Expected::once()
     * ], $this);
     * ```
     *
     * @template RealInstanceType of object
     * @param class-string<RealInstanceType>|RealInstanceType|callable(): class-string<RealInstanceType> $class - A class to be mocked
     * @param array $params - properties and methods to set
     * @param bool|\PHPUnit\Framework\TestCase $testCase
     *
     * @return \PHPUnit\Framework\MockObject\MockObject&RealInstanceType - mock
     * @throws \RuntimeException when class does not exist
     * @throws \Exception
     */
    public static function make($class, $params = [], $testCase = false)
    {
        $class = self::getClassname($class);
        if (!class_exists($class)) {
            if (interface_exists($class)) {
                throw new \RuntimeException("Stub::make can't mock interfaces, please use Stub::makeEmpty instead.");
            }
            throw new \RuntimeException("Stubbed class $class doesn't exist.");
        }

        $reflection = new \ReflectionClass($class);
        $callables = self::getMethodsToReplace($reflection, $params);
        if ($reflection->isAbstract()) {
            $arguments = empty($callables) ? [] : array_keys($callables);
            $mock = self::generateMockForAbstractClass($class, $arguments, '', false, $testCase);
        } else {
            $arguments = empty($callables) ? null : array_keys($callables);
            $mock = self::generateMock($class, $arguments, [], '', false, $testCase);
        }

        self::bindParameters($mock, $params);

        return self::markAsMock($mock, $reflection);
    }

    /**
     * Set __mock flag, if at all possible
     *
     * @param object $mock
     * @param \ReflectionClass $reflection
     * @return object
     */
    private static function markAsMock($mock, \ReflectionClass $reflection)
    {
        if (!$reflection->hasMethod('__set')) {
            $mock->__mocked = $reflection->getName();
        }
        return $mock;
    }

    /**
     * Creates $num instances of class through `Stub::make`.
     *
     * @param mixed $class
     * @param int $num
     * @param array $params
     *
     * @return array
     * @throws \Exception
     */
    public static function factory($class, $num = 1, $params = [])
    {
        $objects = [];
        for ($i = 0; $i < $num; $i++) {
            $objects[] = self::make($class, $params);
        }

        return $objects;
    }

    /**
     * Instantiates class having all methods replaced with dummies except one.
     * Constructor is not triggered.
     * Properties and methods can be replaced.
     * Even protected and private properties can be set.
     *
     * ``` php
     * <?php
     * Stub::makeEmptyExcept('User', 'save');
     * Stub::makeEmptyExcept('User', 'save', ['name' => 'davert']);
     * ?>
     * ```
     *
     * Accepts either name of class or object of that class
     *
     * ``` php
     * <?php
     * * Stub::makeEmptyExcept(new User, 'save');
     * ?>
     * ```
     *
     * To replace method provide it's name as a key in second parameter
     * and it's return value or callback function as parameter
     *
     * ``` php
     * <?php
     * Stub::makeEmptyExcept('User', 'save', ['isValid' => function () { return true; }]);
     * Stub::makeEmptyExcept('User', 'save', ['isValid' => true]);
     * ?>
     * ```
     *
     * **To create a mock, pass current testcase name as last argument:**
     *
     * ```php
     * <?php
     * Stub::makeEmptyExcept('User', 'validate', [
     *      'save' => \Codeception\Stub\Expected::once()
     * ], $this);
     * ```
     * @template
     * @param class-string<RealInstanceType>|RealInstanceType|callable(): class-string<RealInstanceType> $class - A class to be mocked
     * @param string $method
     * @param array $params
     * @param bool|\PHPUnit\Framework\TestCase $testCase
     *
     * @return \PHPUnit\Framework\MockObject\MockObject&RealInstanceType
     * @throws \Exception
     */
    public static function makeEmptyExcept($class, $method, $params = [], $testCase = false)
    {
        $class = self::getClassname($class);
        $reflectionClass = new \ReflectionClass($class);

        $methods = $reflectionClass->getMethods();

        $methods = array_filter(
            $methods,
            function ($m) {
                return !in_array($m->name, Stub::$magicMethods);
            }
        );

        $methods = array_filter(
            $methods,
            function ($m) use ($method) {
                return $method != $m->name;
            }
        );

        $methods = array_map(
            function ($m) {
                return $m->name;
            },
            $methods
        );

        $methods = count($methods) ? $methods : null;
        $mock = self::generateMock($class, $methods, [], '', false, $testCase);
        self::bindParameters($mock, $params);

        return self::markAsMock($mock, $reflectionClass);
    }

    /**
     * Instantiates class having all methods replaced with dummies.
     * Constructor is not triggered.
     * Properties and methods can be set as a second parameter.
     * Even protected and private properties can be set.
     *
     * ``` php
     * <?php
     * Stub::makeEmpty('User');
     * Stub::makeEmpty('User', ['name' => 'davert']);
     * ```
     *
     * Accepts either name of class or object of that class
     *
     * ``` php
     * <?php
     * Stub::makeEmpty(new User, ['name' => 'davert']);
     * ```
     *
     * To replace method provide it's name as a key in second parameter
     * and it's return value or callback function as parameter
     *
     * ``` php
     * <?php
     * Stub::makeEmpty('User', ['save' => function () { return true; }]);
     * Stub::makeEmpty('User', ['save' => true]);
     * ```
     *
     * **To create a mock, pass current testcase name as last argument:**
     *
     * ```php
     * <?php
     * Stub::makeEmpty('User', [
     *      'save' => \Codeception\Stub\Expected::once()
     * ], $this);
     * ```
     *
     * @template RealInstanceType of object
     * @param class-string<RealInstanceType>|RealInstanceType|callable(): class-string<RealInstanceType> $class - A class to be mocked
     * @param array $params
     * @param bool|\PHPUnit\Framework\TestCase $testCase
     *
     * @return \PHPUnit\Framework\MockObject\MockObject&RealInstanceType
     * @throws \Exception
     */
    public static function makeEmpty($class, $params = [], $testCase = false)
    {
        $class = self::getClassname($class);
        $reflection = new \ReflectionClass($class);

        $methods = get_class_methods($class);
        $methods = array_filter(
            $methods,
            function ($i) {
                return !in_array($i, Stub::$magicMethods);
            }
        );
        $mock = self::generateMock($class, $methods, [], '', false, $testCase);
        self::bindParameters($mock, $params);

        return self::markAsMock($mock, $reflection);
    }

    /**
     * Clones an object and redefines it's properties (even protected and private)
     *
     * @param       $obj
     * @param array $params
     *
     * @return mixed
     * @throws \Exception
     */
    public static function copy($obj, $params = [])
    {
        $copy = clone($obj);
        self::bindParameters($copy, $params);

        return $copy;
    }

    /**
     * Instantiates a class instance by running constructor.
     * Parameters for constructor passed as second argument
     * Properties and methods can be set in third argument.
     * Even protected and private properties can be set.
     *
     * ``` php
     * <?php
     * Stub::construct('User', ['autosave' => false]);
     * Stub::construct('User', ['autosave' => false], ['name' => 'davert']);
     * ?>
     * ```
     *
     * Accepts either name of class or object of that class
     *
     * ``` php
     * <?php
     * Stub::construct(new User, ['autosave' => false], ['name' => 'davert']);
     * ?>
     * ```
     *
     * To replace method provide it's name as a key in third parameter
     * and it's return value or callback function as parameter
     *
     * ``` php
     * <?php
     * Stub::construct('User', [], ['save' => function () { return true; }]);
     * Stub::construct('User', [], ['save' => true]);
     * ?>
     * ```
     *
     * **To create a mock, pass current testcase name as last argument:**
     *
     * ```php
     * <?php
     * Stub::construct('User', [], [
     *      'save' => \Codeception\Stub\Expected::once()
     * ], $this);
     * ```
     *
     * @template RealInstanceType of object
     * @param class-string<RealInstanceType>|RealInstanceType|callable(): class-string<RealInstanceType> $class - A class to be mocked
     * @param array $constructorParams
     * @param array $params
     * @param bool|\PHPUnit\Framework\TestCase $testCase
     *
     * @return \PHPUnit\Framework\MockObject\MockObject&RealInstanceType
     * @throws \Exception
     */
    public static function construct($class, $constructorParams = [], $params = [], $testCase = false)
    {
        $class = self::getClassname($class);
        $reflection = new \ReflectionClass($class);

        $callables = self::getMethodsToReplace($reflection, $params);

        $arguments = empty($callables) ? null : array_keys($callables);
        $mock = self::generateMock($class, $arguments, $constructorParams, $testCase);
        self::bindParameters($mock, $params);

        return self::markAsMock($mock, $reflection);
    }

    /**
     * Instantiates a class instance by running constructor with all methods replaced with dummies.
     * Parameters for constructor passed as second argument
     * Properties and methods can be set in third argument.
     * Even protected and private properties can be set.
     *
     * ``` php
     * <?php
     * Stub::constructEmpty('User', ['autosave' => false]);
     * Stub::constructEmpty('User', ['autosave' => false], ['name' => 'davert']);
     * ```
     *
     * Accepts either name of class or object of that class
     *
     * ``` php
     * <?php
     * Stub::constructEmpty(new User, ['autosave' => false], ['name' => 'davert']);
     * ```
     *
     * To replace method provide it's name as a key in third parameter
     * and it's return value or callback function as parameter
     *
     * ``` php
     * <?php
     * Stub::constructEmpty('User', [], ['save' => function () { return true; }]);
     * Stub::constructEmpty('User', [], ['save' => true]);
     * ```
     *
     * **To create a mock, pass current testcase name as last argument:**
     *
     * ```php
     * <?php
     * Stub::constructEmpty('User', [], [
     *      'save' => \Codeception\Stub\Expected::once()
     * ], $this);
     * ```
     *
     * @template RealInstanceType of object
     * @param class-string<RealInstanceType>|RealInstanceType|callable(): class-string<RealInstanceType> $class - A class to be mocked
     * @param array $constructorParams
     * @param array $params
     * @param bool|\PHPUnit\Framework\TestCase $testCase
     *
     * @return \PHPUnit\Framework\MockObject\MockObject&RealInstanceType
     */
    public static function constructEmpty($class, $constructorParams = [], $params = [], $testCase = false)
    {
        $class = self::getClassname($class);
        $reflection = new \ReflectionClass($class);

        $methods = get_class_methods($class);
        $methods = array_filter(
            $methods,
            function ($i) {
                return !in_array($i, Stub::$magicMethods);
            }
        );
        $mock = self::generateMock($class, $methods, $constructorParams, $testCase);
        self::bindParameters($mock, $params);

        return self::markAsMock($mock, $reflection);
    }

    /**
     * Instantiates a class instance by running constructor with all methods replaced with dummies, except one.
     * Parameters for constructor passed as second argument
     * Properties and methods can be set in third argument.
     * Even protected and private properties can be set.
     *
     * ``` php
     * <?php
     * Stub::constructEmptyExcept('User', 'save');
     * Stub::constructEmptyExcept('User', 'save', ['autosave' => false], ['name' => 'davert']);
     * ?>
     * ```
     *
     * Accepts either name of class or object of that class
     *
     * ``` php
     * <?php
     * Stub::constructEmptyExcept(new User, 'save', ['autosave' => false], ['name' => 'davert']);
     * ?>
     * ```
     *
     * To replace method provide it's name as a key in third parameter
     * and it's return value or callback function as parameter
     *
     * ``` php
     * <?php
     * Stub::constructEmptyExcept('User', 'save', [], ['save' => function () { return true; }]);
     * Stub::constructEmptyExcept('User', 'save', [], ['save' => true]);
     * ?>
     * ```
     *
     * **To create a mock, pass current testcase name as last argument:**
     *
     * ```php
     * <?php
     * Stub::constructEmptyExcept('User', 'save', [], [
     *      'save' => \Codeception\Stub\Expected::once()
     * ], $this);
     * ```
     *
     * @template RealInstanceType of object
     * @param class-string<RealInstanceType>|RealInstanceType|callable(): class-string<RealInstanceType> $class - A class to be mocked
     * @param string $method
     * @param array $constructorParams
     * @param array $params
     * @param bool|\PHPUnit\Framework\TestCase $testCase
     *
     * @return \PHPUnit\Framework\MockObject\MockObject&RealInstanceType
     */
    public static function constructEmptyExcept(
        $class,
        $method,
        $constructorParams = [],
        $params = [],
        $testCase = false
    ) {
        $class = self::getClassname($class);
        $reflectionClass = new \ReflectionClass($class);
        $methods = $reflectionClass->getMethods();
        $methods = array_filter(
            $methods,
            function ($m) {
                return !in_array($m->name, Stub::$magicMethods);
            }
        );
        $methods = array_filter(
            $methods,
            function ($m) use ($method) {
                return $method != $m->name;
            }
        );
        $methods = array_map(
            function ($m) {
                return $m->name;
            },
            $methods
        );
        $methods = count($methods) ? $methods : null;
        $mock = self::generateMock($class, $methods, $constructorParams, $testCase);
        self::bindParameters($mock, $params);

        return self::markAsMock($mock, $reflectionClass);
    }

    private static function generateMock()
    {
        return self::doGenerateMock(func_get_args());
    }

    /**
     * Returns a mock object for the specified abstract class with all abstract
     * methods of the class mocked. Concrete methods to mock can be specified with
     * the last parameter
     *
     * @return object
     * @since  Method available since Release 1.0.0
     */
    private static function generateMockForAbstractClass()
    {
        return self::doGenerateMock(func_get_args(), true);
    }

    private static function doGenerateMock($args, $isAbstract = false)
    {
        $testCase = self::extractTestCaseFromArgs($args);
        $methodName = $isAbstract ? 'getMockForAbstractClass' : 'getMock';
        $generatorClass = new Generator;

        // using PHPUnit 5.4 mocks registration
        if (version_compare(\PHPUnit\Runner\Version::series(), '5.4', '>=')
            && $testCase instanceof \PHPUnit\Framework\TestCase
        ) {
            $mock = call_user_func_array([$generatorClass, $methodName], $args);
            $testCase->registerMockObject($mock);
            return $mock;
        }
        if ($testCase instanceof  \PHPUnit\Framework\TestCase) {
            $generatorClass = $testCase;
        }
        return call_user_func_array([$generatorClass, $methodName], $args);
    }

    private static function extractTestCaseFromArgs(&$args)
    {
        $argsLength = count($args) - 1;
        $testCase = $args[$argsLength];

        unset($args[$argsLength]);

        return $testCase;
    }

    /**
     * Replaces properties of current stub
     *
     * @param \PHPUnit\Framework\MockObject\MockObject $mock
     * @param array $params
     *
     * @return mixed
     * @throws \LogicException
     */
    public static function update($mock, array $params)
    {
        //do not rely on __mocked property, check typ eof $mock
        if (!$mock instanceof \PHPUnit\Framework\MockObject\MockObject) {
            throw new \LogicException('You can update only stubbed objects');
        }

        self::bindParameters($mock, $params);

        return $mock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mock
     * @param array $params
     * @throws \LogicException
     */
    protected static function bindParameters($mock, $params)
    {
        $reflectionClass = new \ReflectionClass($mock);
        if ($mock instanceof \PHPUnit\Framework\MockObject\MockObject) {
            $parentClass = $reflectionClass->getParentClass();
            if ($parentClass !== false) {
                $reflectionClass = $reflectionClass->getParentClass();
            }
        }

        foreach ($params as $param => $value) {
            // redefine method
            if ($reflectionClass->hasMethod($param)) {
                if ($value instanceof StubMarshaler) {
                    $marshaler = $value;
                    $mock
                        ->expects($marshaler->getMatcher())
                        ->method($param)
                        ->will(new ReturnCallback($marshaler->getValue()));
                } elseif ($value instanceof \Closure) {
                    $mock
                        ->expects(new AnyInvokedCount)
                        ->method($param)
                        ->will(new ReturnCallback($value));
                } elseif ($value instanceof ConsecutiveMap) {
                    $consecutiveMap = $value;
                    $mock
                        ->expects(new AnyInvokedCount)
                        ->method($param)
                        ->will(new ConsecutiveCalls($consecutiveMap->getMap()));
                } else {
                    $mock
                        ->expects(new AnyInvokedCount)
                        ->method($param)
                        ->will(new ReturnStub($value));
                }
            } elseif ($reflectionClass->hasProperty($param)) {
                $reflectionProperty = $reflectionClass->getProperty($param);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($mock, $value);
                continue;
            } else {
                if ($reflectionClass->hasMethod('__set')) {
                    try {
                        $mock->{$param} = $value;
                    } catch (\Exception $e) {
                        throw new \LogicException(
                            sprintf(
                                'Could not add property %1$s, class %2$s implements __set method, '
                                . 'and no %1$s property exists',
                                $param,
                                $reflectionClass->getName()
                            ),
                            $e->getCode(),
                            $e
                        );
                    }
                } else {
                    $mock->{$param} = $value;
                }
                continue;
            }
        }
    }

    /**
     * @todo should be simplified
     */
    protected static function getClassname($object)
    {
        if (is_object($object)) {
            return get_class($object);
        }

        if (is_callable($object)) {
            return call_user_func($object);
        }

        return $object;
    }

    /**
     * @param \ReflectionClass $reflection
     * @param $params
     * @return array
     */
    protected static function getMethodsToReplace(\ReflectionClass $reflection, $params)
    {
        $callables = [];
        foreach ($params as $method => $value) {
            if ($reflection->hasMethod($method)) {
                $callables[$method] = $value;
            }
        }

        return $callables;
    }


    /**
     * Stubbing a method call to return a list of values in the specified order.
     *
     * ``` php
     * <?php
     * $user = Stub::make('User', ['getName' => Stub::consecutive('david', 'emma', 'sam', 'amy')]);
     * $user->getName(); //david
     * $user->getName(); //emma
     * $user->getName(); //sam
     * $user->getName(); //amy
     * ?>
     * ```
     *
     * @return ConsecutiveMap
     */
    public static function consecutive()
    {
        return new ConsecutiveMap(func_get_args());
    }
}
