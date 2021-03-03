<?php

namespace Tests\Behat\Gherkin\Node;

use Behat\Gherkin\Node\TableNode;

class TableNodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Behat\Gherkin\Exception\NodeException
     */
    public function testConstructorExpectsSameNumberOfColumnsInEachRow()
    {
        new TableNode(array(
            array('username', 'password'),
            array('everzet'),
            array('antono', 'pa$sword')
        ));
    }

    public function constructorTestDataProvider() {
        return array(
            'One-dimensional array' => array(
                array('everzet', 'antono')
            ),
            'Three-dimensional array' => array(
                array(array(array('everzet', 'antono')))
            )
        );
    }

    /**
     * @dataProvider constructorTestDataProvider
     * @expectedException \Behat\Gherkin\Exception\NodeException
     * @expectedExceptionMessage Table is not two-dimensional.
     */
    public function testConstructorExpectsTwoDimensionalArrays($table)
    {
        new TableNode($table);
    }

    public function testHashTable()
    {
        $table = new TableNode(array(
            array('username', 'password'),
            array('everzet', 'qwerty'),
            array('antono', 'pa$sword')
        ));

        $this->assertEquals(
            array(
                array('username' => 'everzet', 'password' => 'qwerty')
              , array('username' => 'antono', 'password' => 'pa$sword')
            ),
            $table->getHash()
        );

        $table = new TableNode(array(
            array('username', 'password'),
            array('', 'qwerty'),
            array('antono', ''),
            array('', '')
        ));

        $this->assertEquals(
            array(
                array('username' => '', 'password' => 'qwerty'),
                array('username' => 'antono', 'password' => ''),
                array('username' => '', 'password' => ''),
            ),
            $table->getHash()
        );
    }

    public function testIterator()
    {
        $table = new TableNode(array(
            array('username', 'password'),
            array('', 'qwerty'),
            array('antono', ''),
            array('', ''),
        ));

        $this->assertEquals(
            array(
                array('username' => '', 'password' => 'qwerty'),
                array('username' => 'antono', 'password' => ''),
                array('username' => '', 'password' => ''),
            ),
            iterator_to_array($table)
        );
    }

    public function testRowsHashTable()
    {
        $table = new TableNode(array(
            array('username', 'everzet'),
            array('password', 'qwerty'),
            array('uid', '35'),
        ));

        $this->assertEquals(
            array('username' => 'everzet', 'password' => 'qwerty', 'uid' => '35'),
            $table->getRowsHash()
        );
    }

    public function testLongRowsHashTable()
    {
        $table = new TableNode(array(
            array('username', 'everzet', 'marcello'),
            array('password', 'qwerty', '12345'),
            array('uid', '35', '22')
        ));

        $this->assertEquals(array(
            'username' => array('everzet', 'marcello'),
            'password' => array('qwerty', '12345'),
            'uid'      => array('35', '22')
        ), $table->getRowsHash());
    }

    public function testGetRows()
    {
        $table = new TableNode(array(
            array('username', 'password'),
            array('everzet', 'qwerty'),
            array('antono', 'pa$sword')
        ));

        $this->assertEquals(array(
            array('username', 'password'),
            array('everzet', 'qwerty'),
            array('antono', 'pa$sword')
        ), $table->getRows());
    }

    public function testGetLines()
    {
        $table = new TableNode(array(
            5  => array('username', 'password'),
            10 => array('everzet', 'qwerty'),
            13 => array('antono', 'pa$sword')
        ));

        $this->assertEquals(array(5, 10, 13), $table->getLines());
    }

    public function testGetRow()
    {
        $table = new TableNode(array(
            array('username', 'password'),
            array('everzet', 'qwerty'),
            array('antono', 'pa$sword')
        ));

        $this->assertEquals(array('username', 'password'), $table->getRow(0));
        $this->assertEquals(array('antono', 'pa$sword'), $table->getRow(2));
    }

    public function testGetColumn()
    {
        $table = new TableNode(array(
            array('username', 'password'),
            array('everzet', 'qwerty'),
            array('antono', 'pa$sword')
        ));

        $this->assertEquals(array('username', 'everzet', 'antono'), $table->getColumn(0));
        $this->assertEquals(array('password', 'qwerty', 'pa$sword'), $table->getColumn(1));

        $table = new TableNode(array(
            array('username'),
            array('everzet'),
            array('antono')
        ));

        $this->assertEquals(array('username', 'everzet', 'antono'), $table->getColumn(0));
    }

    public function testGetRowWithLineNumbers()
    {
        $table = new TableNode(array(
            5  => array('username', 'password'),
            10 => array('everzet', 'qwerty'),
            13 => array('antono', 'pa$sword')
        ));

        $this->assertEquals(array('username', 'password'), $table->getRow(0));
        $this->assertEquals(array('antono', 'pa$sword'), $table->getRow(2));
    }

    public function testGetTable()
    {
        $table = new TableNode($a = array(
            5  => array('username', 'password'),
            10 => array('everzet', 'qwerty'),
            13 => array('antono', 'pa$sword')
        ));

        $this->assertEquals($a, $table->getTable());
    }

    public function testGetRowLine()
    {
        $table = new TableNode(array(
            5  => array('username', 'password'),
            10 => array('everzet', 'qwerty'),
            13 => array('antono', 'pa$sword')
        ));

        $this->assertEquals(5, $table->getRowLine(0));
        $this->assertEquals(13, $table->getRowLine(2));
    }

    public function testGetRowAsString()
    {
        $table = new TableNode(array(
            5  => array('username', 'password'),
            10 => array('everzet', 'qwerty'),
            13 => array('antono', 'pa$sword')
        ));

        $this->assertEquals('| username | password |', $table->getRowAsString(0));
        $this->assertEquals('| antono   | pa$sword |', $table->getRowAsString(2));
    }

    public function testGetTableAsString()
    {
        $table = new TableNode(array(
            5  => array('id', 'username', 'password'),
            10 => array('42', 'everzet', 'qwerty'),
            13 => array('2', 'antono', 'pa$sword')
        ));

        $expected = <<<TABLE
| id | username | password |
| 42 | everzet  | qwerty   |
| 2  | antono   | pa\$sword |
TABLE;
        $this->assertEquals($expected, $table->getTableAsString());
    }

    public function testFromList()
    {
        $table = TableNode::fromList(array(
            'everzet',
            'antono'
        ));

        $expected = new TableNode(array(
            array('everzet'),
            array('antono'),
        ));
        $this->assertEquals($expected, $table);
    }

    /**
     * @expectedException \Behat\Gherkin\Exception\NodeException
     */
    public function testGetTableFromListWithMultidimensionalArrayArgument()
    {
        TableNode::fromList(array(
            array(1, 2, 3),
            array(4, 5, 6)
        ));
    }

}
