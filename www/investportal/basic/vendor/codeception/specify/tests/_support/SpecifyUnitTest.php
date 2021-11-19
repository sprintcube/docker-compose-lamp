<?php

class SpecifyUnitTest extends \PHPUnit_Framework_TestCase
{
    use Codeception\Specify;

    private $private = true;

    /**
     * @param mixed $private
     */
    protected function setPrivateProperty($private)
    {
        $this->private = $private;
    }
    /**
     * @return mixed
     */
    protected function getPrivateProperty()
    {
        return $this->private;
    }
}
