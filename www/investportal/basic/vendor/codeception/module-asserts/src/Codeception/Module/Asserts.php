<?php

namespace Codeception\Module;

use Codeception\Lib\Notification;

/**
 * Special module for using asserts in your tests.
 */
class Asserts extends AbstractAsserts
{
    /**
     * Handles and checks exception called inside callback function.
     * Either exception class name or exception instance should be provided.
     *
     * ```php
     * <?php
     * $I->expectException(MyException::class, function() {
     *     $this->doSomethingBad();
     * });
     *
     * $I->expectException(new MyException(), function() {
     *     $this->doSomethingBad();
     * });
     * ```
     * If you want to check message or exception code, you can pass them with exception instance:
     * ```php
     * <?php
     * // will check that exception MyException is thrown with "Don't do bad things" message
     * $I->expectException(new MyException("Don't do bad things"), function() {
     *     $this->doSomethingBad();
     * });
     * ```
     *
     * @deprecated Use expectThrowable() instead
     * @param \Exception|string $exception
     * @param callable $callback
     */
    public function expectException($exception, $callback)
    {
        Notification::deprecate('Use expectThrowable() instead');
        $this->expectThrowable($exception, $callback);
    }

    /**
     * Handles and checks throwables (Exceptions/Errors) called inside the callback function.
     * Either throwable class name or throwable instance should be provided.
     *
     * ```php
     * <?php
     * $I->expectThrowable(MyThrowable::class, function() {
     *     $this->doSomethingBad();
     * });
     *
     * $I->expectThrowable(new MyException(), function() {
     *     $this->doSomethingBad();
     * });
     * ```
     * If you want to check message or throwable code, you can pass them with throwable instance:
     * ```php
     * <?php
     * // will check that throwable MyError is thrown with "Don't do bad things" message
     * $I->expectThrowable(new MyError("Don't do bad things"), function() {
     *     $this->doSomethingBad();
     * });
     * ```
     *
     * @param \Throwable|string $throwable
     * @param callable $callback
     */
    public function expectThrowable($throwable, $callback)
    {
        if (is_object($throwable)) {
            $class = get_class($throwable);
            $msg = $throwable->getMessage();
            $code = $throwable->getCode();
        } else {
            $class = $throwable;
            $msg = null;
            $code = null;
        }

        try {
            $callback();
        } catch (\Exception $t) {
            $this->checkThrowable($t, $class, $msg, $code);
            return;
        } catch (\Throwable $t) {
            $this->checkThrowable($t, $class, $msg, $code);
            return;
        }

        $this->fail("Expected throwable of class '$class' to be thrown, but nothing was caught");
    }

    /**
     * Check if the given throwable matches the expected data,
     * fail (throws an exception) if it does not.
     *
     * @param \Throwable $throwable
     * @param string $expectedClass
     * @param string $expectedMsg
     * @param int $expectedCode
     */
    protected function checkThrowable($throwable, $expectedClass, $expectedMsg, $expectedCode)
    {
        if (!($throwable instanceof $expectedClass)) {
            $this->fail(sprintf(
                "Exception of class '$expectedClass' expected to be thrown, but class '%s' was caught",
                get_class($throwable)
            ));
        }

        if (null !== $expectedMsg && $throwable->getMessage() !== $expectedMsg) {
            $this->fail(sprintf(
                "Exception of class '$expectedClass' expected to have message '$expectedMsg', but actual message was '%s'",
                $throwable->getMessage()
            ));
        }

        if (null !== $expectedCode && $throwable->getCode() !== $expectedCode) {
            $this->fail(sprintf(
                "Exception of class '$expectedClass' expected to have code '$expectedCode', but actual code was '%s'",
                $throwable->getCode()
            ));
        }

        $this->assertTrue(true); // increment assertion counter
    }
}
