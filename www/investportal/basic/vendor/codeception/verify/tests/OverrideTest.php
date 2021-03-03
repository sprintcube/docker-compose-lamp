<?php
include __DIR__.'/../vendor/autoload.php';

class OverrideTest extends \Codeception\PHPUnit\TestCase
{
    protected function _setUp()
    {
        
    }

    protected function _tearDown()
    {
        \Codeception\Verify::$override = false;
    }
    
    public function testVerifyCanBeOverridden()
    {
        \Codeception\Verify::$override = MyVerify::class;
        $this->assertInstanceOf(MyVerify::class, verify(null));
    }

}

class MyVerify extends \Codeception\Verify {

}