<?php
namespace Codeception;

use \Codeception\PHPUnit\TestCase as a;

class Verify {

    public static $override = false;

    protected $actual = null;
    protected $description = '';
    protected $isFileExpectation = false;

    public function __construct($description)
    {
        $descriptionGiven = (func_num_args() == 2);

        if (!$descriptionGiven) {
            $this->actual = $description;
            return;
        }

        $actual = func_get_args();
        $this->actual = $actual[1];
        $this->description = $description;
    }

    /**
     * @param boolean $isFileExpectation
     */
    public function setIsFileExpectation($isFileExpectation)
    {
        $this->isFileExpectation = $isFileExpectation;
    }

    public function equals($expected, $delta = 0)
    {
        if ( ! $this->isFileExpectation ) {
            a::assertEquals($expected, $this->actual, $this->description, $delta);
        } else {
            a::assertFileEquals($expected, $this->actual, $this->description);
        }
    }

    public function notEquals($expected, $delta = 0)
    {
        if ( ! $this->isFileExpectation ) {
            a::assertNotEquals($expected, $this->actual, $this->description, $delta);
        } else {
            a::assertFileNotEquals($expected, $this->actual, $this->description);
        }
    }

    public function contains($needle)
    {
        a::assertContains($needle, $this->actual, $this->description);
    }

    public function notContains($needle)
    {
        a::assertNotContains($needle, $this->actual, $this->description);
    }

    public function greaterThan($expected)
    {
        a::assertGreaterThan($expected, $this->actual, $this->description);
    }

    public function lessThan($expected)
    {
        a::assertLessThan($expected, $this->actual, $this->description);
    }

    public function greaterOrEquals($expected)
    {
        a::assertGreaterThanOrEqual($expected, $this->actual, $this->description);
    }

    public function lessOrEquals($expected)
    {
        a::assertLessThanOrEqual($expected, $this->actual, $this->description);
    }

    public function true()
    {
        a::assertTrue($this->actual, $this->description);
    }

    public function false()
    {
        a::assertFalse($this->actual, $this->description);
    }

    public function null()
    {
        a::assertNull($this->actual, $this->description);
    }

    public function notNull()
    {
        a::assertNotNull($this->actual, $this->description);
    }

    public function isEmpty()
    {
        a::assertEmpty($this->actual, $this->description);
    }

    public function notEmpty()
    {
        a::assertNotEmpty($this->actual, $this->description);
    }

    public function hasKey($key)
    {
        a::assertArrayHasKey($key, $this->actual, $this->description);
    }

    public function hasntKey($key)
    {
        a::assertArrayNotHasKey($key, $this->actual, $this->description);
    }

    public function isInstanceOf($class)
    {
        a::assertInstanceOf($class, $this->actual, $this->description);
    }

    public function isNotInstanceOf($class)
    {
        a::assertNotInstanceOf($class, $this->actual, $this->description);
    }

    public function internalType($type)
    {
        a::assertInternalType($type, $this->actual, $this->description);
    }

    public function notInternalType($type)
    {
        a::assertNotInternalType($type, $this->actual, $this->description);
    }

    public function hasAttribute($attribute)
    {
        if (is_string($this->actual)) {
            a::assertClassHasAttribute($attribute, $this->actual, $this->description);
        } else {
            a::assertObjectHasAttribute($attribute, $this->actual, $this->description);
        }
    }

    public function notHasAttribute($attribute)
    {
        if (is_string($this->actual)) {
            a::assertClassNotHasAttribute($attribute, $this->actual, $this->description);
        } else {
            a::assertObjectNotHasAttribute($attribute, $this->actual, $this->description);
        }
    }

    public function hasStaticAttribute($attribute)
    {
        a::assertClassHasStaticAttribute($attribute, $this->actual, $this->description);
    }

    public function notHasStaticAttribute($attribute)
    {
        a::assertClassNotHasStaticAttribute($attribute, $this->actual, $this->description);
    }

    public function containsOnly($type, $isNativeType = NULL)
    {
        a::assertContainsOnly($type, $this->actual, $isNativeType, $this->description);
    }

    public function notContainsOnly($type, $isNativeType = NULL)
    {
        a::assertNotContainsOnly($type, $this->actual, $isNativeType, $this->description);
    }

    public function containsOnlyInstancesOf($class)
    {
        a::assertContainsOnlyInstancesOf($class, $this->actual, $this->description);
    }

    public function count($expectedCount)
    {
        a::assertCount($expectedCount, $this->actual, $this->description);
    }

    public function notCount($expectedCount)
    {
        a::assertNotCount($expectedCount, $this->actual, $this->description);
    }

    public function equalXMLStructure($xml, $checkAttributes = FALSE)
    {
        a::assertEqualXMLStructure($xml, $this->actual, $checkAttributes, $this->description);
    }

    public function exists()
    {
        if (!$this->isFileExpectation ) {
            throw new \Exception('exists() expectation should be called with expect_file()');
        }
        a::assertFileExists($this->actual, $this->description);
    }

    public function notExists()
    {
        if (!$this->isFileExpectation ) {
            throw new \Exception('notExists() expectation should be called with expect_file()');
        }
        a::assertFileNotExists($this->actual, $this->description);
    }

    public function equalsJsonFile($file)
    {
        if (!$this->isFileExpectation ) {
            a::assertJsonStringEqualsJsonFile($file, $this->actual, $this->description);
        } else {
            a::assertJsonFileEqualsJsonFile($file, $this->actual, $this->description);
        }
    }

    public function equalsJsonString($string)
    {
        a::assertJsonStringEqualsJsonString($string, $this->actual, $this->description);
    }

    public function regExp($expression)
    {
        a::assertRegExp($expression, $this->actual, $this->description);
    }

    public function matchesFormat($format)
    {
        a::assertStringMatchesFormat($format, $this->actual, $this->description);
    }

    public function notMatchesFormat($format)
    {
        a::assertStringNotMatchesFormat($format, $this->actual, $this->description);
    }

    public function matchesFormatFile($formatFile)
    {
        a::assertStringMatchesFormatFile($formatFile, $this->actual, $this->description);
    }

    public function notMatchesFormatFile($formatFile)
    {
        a::assertStringNotMatchesFormatFile($formatFile, $this->actual, $this->description);
    }

    public function same($expected)
    {
        a::assertSame($expected, $this->actual, $this->description);
    }

    public function notSame($expected)
    {
        a::assertNotSame($expected, $this->actual, $this->description);
    }

    public function endsWith($suffix)
    {
        a::assertStringEndsWith($suffix, $this->actual, $this->description);
    }

    public function notEndsWith($suffix)
    {
        a::assertStringEndsNotWith($suffix, $this->actual, $this->description);
    }

    public function equalsFile($file)
    {
        a::assertStringEqualsFile($file, $this->actual, $this->description);
    }

    public function notEqualsFile($file)
    {
        a::assertStringNotEqualsFile($file, $this->actual, $this->description);
    }

    public function startsWith($prefix)
    {
        a::assertStringStartsWith($prefix, $this->actual, $this->description);
    }

    public function notStartsWith($prefix)
    {
        a::assertStringStartsNotWith($prefix, $this->actual, $this->description);
    }

    public function equalsXmlFile($file)
    {
        if (!$this->isFileExpectation ) {
            a::assertXmlStringEqualsXmlFile($file, $this->actual, $this->description);
        } else {
            a::assertXmlFileEqualsXmlFile($file, $this->actual, $this->description);
        }
    }

    public function equalsXmlString($xmlString)
    {
        a::assertXmlStringEqualsXmlString($xmlString, $this->actual, $this->description);
    }

    public function stringContainsString($needle)
    {
        a::assertStringContainsString($needle, $this->actual, $this->description);
    }

    public function stringNotContainsString($needle)
    {
        a::assertStringNotContainsString($needle, $this->actual, $this->description);
    }

    public function stringContainsStringIgnoringCase($needle)
    {
        a::assertStringContainsStringIgnoringCase($needle, $this->actual, $this->description);
    }

    public function stringNotContainsStringIgnoringCase($needle)
    {
        a::assertStringNotContainsStringIgnoringCase($needle, $this->actual, $this->description);
    }

    public function array()
    {
        a::assertIsArray($this->actual, $this->description);
    }

    public function bool()
    {
        a::assertIsBool($this->actual, $this->description);
    }

    public function float()
    {
        a::assertIsFloat($this->actual, $this->description);
    }

    public function int()
    {
        a::assertIsInt($this->actual, $this->description);
    }

    public function numeric()
    {
        a::assertIsNumeric($this->actual, $this->description);
    }

    public function object()
    {
        a::assertIsObject($this->actual, $this->description);
    }

    public function resource()
    {
        a::assertIsResource($this->actual, $this->description);
    }

    public function string()
    {
        a::assertIsString($this->actual, $this->description);
    }

    public function scalar()
    {
        a::assertIsScalar($this->actual, $this->description);
    }

    public function callable()
    {
        a::assertIsCallable($this->actual, $this->description);
    }

    public function notArray()
    {
        a::assertIsNotArray($this->actual, $this->description);
    }

    public function notBool()
    {
        a::assertIsNotBool($this->actual, $this->description);
    }

    public function notFloat()
    {
        a::assertIsNotFloat($this->actual, $this->description);
    }

    public function notInt()
    {
        a::assertIsNotInt($this->actual, $this->description);
    }

    public function notNumeric()
    {
        a::assertIsNotNumeric($this->actual, $this->description);
    }

    public function notObject()
    {
        a::assertIsNotObject($this->actual, $this->description);
    }

    public function notResource()
    {
        a::assertIsNotResource($this->actual, $this->description);
    }

    public function notString()
    {
        a::assertIsNotString($this->actual, $this->description);
    }

    public function notScalar()
    {
        a::assertIsNotScalar($this->actual, $this->description);
    }

    public function notCallable()
    {
        a::assertIsNotCallable($this->actual, $this->description);
    }
}
