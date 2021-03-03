<?php

namespace Tests\Behat\Gherkin\Node;

use Behat\Gherkin\Node\PyStringNode;

class PyStringNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetStrings()
    {
        $str = new PyStringNode(array('line1', 'line2', 'line3'), 0);

        $this->assertEquals(array('line1', 'line2', 'line3'), $str->getStrings());
    }

    public function testGetRaw()
    {
        $str = new PyStringNode(array('line1', 'line2', 'line3'), 0);

        $expected = <<<STR
line1
line2
line3
STR;
        $this->assertEquals($expected, $str->getRaw());
    }
}
