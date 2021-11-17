<?php

namespace Codeception\Stub;

use PHPUnit\Framework\MockObject\Rule\InvocationOrder;

/**
 * Holds matcher and value of mocked method
 */
class StubMarshaler
{
    private $methodMatcher;

    private $methodValue;

    public function __construct(InvocationOrder $matcher, $value)
    {
        $this->methodMatcher = $matcher;
        $this->methodValue = $value;
    }

    public function getMatcher()
    {
        return $this->methodMatcher;
    }

    public function getValue()
    {
        return $this->methodValue;
    }
}
