<?php

class ObjectPropertyTest extends \SpecifyUnitTest
{
    private $private = 'private';

    public function testConstruction()
    {
        $this->prop = 'test';

        $prop = new \Codeception\Specify\ObjectProperty($this, 'prop');

        $this->assertEquals('prop', $prop->getName());
        $this->assertEquals('test', $prop->getValue());

        $prop = new \Codeception\Specify\ObjectProperty($this, 'private');

        $this->assertEquals('private', $prop->getName());
        $this->assertEquals('private', $prop->getValue());

        $prop = new \Codeception\Specify\ObjectProperty(
            $this, new ReflectionProperty($this, 'private')
        );

        $this->assertEquals('private', $prop->getName());
        $this->assertEquals('private', $prop->getValue());
    }

    public function testRestore()
    {
        $this->prop = 'test';

        $prop = new \Codeception\Specify\ObjectProperty($this, 'prop');
        $prop->setValue('another value');

        $this->assertEquals('another value', $this->prop);

        $prop->restoreValue();

        $this->assertEquals('test', $this->prop);

        $prop = new \Codeception\Specify\ObjectProperty($this, 'private');
        $prop->setValue('another private value');

        $this->assertEquals('another private value', $this->private);

        $prop->restoreValue();

        $this->assertEquals('private', $this->private);

        $prop = new \Codeception\Specify\ObjectProperty($this, 'prop', 'testing');

        $this->assertEquals('test', $prop->getValue());

        $prop->setValue('Hello, World!');

        $this->assertEquals($prop->getValue(), $this->prop);
        $this->assertEquals('Hello, World!', $prop->getValue());

        $prop->restoreValue();

        $this->assertEquals($prop->getValue(), $this->prop);
        $this->assertEquals('testing', $prop->getValue());
    }
}
