<?php

include_once __DIR__.'/../src/Codeception/function.php';

class VerifyTest extends \Codeception\PHPUnit\TestCase {

    protected $xml;

    protected function _setUp()
    {
        $this->xml = new DomDocument;
        $this->xml->loadXML('<foo><bar>Baz</bar><bar>Baz</bar></foo>');
    }
    
    public function testEquals()
    {
        verify(5)->equals(5);
        verify("hello")->equals("hello");
        verify("user have 5 posts", 5)->equals(5);
        verify(3.251)->equals(3.25, 0.01);
        verify("respects delta", 3.251)->equals(3.25, 0.01);
        verify_file(__FILE__)->equals(__FILE__);
    }

    public function testNotEquals()
    {
        verify(3)->notEquals(5);
        verify(3.252)->notEquals(3.25, 0.001);
        verify("respects delta", 3.252, 0.001);
        verify_file(__FILE__)->notEquals(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'composer.json');
    }

    public function testContains()
    {
        verify(array(3, 2))->contains(3);
        verify("user have 5 posts", array(3, 2))->notContains(5);
    }

    public function testGreaterLowerThan()
    {
        verify(7)->greaterThan(5);
        verify(7)->lessThan(10);
        verify(7)->lessOrEquals(7);
        verify(7)->lessOrEquals(8);
        verify(7)->greaterOrEquals(7);
        verify(7)->greaterOrEquals(5);
    }

    public function testTrueFalseNull()
    {
        verify(true)->true();
        verify(false)->false();
        verify(null)->null();
        verify(true)->notNull();
        verify('something should be false', false)->false();
        verify('something should be true', true)->true();
    }

    public function testEmptyNotEmpty()
    {
        verify(array('3', '5'))->notEmpty();
        verify(array())->isEmpty();
    }

    public function testVerifyThat()
    {
        verify_that(12);
        verify_that('hello world');
        verify_that(array('hello'));
    }

    public function testVerifyNot()
    {
        verify_not(false);
        verify_not(null);
        verify_not(array());
    }

    public function testExpectFunctions()
    {
        expect(12)->equals(12);
        expect_that(true);
        expect_not(false);
    }

    public function testArrayHasKey()
    {
        $errors = array('title' => 'You should add title');
        expect($errors)->hasKey('title');
        expect($errors)->hasntKey('body');
    }

    public function testIsInstanceOf()
    {
        $testClass = new DateTime();
        expect($testClass)->isInstanceOf('DateTime');
        expect($testClass)->isNotInstanceOf('DateTimeZone');
    }

    public function testInternalType()
    {
        $testVar = array();
        expect($testVar)->internalType('array');
        expect($testVar)->notInternalType('boolean');
    }

    public function testHasAttribute()
    {
        expect('Exception')->hasAttribute('message');
        expect('Exception')->notHasAttribute('fakeproperty');
        
        $testObject = (object) array('existingAttribute' => true);
        expect($testObject)->hasAttribute('existingAttribute');
        expect($testObject)->notHasAttribute('fakeproperty');
    }

    public function testHasStaticAttribute()
    {
        expect('FakeClassForTesting')->hasStaticAttribute('staticProperty');
        expect('FakeClassForTesting')->notHasStaticAttribute('fakeProperty');
    }

    public function testContainsOnly()
    {
        expect(array('1', '2', '3'))->containsOnly('string');
        expect(array('1', '2', 3))->notContainsOnly('string');
    }

    public function testContainsOnlyInstancesOf()
    {
        expect(array(new FakeClassForTesting(), new FakeClassForTesting(), new FakeClassForTesting()))
            ->containsOnlyInstancesOf('FakeClassForTesting');
    }

    public function testCount()
    {
        expect(array(1,2,3))->count(3);
        expect(array(1,2,3))->notCount(2);
    }

    public function testEqualXMLStructure()
    {
        $expected = new DOMElement('foo');
        $actual = new DOMElement('foo');

        expect($expected)->equalXMLStructure($actual);
    }

    public function testFileExists()
    {
        expect_file(__FILE__)->exists();
        expect_file('completelyrandomfilename.txt')->notExists();
    }

    public function testEqualsJsonFile()
    {
        expect_file(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'json-test-file.json')
            ->equalsJsonFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'equal-json-test-file.json');
        expect('{"some" : "data"}')->equalsJsonFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'equal-json-test-file.json');
    }

    public function testEqualsJsonString()
    {
        expect('{"some" : "data"}')->equalsJsonString('{"some" : "data"}');
    }

    public function testRegExp()
    {
        expect('somestring')->regExp('/string/');
    }

    public function testMatchesFormat()
    {
        expect('somestring')->matchesFormat('%s');
        expect('somestring')->notMatchesFormat('%i');
    }

    public function testMatchesFormatFile()
    {
        expect('23')->matchesFormatFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'format-file.txt');
        expect('asdfas')->notMatchesFormatFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'format-file.txt');
    }

    public function testSame()
    {
        expect(1)->same(0+1);
        expect(1)->notSame(true);
    }

    public function testEndsWith()
    {
        expect('A completely not funny string')->endsWith('ny string');
        expect('A completely not funny string')->notEndsWith('A completely');
    }

    public function testEqualsFile()
    {
        expect('%i')->equalsFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'format-file.txt');
        expect('Another string')->notEqualsFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'format-file.txt');
    }

    public function testStartsWith()
    {
        expect('A completely not funny string')->startsWith('A completely');
        expect('A completely not funny string')->notStartsWith('string');
    }

    public function testEqualsXmlFile()
    {
        expect_file(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'xml-test-file.xml')
            ->equalsXmlFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'xml-test-file.xml');
        expect('<foo><bar>Baz</bar><bar>Baz</bar></foo>')
            ->equalsXmlFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'xml-test-file.xml');
    }

    public function testEqualsXmlString()
    {
        expect('<foo><bar>Baz</bar><bar>Baz</bar></foo>')
            ->equalsXmlString('<foo><bar>Baz</bar><bar>Baz</bar></foo>');
    }

    public function testStringContainsString()
    {
        verify('foo bar')->stringContainsString('o b');
        verify('foo bar')->stringNotContainsString('BAR');
    }

    public function testStringContainsStringIgnoringCase()
    {
        verify('foo bar')->stringContainsStringIgnoringCase('O b');
        verify('foo bar')->stringNotContainsStringIgnoringCase('baz');
    }

    public function testIsString()
    {
        verify('foo bar')->string();
        verify(false)->notString();
    }

    public function testIsArray()
    {
        verify([1,2,3])->array();
        verify(false)->notArray();
    }

    public function testIsBool()
    {
        verify(false)->bool();
        verify([1,2,3])->notBool();
    }

    public function testIsFloat()
    {
        verify(1.5)->float();
        verify(1)->notFloat();
    }

    public function testIsInt()
    {
        verify(5)->int();
        verify(1.5)->notInt();
    }

    public function testIsNumeric()
    {
        verify('1.5')->numeric();
        verify('foo bar')->notNumeric();
    }

    public function testIsObject()
    {
        verify(new stdClass)->object();
        verify(false)->notObject();
    }

    public function testIsResource()
    {
        verify(fopen(__FILE__, 'r'))->resource();
        verify(false)->notResource();
    }

    public function testIsScalar()
    {
        verify('foo bar')->scalar();
        verify([1,2,3])->notScalar();
    }

    public function testIsCallable()
    {
        verify(function() {})->callable();
        verify(false)->notCallable();
    }
}



class FakeClassForTesting
{
    static $staticProperty;
}
