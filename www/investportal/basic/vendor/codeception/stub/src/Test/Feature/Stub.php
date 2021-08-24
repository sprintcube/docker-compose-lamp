<?php

namespace Codeception\Test\Feature;

/**
 * ### Usage in Codeception
 *
 * Since Codeception 2.3.8 this trait is enabled in `\Codeception\Test\Unit` class.
 *
 * ### Usage in PHPUnit
 *
 * Include this trait into a TestCase to be able to use Stubs and Mocks:
 *
 * ```php
 * <?php
 * class MyTest extends \PHPUnit\Framework\TestCase
 * {
 *      use Codeception\Test\Feature\Stub;
 * }
 * ```
 */
trait Stub
{
    private $mocks;

    protected function stubStart()
    {
        if ($this instanceof \PHPUnit\Framework\TestCase) {
            return;
        }
        $this->mocks = [];
    }

    protected function stubEnd($status, $time)
    {
        if ($this instanceof \PHPUnit\Framework\TestCase) {
            return;
        }
        if ($status !== 'ok') { // Codeception status
            return;
        }

        foreach ($this->mocks as $mockObject) {
            if ($mockObject->__phpunit_hasMatchers()) {
                $this->assertTrue(true); // incrementing assertions
            }

            $mockObject->__phpunit_verify(true);
        }
    }

    /**
     * Instantiates a class without executing a constructor.
     * Properties and methods can be set as a second parameter.
     * Even protected and private properties can be set.
     *
     * ``` php
     * <?php
     * $this->make('User');
     * $this->make('User', ['name' => 'davert']);
     * ?>
     * ```
     *
     * Accepts either name of class or object of that class
     *
     * ``` php
     * <?php
     * $this->make(new User, ['name' => 'davert']);
     * ?>
     * ```
     *
     * To replace method provide it's name as a key in second parameter
     * and it's return value or callback function as parameter
     *
     * ``` php
     * <?php
     * $this->make('User', ['save' => function () { return true; }]);
     * $this->make('User', ['save' => true]);
     * ```
     * @template RealInstanceType of object
     * @param class-string<RealInstanceType>|RealInstanceType|callable(): class-string<RealInstanceType> $class - A class to be mocked
     * @param array $params - properties and methods to set
     *
     * @return \PHPUnit\Framework\MockObject\MockObject&RealInstanceType - mock
     * @throws \RuntimeException when class does not exist
     * @throws \Exception
     */
    public function make($class, $params = [])
    {
        return $this->mocks[] = \Codeception\Stub::make($class, $params, $this);
    }

    /**
     * Instantiates class having all methods replaced with dummies.
     * Constructor is not triggered.
     * Properties and methods can be set as a second parameter.
     * Even protected and private properties can be set.
     *
     * ``` php
     * <?php
     * $this->makeEmpty('User');
     * $this->makeEmpty('User', ['name' => 'davert']);
     * ```
     *
     * Accepts either name of class or object of that class
     *
     * ``` php
     * <?php
     * $this->makeEmpty(new User, ['name' => 'davert']);
     * ```
     *
     * To replace method provide it's name as a key in second parameter
     * and it's return value or callback function as parameter
     *
     * ``` php
     * <?php
     * $this->makeEmpty('User', ['save' => function () { return true; }]);
     * $this->makeEmpty('User', ['save' => true));
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
    public function makeEmpty($class, $params = [])
    {
        return $this->mocks[] = \Codeception\Stub::makeEmpty($class, $params, $this);
    }

    /**
     * Instantiates class having all methods replaced with dummies except one.
     * Constructor is not triggered.
     * Properties and methods can be replaced.
     * Even protected and private properties can be set.
     *
     * ``` php
     * <?php
     * $this->makeEmptyExcept('User', 'save');
     * $this->makeEmptyExcept('User', 'save', ['name' => 'davert']);
     * ?>
     * ```
     *
     * Accepts either name of class or object of that class
     *
     * ``` php
     * <?php
     * * $this->makeEmptyExcept(new User, 'save');
     * ?>
     * ```
     *
     * To replace method provide it's name as a key in second parameter
     * and it's return value or callback function as parameter
     *
     * ``` php
     * <?php
     * $this->makeEmptyExcept('User', 'save', ['isValid' => function () { return true; }]);
     * $this->makeEmptyExcept('User', 'save', ['isValid' => true]);
     * ```
     *
     * @template RealInstanceType of object
     * @param class-string<RealInstanceType>|RealInstanceType|callable(): class-string<RealInstanceType> $class - A class to be mocked
     * @param string $method
     * @param array $params
     *
     * @return \PHPUnit\Framework\MockObject\MockObject&RealInstanceType
     * @throws \Exception
     */
    public function makeEmptyExcept($class, $method, $params = [])
    {
        return $this->mocks[] = \Codeception\Stub::makeEmptyExcept($class, $method, $params, $this);
    }

    /**
     * Instantiates a class instance by running constructor.
     * Parameters for constructor passed as second argument
     * Properties and methods can be set in third argument.
     * Even protected and private properties can be set.
     *
     * ``` php
     * <?php
     * $this->construct('User', ['autosave' => false]);
     * $this->construct('User', ['autosave' => false], ['name' => 'davert']);
     * ?>
     * ```
     *
     * Accepts either name of class or object of that class
     *
     * ``` php
     * <?php
     * $this->construct(new User, ['autosave' => false), ['name' => 'davert']);
     * ?>
     * ```
     *
     * To replace method provide it's name as a key in third parameter
     * and it's return value or callback function as parameter
     *
     * ``` php
     * <?php
     * $this->construct('User', [], ['save' => function () { return true; }]);
     * $this->construct('User', [], ['save' => true]);
     * ?>
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
    public function construct($class, $constructorParams = [], $params = [])
    {
        return $this->mocks[] = \Codeception\Stub::construct($class, $constructorParams, $params, $this);
    }

    /**
     * Instantiates a class instance by running constructor with all methods replaced with dummies.
     * Parameters for constructor passed as second argument
     * Properties and methods can be set in third argument.
     * Even protected and private properties can be set.
     *
     * ``` php
     * <?php
     * $this->constructEmpty('User', ['autosave' => false]);
     * $this->constructEmpty('User', ['autosave' => false), ['name' => 'davert']);
     * ```
     *
     * Accepts either name of class or object of that class
     *
     * ``` php
     * <?php
     * $this->constructEmpty(new User, ['autosave' => false], ['name' => 'davert']);
     * ```
     *
     * To replace method provide it's name as a key in third parameter
     * and it's return value or callback function as parameter
     *
     * ``` php
     * <?php
     * $this->constructEmpty('User', array(), array('save' => function () { return true; }));
     * $this->constructEmpty('User', array(), array('save' => true));
     * ```
     *
     * **To create a mock, pass current testcase name as last argument:**
     *
     * ```php
     * <?php
     * $this->constructEmpty('User', [], [
     *      'save' => \Codeception\Stub\Expected::once()
     * ]);
     * ```
     *
     * @template RealInstanceType of object
     * @param class-string<RealInstanceType>|RealInstanceType|callable(): class-string<RealInstanceType> $class - A class to be mocked
     * @param array $constructorParams
     * @param array $params
     *
     * @return \PHPUnit\Framework\MockObject\MockObject&RealInstanceType
     */
    public function constructEmpty($class, $constructorParams = [], $params = [])
    {
        return $this->mocks[] = \Codeception\Stub::constructEmpty($class, $constructorParams, $params, $this);
    }

    /**
     * Instantiates a class instance by running constructor with all methods replaced with dummies, except one.
     * Parameters for constructor passed as second argument
     * Properties and methods can be set in third argument.
     * Even protected and private properties can be set.
     *
     * ``` php
     * <?php
     * $this->constructEmptyExcept('User', 'save');
     * $this->constructEmptyExcept('User', 'save', ['autosave' => false], ['name' => 'davert']);
     * ?>
     * ```
     *
     * Accepts either name of class or object of that class
     *
     * ``` php
     * <?php
     * $this->constructEmptyExcept(new User, 'save', ['autosave' => false], ['name' => 'davert']);
     * ?>
     * ```
     *
     * To replace method provide it's name as a key in third parameter
     * and it's return value or callback function as parameter
     *
     * ``` php
     * <?php
     * $this->constructEmptyExcept('User', 'save', [], ['save' => function () { return true; }]);
     * $this->constructEmptyExcept('User', 'save', [], ['save' => true]);
     * ?>
     * ```
     *
     * @template RealInstanceType of object
     * @param class-string<RealInstanceType>|RealInstanceType|callable(): class-string<RealInstanceType> $class - A class to be mocked
     * @param string $method
     * @param array $constructorParams
     * @param array $params
     *
     * @return \PHPUnit\Framework\MockObject\MockObject&RealInstanceType
     */
    public function constructEmptyExcept($class, $method, $constructorParams = [], $params = [])
    {
        return $this->mocks[] = \Codeception\Stub::constructEmptyExcept($class, $method, $constructorParams, $params, $this);
    }

}