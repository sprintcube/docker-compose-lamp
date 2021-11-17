<?php

namespace Codeception\Util\Shared;

use ArrayAccess;
use Codeception\PHPUnit\TestCase;
use Countable;
use DOMDocument;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;

trait InheritedAsserts
{
    /**
     * Asserts that an array has a specified key.
     *
     * @param int|string $key
     * @param array|ArrayAccess $array
     * @param string $message
     */
    protected function assertArrayHasKey($key, $array, $message = '')
    {
        Assert::assertArrayHasKey($key, $array, $message);
    }

    /**
     * Asserts that an array does not have a specified key.
     *
     * @param int|string $key
     * @param array|ArrayAccess $array
     * @param string $message
     */
    protected function assertArrayNotHasKey($key, $array, $message = '')
    {
        Assert::assertArrayNotHasKey($key, $array, $message);
    }

    /**
     * Asserts that a class has a specified attribute.
     *
     * @param string $attributeName
     * @param string $className
     * @param string $message
     */
    protected function assertClassHasAttribute($attributeName, $className, $message = '')
    {
        Assert::assertClassHasAttribute($attributeName, $className, $message);
    }

    /**
     * Asserts that a class has a specified static attribute.
     *
     * @param string $attributeName
     * @param string $className
     * @param string $message
     */
    protected function assertClassHasStaticAttribute($attributeName, $className, $message = '')
    {
        Assert::assertClassHasStaticAttribute($attributeName, $className, $message);
    }

    /**
     * Asserts that a class does not have a specified attribute.
     *
     * @param string $attributeName
     * @param string $className
     * @param string $message
     */
    protected function assertClassNotHasAttribute($attributeName, $className, $message = '')
    {
        Assert::assertClassNotHasAttribute($attributeName, $className, $message);
    }

    /**
     * Asserts that a class does not have a specified static attribute.
     *
     * @param string $attributeName
     * @param string $className
     * @param string $message
     */
    protected function assertClassNotHasStaticAttribute($attributeName, $className, $message = '')
    {
        Assert::assertClassNotHasStaticAttribute($attributeName, $className, $message);
    }

    /**
     * Asserts that a haystack contains a needle.
     *
     * @param $needle
     * @param $haystack
     * @param string $message
     */
    protected function assertContains($needle, $haystack, $message = '')
    {
        Assert::assertContains($needle, $haystack, $message);
    }

    /**
     * @param $needle
     * @param $haystack
     * @param string $message
     */
    protected function assertContainsEquals($needle, $haystack, $message = '')
    {
        Assert::assertContainsEquals($needle, $haystack, $message);
    }

    /**
     * Asserts that a haystack contains only values of a given type.
     *
     * @param string $type
     * @param $haystack
     * @param bool|null $isNativeType
     * @param string $message
     */
    protected function assertContainsOnly($type, $haystack, $isNativeType = null, $message = '')
    {
        Assert::assertContainsOnly($type, $haystack, $isNativeType, $message);
    }

    /**
     * Asserts that a haystack contains only instances of a given class name.
     *
     * @param string $className
     * @param $haystack
     * @param string $message
     */
    protected function assertContainsOnlyInstancesOf($className, $haystack, $message = '')
    {
        Assert::assertContainsOnlyInstancesOf($className, $haystack, $message);
    }

    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param int $expectedCount
     * @param Countable|iterable $haystack
     * @param string $message
     */
    protected function assertCount($expectedCount, $haystack, $message = '')
    {
        Assert::assertCount($expectedCount, $haystack, $message);
    }

    /**
     * Asserts that a directory does not exist.
     *
     * @param string $directory
     * @param string $message
     */
    protected function assertDirectoryDoesNotExist($directory, $message = '')
    {
        Assert::assertDirectoryDoesNotExist($directory, $message);
    }

    /**
     * Asserts that a directory exists.
     *
     * @param string $directory
     * @param string $message
     */
    protected function assertDirectoryExists($directory, $message = '')
    {
        Assert::assertDirectoryExists($directory, $message);
    }

    /**
     * Asserts that a directory exists and is not readable.
     *
     * @param string $directory
     * @param string $message
     */
    protected function assertDirectoryIsNotReadable($directory, $message = '')
    {
        Assert::assertDirectoryIsNotReadable($directory, $message);
    }

    /**
     * Asserts that a directory exists and is not writable.
     *
     * @param string $directory
     * @param string $message
     */
    protected function assertDirectoryIsNotWritable($directory, $message = '')
    {
        Assert::assertDirectoryIsNotWritable($directory, $message);
    }

    /**
     * Asserts that a directory exists and is readable.
     *
     * @param string $directory
     * @param string $message
     */
    protected function assertDirectoryIsReadable($directory, $message = '')
    {
        Assert::assertDirectoryIsReadable($directory, $message);
    }

    /**
     * Asserts that a directory exists and is writable.
     *
     * @param string $directory
     * @param string $message
     */
    protected function assertDirectoryIsWritable($directory, $message = '')
    {
        Assert::assertDirectoryIsWritable($directory, $message);
    }

    /**
     * Asserts that a string does not match a given regular expression.
     *
     * @param string $pattern
     * @param string $string
     * @param string $message
     */
    protected function assertDoesNotMatchRegularExpression($pattern, $string, $message = '')
    {
        TestCase::assertNotRegExp($pattern, $string, $message);
    }

    /**
     * Asserts that a variable is empty.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertEmpty($actual, $message = '')
    {
        Assert::assertEmpty($actual, $message);
    }

    /**
     * Asserts that two variables are equal.
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertEquals($expected, $actual, $message = '')
    {
        Assert::assertEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two variables are equal (canonicalizing).
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertEqualsCanonicalizing($expected, $actual, $message = '')
    {
        TestCase::assertEqualsCanonicalizing($expected, $actual, $message);
    }

    /**
     * Asserts that two variables are equal (ignoring case).
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertEqualsIgnoringCase($expected, $actual, $message = '')
    {
        TestCase::assertEqualsIgnoringCase($expected, $actual, $message);
    }

    /**
     * Asserts that two variables are equal (with delta).
     *
     * @param $expected
     * @param $actual
     * @param float $delta
     * @param string $message
     */
    protected function assertEqualsWithDelta($expected, $actual, $delta, $message = '')
    {
        TestCase::assertEqualsWithDelta($expected, $actual, $delta, $message);
    }

    /**
     * Asserts that a condition is false.
     *
     * @param $condition
     * @param string $message
     */
    protected function assertFalse($condition, $message = '')
    {
        Assert::assertFalse($condition, $message);
    }

    /**
     * Asserts that a file does not exist.
     *
     * @param string $filename
     * @param string $message
     */
    protected function assertFileDoesNotExist($filename, $message = '')
    {
        TestCase::assertFileNotExists($filename, $message);
    }

    /**
     * Asserts that the contents of one file is equal to the contents of another file.
     *
     * @param string $expected
     * @param string $actual
     * @param string $message
     */
    protected function assertFileEquals($expected, $actual, $message = '')
    {
        Assert::assertFileEquals($expected, $actual, $message);
    }

    /**
     * Asserts that the contents of one file is equal to the contents of another file (canonicalizing).
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertFileEqualsCanonicalizing($expected, $actual, $message = '')
    {
        Assert::assertFileEqualsCanonicalizing($expected, $actual, $message);
    }

    /**
     * Asserts that the contents of one file is equal to the contents of another file (ignoring case).
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertFileEqualsIgnoringCase($expected, $actual, $message = '')
    {
        Assert::assertFileEqualsIgnoringCase($expected, $actual, $message);
    }

    /**
     * Asserts that a file exists.
     *
     * @param string $filename
     * @param string $message
     */
    protected function assertFileExists($filename, $message = '')
    {
        Assert::assertFileExists($filename, $message);
    }

    /**
     * Asserts that a file exists and is not readable.
     *
     * @param string $file
     * @param string $message
     */
    protected function assertFileIsNotReadable($file, $message = '')
    {
        Assert::assertFileIsNotReadable($file, $message);
    }

    /**
     * Asserts that a file exists and is not writable.
     *
     * @param string $file
     * @param string $message
     */
    protected function assertFileIsNotWritable($file, $message = '')
    {
        Assert::assertFileIsNotWritable($file, $message);
    }

    /**
     * Asserts that a file exists and is readable.
     *
     * @param string $file
     * @param string $message
     */
    protected function assertFileIsReadable($file, $message = '')
    {
        Assert::assertFileIsReadable($file, $message);
    }

    /**
     * Asserts that a file exists and is writable.
     *
     * @param string $file
     * @param string $message
     */
    protected function assertFileIsWritable($file, $message = '')
    {
        Assert::assertFileIsWritable($file, $message);
    }

    /**
     * Asserts that the contents of one file is not equal to the contents of another file.
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertFileNotEquals($expected, $actual, $message = '')
    {
        Assert::assertFileNotEquals($expected, $actual, $message);
    }

    /**
     * Asserts that the contents of one file is not equal to the contents of another file (canonicalizing).
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertFileNotEqualsCanonicalizing($expected, $actual, $message = '')
    {
        Assert::assertFileNotEqualsCanonicalizing($expected, $actual, $message);
    }

    /**
     * Asserts that the contents of one file is not equal to the contents of another file (ignoring case).
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertFileNotEqualsIgnoringCase($expected, $actual, $message = '')
    {
        Assert::assertFileNotEqualsIgnoringCase($expected, $actual, $message);
    }

    /**
     * Asserts that a variable is finite.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertFinite($actual, $message = '')
    {
        Assert::assertFinite($actual, $message);
    }

    /**
     * Asserts that a value is greater than another value.
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertGreaterThan($expected, $actual, $message = '')
    {
        Assert::assertGreaterThan($expected, $actual, $message);
    }

    /**
     * Asserts that a value is greater than or equal to another value.
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertGreaterThanOrEqual($expected, $actual, $message = '')
    {
        Assert::assertGreaterThanOrEqual($expected, $actual, $message);
    }

    /**
     * Asserts that a variable is infinite.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertInfinite($actual, $message = '')
    {
        Assert::assertInfinite($actual, $message);
    }

    /**
     * Asserts that a variable is of a given type.
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertInstanceOf($expected, $actual, $message = '')
    {
        Assert::assertInstanceOf($expected, $actual, $message);
    }

    /**
     * Asserts that a variable is of type array.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsArray($actual, $message = '')
    {
        TestCase::assertIsArray($actual, $message);
    }

    /**
     * Asserts that a variable is of type bool.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsBool($actual, $message = '')
    {
        TestCase::assertIsBool($actual, $message);
    }

    /**
     * Asserts that a variable is of type callable.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsCallable($actual, $message = '')
    {
        TestCase::assertIsCallable($actual, $message);
    }

    /**
     * Asserts that a variable is of type resource and is closed.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsClosedResource($actual, $message = '')
    {
        TestCase::assertIsClosedResource($actual, $message);
    }

    /**
     * Asserts that a variable is of type float.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsFloat($actual, $message = '')
    {
        TestCase::assertIsFloat($actual, $message);
    }

    /**
     * Asserts that a variable is of type int.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsInt($actual, $message = '')
    {
        TestCase::assertIsInt($actual, $message);
    }

    /**
     * Asserts that a variable is of type iterable.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsIterable($actual, $message = '')
    {
        TestCase::assertIsIterable($actual, $message);
    }

    /**
     * Asserts that a variable is not of type array.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsNotArray($actual, $message = '')
    {
        TestCase::assertIsNotArray($actual, $message);
    }

    /**
     * Asserts that a variable is not of type bool.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsNotBool($actual, $message = '')
    {
        TestCase::assertIsNotBool($actual, $message);
    }

    /**
     * Asserts that a variable is not of type callable.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsNotCallable($actual, $message = '')
    {
        TestCase::assertIsNotCallable($actual, $message);
    }

    /**
     * Asserts that a variable is not of type resource.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsNotClosedResource($actual, $message = '')
    {
        TestCase::assertIsNotClosedResource($actual, $message);
    }

    /**
     * Asserts that a variable is not of type float.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsNotFloat($actual, $message = '')
    {
        TestCase::assertIsNotFloat($actual, $message);
    }

    /**
     * Asserts that a variable is not of type int.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsNotInt($actual, $message = '')
    {
        TestCase::assertIsNotInt($actual, $message);
    }

    /**
     * Asserts that a variable is not of type iterable.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsNotIterable($actual, $message = '')
    {
        TestCase::assertIsNotIterable($actual, $message);
    }

    /**
     * Asserts that a variable is not of type numeric.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsNotNumeric($actual, $message = '')
    {
        TestCase::assertIsNotNumeric($actual, $message);
    }

    /**
     * Asserts that a variable is not of type object.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsNotObject($actual, $message = '')
    {
        TestCase::assertIsNotObject($actual, $message);
    }

    /**
     * Asserts that a file/dir exists and is not readable.
     *
     * @param string $filename
     * @param string $message
     */
    protected function assertIsNotReadable($filename, $message = '')
    {
        TestCase::assertIsNotReadable($filename, $message);
    }

    /**
     * Asserts that a variable is not of type resource.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsNotResource($actual, $message = '')
    {
        TestCase::assertIsNotResource($actual, $message);
    }

    /**
     * Asserts that a variable is not of type scalar.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsNotScalar($actual, $message = '')
    {
        TestCase::assertIsNotScalar($actual, $message);
    }

    /**
     * Asserts that a variable is not of type string.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsNotString($actual, $message = '')
    {
        TestCase::assertIsNotString($actual, $message);
    }

    /**
     * Asserts that a file/dir exists and is not writable.
     *
     * @param $filename
     * @param string $message
     */
    protected function assertIsNotWritable($filename, $message = '')
    {
        TestCase::assertIsNotWritable($filename, $message);
    }

    /**
     * Asserts that a variable is of type numeric.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsNumeric($actual, $message = '')
    {
        TestCase::assertIsNumeric($actual, $message);
    }

    /**
     * Asserts that a variable is of type object.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsObject($actual, $message = '')
    {
        TestCase::assertIsObject($actual, $message);
    }

    /**
     * Asserts that a file/dir is readable.
     *
     * @param $filename
     * @param string $message
     */
    protected function assertIsReadable($filename, $message = '')
    {
        TestCase::assertIsReadable($filename, $message);
    }

    /**
     * Asserts that a variable is of type resource.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsResource($actual, $message = '')
    {
        TestCase::assertIsResource($actual, $message);
    }

    /**
     * Asserts that a variable is of type scalar.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsScalar($actual, $message = '')
    {
        TestCase::assertIsScalar($actual, $message);
    }

    /**
     * Asserts that a variable is of type string.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertIsString($actual, $message = '')
    {
        TestCase::assertIsString($actual, $message);
    }

    /**
     * Asserts that a file/dir exists and is writable.
     *
     * @param $filename
     * @param string $message
     */
    protected function assertIsWritable($filename, $message = '')
    {
        TestCase::assertIsWritable($filename, $message);
    }

    /**
     * Asserts that a string is a valid JSON string.
     *
     * @param string $actualJson
     * @param string $message
     */
    protected function assertJson($actualJson, $message = '')
    {
        Assert::assertJson($actualJson, $message);
    }

    /**
     * Asserts that two JSON files are equal.
     *
     * @param string $expectedFile
     * @param string $actualFile
     * @param string $message
     */
    protected function assertJsonFileEqualsJsonFile($expectedFile, $actualFile, $message = '')
    {
        Assert::assertJsonFileEqualsJsonFile($expectedFile, $actualFile, $message);
    }

    /**
     * Asserts that two JSON files are not equal.
     *
     * @param string $expectedFile
     * @param string $actualFile
     * @param string $message
     */
    protected function assertJsonFileNotEqualsJsonFile($expectedFile, $actualFile, $message = '')
    {
        Assert::assertJsonFileNotEqualsJsonFile($expectedFile, $actualFile, $message);
    }

    /**
     * Asserts that the generated JSON encoded object and the content of the given file are equal.
     *
     * @param string $expectedFile
     * @param string $actualJson
     * @param string $message
     */
    protected function assertJsonStringEqualsJsonFile($expectedFile, $actualJson, $message = '')
    {
        Assert::assertJsonStringEqualsJsonFile($expectedFile, $actualJson, $message);
    }

    /**
     * Asserts that two given JSON encoded objects or arrays are equal.
     *
     * @param string $expectedJson
     * @param string $actualJson
     * @param string $message
     */
    protected function assertJsonStringEqualsJsonString($expectedJson, $actualJson, $message = '')
    {
        Assert::assertJsonStringEqualsJsonString($expectedJson, $actualJson, $message);
    }

    /**
     * Asserts that the generated JSON encoded object and the content of the given file are not equal.
     *
     * @param string $expectedFile
     * @param string $actualJson
     * @param string $message
     */
    protected function assertJsonStringNotEqualsJsonFile($expectedFile, $actualJson, $message = '')
    {
        Assert::assertJsonStringNotEqualsJsonFile($expectedFile, $actualJson, $message);
    }

    /**
     * Asserts that two given JSON encoded objects or arrays are not equal.
     *
     * @param string $expectedJson
     * @param string $actualJson
     * @param string $message
     */
    protected function assertJsonStringNotEqualsJsonString($expectedJson, $actualJson, $message = '')
    {
        Assert::assertJsonStringNotEqualsJsonString($expectedJson, $actualJson, $message);
    }

    /**
     * Asserts that a value is smaller than another value.
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertLessThan($expected, $actual, $message = '')
    {
        Assert::assertLessThan($expected, $actual, $message);
    }

    /**
     * Asserts that a value is smaller than or equal to another value.
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertLessThanOrEqual($expected, $actual, $message = '')
    {
        Assert::assertLessThanOrEqual($expected, $actual, $message);
    }

    /**
     * Asserts that a string matches a given regular expression.
     *
     * @param string $pattern
     * @param string $string
     * @param string $message
     */
    protected function assertMatchesRegularExpression($pattern, $string, $message = '')
    {
        TestCase::assertRegExp($pattern, $string, $message);
    }

    /**
     * Asserts that a variable is nan.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertNan($actual, $message = '')
    {
        Assert::assertNan($actual, $message);
    }

    /**
     * Asserts that a haystack does not contain a needle.
     *
     * @param $needle
     * @param $haystack
     * @param string $message
     */
    protected function assertNotContains($needle, $haystack, $message = '')
    {
        Assert::assertNotContains($needle, $haystack, $message);
    }

    protected function assertNotContainsEquals($needle, $haystack, $message = '')
    {
        Assert::assertNotContainsEquals($needle, $haystack, $message);
    }

    /**
     * Asserts that a haystack does not contain only values of a given type.
     *
     * @param string $type
     * @param $haystack
     * @param bool|null $isNativeType
     * @param string $message
     */
    protected function assertNotContainsOnly($type, $haystack, $isNativeType = null, $message = '')
    {
        Assert::assertNotContainsOnly($type, $haystack, $isNativeType, $message);
    }

    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param int $expectedCount
     * @param Countable|iterable $haystack
     * @param string $message
     */
    protected function assertNotCount($expectedCount, $haystack, $message = '')
    {
        Assert::assertNotCount($expectedCount, $haystack, $message);
    }

    /**
     * Asserts that a variable is not empty.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertNotEmpty($actual, $message = '')
    {
        Assert::assertNotEmpty($actual, $message);
    }

    /**
     * Asserts that two variables are not equal.
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertNotEquals($expected, $actual, $message = '')
    {
        TestCase::assertNotEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two variables are not equal (canonicalizing).
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertNotEqualsCanonicalizing($expected, $actual, $message = '')
    {
        TestCase::assertNotEqualsCanonicalizing($expected, $actual, $message);
    }

    /**
     * Asserts that two variables are not equal (ignoring case).
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertNotEqualsIgnoringCase($expected, $actual, $message = '')
    {
        TestCase::assertNotEqualsIgnoringCase($expected, $actual, $message);
    }

    /**
     * Asserts that two variables are not equal (with delta).
     *
     * @param $expected
     * @param $actual
     * @param float $delta
     * @param string $message
     */
    protected function assertNotEqualsWithDelta($expected, $actual, $delta, $message = '')
    {
        TestCase::assertNotEqualsWithDelta($expected, $actual, $delta, $message);
    }

    /**
     * Asserts that a condition is not false.
     *
     * @param $condition
     * @param string $message
     */
    protected function assertNotFalse($condition, $message = '')
    {
        Assert::assertNotFalse($condition, $message);
    }

    /**
     * Asserts that a variable is not of a given type.
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertNotInstanceOf($expected, $actual, $message = '')
    {
        Assert::assertNotInstanceOf($expected, $actual, $message);
    }

    /**
     * Asserts that a variable is not null.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertNotNull($actual, $message = '')
    {
        Assert::assertNotNull($actual, $message);
    }

    /**
     * Asserts that two variables do not have the same type and value.
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertNotSame($expected, $actual, $message = '')
    {
        Assert::assertNotSame($expected, $actual, $message);
    }

    /**
     * Assert that the size of two arrays (or `Countable` or `Traversable` objects) is not the same.
     *
     * @param Countable|iterable $expected
     * @param Countable|iterable $actual
     * @param string $message
     */
    protected function assertNotSameSize($expected, $actual, $message = '')
    {
        Assert::assertNotSameSize($expected, $actual, $message);
    }

    /**
     * Asserts that a condition is not true.
     *
     * @param $condition
     * @param string $message
     */
    protected function assertNotTrue($condition, $message = '')
    {
        Assert::assertNotTrue($condition, $message);
    }

    /**
     * Asserts that a variable is null.
     *
     * @param $actual
     * @param string $message
     */
    protected function assertNull($actual, $message = '')
    {
        Assert::assertNull($actual, $message);
    }

    /**
     * Asserts that an object has a specified attribute.
     *
     * @param string $attributeName
     * @param object $object
     * @param string $message
     */
    protected function assertObjectHasAttribute($attributeName, $object, $message = '')
    {
        Assert::assertObjectHasAttribute($attributeName, $object, $message);
    }

    /**
     * Asserts that an object does not have a specified attribute.
     *
     * @param string $attributeName
     * @param object $object
     * @param string $message
     */
    protected function assertObjectNotHasAttribute($attributeName, $object, $message = '')
    {
        Assert::assertObjectNotHasAttribute($attributeName, $object, $message);
    }

    /**
     * Asserts that two variables have the same type and value.
     *
     * @param $expected
     * @param $actual
     * @param string $message
     */
    protected function assertSame($expected, $actual, $message = '')
    {
        Assert::assertSame($expected, $actual, $message);
    }

    /**
     * Assert that the size of two arrays (or `Countable` or `Traversable` objects) is the same.
     *
     * @param Countable|iterable $expected
     * @param Countable|iterable $actual
     * @param string $message
     */
    protected function assertSameSize($expected, $actual, $message = '')
    {
        Assert::assertSameSize($expected, $actual, $message);
    }

    /**
     * @param string $needle
     * @param string $haystack
     * @param string $message
     */
    protected function assertStringContainsString($needle, $haystack, $message = '')
    {
        TestCase::assertStringContainsString($needle, $haystack, $message);
    }

    protected function assertStringContainsStringIgnoringCase($needle, $haystack, $message = '')
    {
        TestCase::assertStringContainsStringIgnoringCase($needle, $haystack, $message);
    }

    /**
     * Asserts that a string ends not with a given suffix.
     *
     * @param string $suffix
     * @param string $string
     * @param string $message
     */
    protected function assertStringEndsNotWith($suffix, $string, $message = '')
    {
        TestCase::assertStringEndsNotWith($suffix, $string, $message);
    }

    /**
     * Asserts that a string ends with a given suffix.
     *
     * @param string $suffix
     * @param string $string
     * @param string $message
     */
    protected function assertStringEndsWith($suffix, $string, $message = '')
    {
        TestCase::assertStringEndsWith($suffix, $string, $message);
    }

    /**
     * Asserts that the contents of a string is equal to the contents of a file.
     *
     * @param string $expectedFile
     * @param string $actualString
     * @param string $message
     */
    protected function assertStringEqualsFile($expectedFile, $actualString, $message = '')
    {
        Assert::assertStringEqualsFile($expectedFile, $actualString, $message);
    }

    /**
     * Asserts that the contents of a string is equal to the contents of a file (canonicalizing).
     *
     * @param string $expectedFile
     * @param string $actualString
     * @param string $message
     */
    protected function assertStringEqualsFileCanonicalizing($expectedFile, $actualString, $message = '')
    {
        Assert::assertStringEqualsFileCanonicalizing($expectedFile, $actualString, $message);
    }

    /**
     * Asserts that the contents of a string is equal to the contents of a file (ignoring case).
     *
     * @param string $expectedFile
     * @param string $actualString
     * @param string $message
     */
    protected function assertStringEqualsFileIgnoringCase($expectedFile, $actualString, $message = '')
    {
        Assert::assertStringEqualsFileIgnoringCase($expectedFile, $actualString, $message);
    }

    /**
     * Asserts that a string matches a given format string.
     *
     * @param string $format
     * @param string $string
     * @param string $message
     */
    protected function assertStringMatchesFormat($format, $string, $message = '')
    {
        Assert::assertStringMatchesFormat($format, $string, $message);
    }

    /**
     * Asserts that a string matches a given format file.
     *
     * @param string $formatFile
     * @param string $string
     * @param string $message
     */
    protected function assertStringMatchesFormatFile($formatFile, $string, $message = '')
    {
        Assert::assertStringMatchesFormatFile($formatFile, $string, $message);
    }

    /**
     * @param string $needle
     * @param string $haystack
     * @param string $message
     */
    protected function assertStringNotContainsString($needle, $haystack, $message = '')
    {
        TestCase::assertStringNotContainsString($needle, $haystack, $message);
    }

    /**
     * @param string $needle
     * @param string $haystack
     * @param string $message
     */
    protected function assertStringNotContainsStringIgnoringCase($needle, $haystack, $message = '')
    {
        TestCase::assertStringNotContainsStringIgnoringCase($needle, $haystack, $message);
    }

    /**
     * Asserts that the contents of a string is not equal to the contents of a file.
     *
     * @param string $expectedFile
     * @param string $actualString
     * @param string $message
     */
    protected function assertStringNotEqualsFile($expectedFile, $actualString, $message = '')
    {
        Assert::assertStringNotEqualsFile($expectedFile, $actualString, $message);
    }

    /**
     * Asserts that the contents of a string is not equal to the contents of a file (canonicalizing).
     * @param string $expectedFile
     * @param string $actualString
     * @param string $message
     */
    protected function assertStringNotEqualsFileCanonicalizing($expectedFile, $actualString, $message = '')
    {
        Assert::assertStringNotEqualsFileCanonicalizing($expectedFile, $actualString, $message);
    }

    /**
     * Asserts that the contents of a string is not equal to the contents of a file (ignoring case).
     *
     * @param string $expectedFile
     * @param string $actualString
     * @param string $message
     */
    protected function assertStringNotEqualsFileIgnoringCase($expectedFile, $actualString, $message = '')
    {
        Assert::assertStringNotEqualsFileIgnoringCase($expectedFile, $actualString, $message);
    }

    /**
     * Asserts that a string does not match a given format string.
     *
     * @param string $format
     * @param string $string
     * @param string $message
     */
    protected function assertStringNotMatchesFormat($format, $string, $message = '')
    {
        Assert::assertStringNotMatchesFormat($format, $string, $message);
    }

    /**
     * Asserts that a string does not match a given format string.
     *
     * @param string $formatFile
     * @param string $string
     * @param string $message
     */
    protected function assertStringNotMatchesFormatFile($formatFile, $string, $message = '')
    {
        Assert::assertStringNotMatchesFormatFile($formatFile, $string, $message);
    }

    /**
     * Asserts that a string starts not with a given prefix.
     *
     * @param string $prefix
     * @param string $string
     * @param string $message
     */
    protected function assertStringStartsNotWith($prefix, $string, $message = '')
    {
        Assert::assertStringStartsNotWith($prefix, $string, $message);
    }

    /**
     * Asserts that a string starts with a given prefix.
     *
     * @param string $prefix
     * @param string $string
     * @param string $message
     */
    protected function assertStringStartsWith($prefix, $string, $message = '')
    {
        Assert::assertStringStartsWith($prefix, $string, $message);
    }

    /**
     * Evaluates a PHPUnit\Framework\Constraint matcher object.
     *
     * @param $value
     * @param Constraint $constraint
     * @param string $message
     */
    protected function assertThat($value, $constraint, $message = '')
    {
        Assert::assertThat($value, $constraint, $message);
    }

    /**
     * Asserts that a condition is true.
     *
     * @param $condition
     * @param string $message
     */
    protected function assertTrue($condition, $message = '')
    {
        Assert::assertTrue($condition, $message);
    }

    /**
     * Asserts that two XML files are equal.
     *
     * @param string $expectedFile
     * @param string $actualFile
     * @param string $message
     */
    protected function assertXmlFileEqualsXmlFile($expectedFile, $actualFile, $message = '')
    {
        Assert::assertXmlFileEqualsXmlFile($expectedFile, $actualFile, $message);
    }

    /**
     * Asserts that two XML files are not equal.
     *
     * @param string $expectedFile
     * @param string $actualFile
     * @param string $message
     */
    protected function assertXmlFileNotEqualsXmlFile($expectedFile, $actualFile, $message = '')
    {
        Assert::assertXmlFileNotEqualsXmlFile($expectedFile, $actualFile, $message);
    }

    /**
     * Asserts that two XML documents are equal.
     *
     * @param string $expectedFile
     * @param DOMDocument|string $actualXml
     * @param string $message
     */
    protected function assertXmlStringEqualsXmlFile($expectedFile, $actualXml, $message = '')
    {
        Assert::assertXmlStringEqualsXmlFile($expectedFile, $actualXml, $message);
    }

    /**
     * Asserts that two XML documents are equal.
     *
     * @param DOMDocument|string $expectedXml
     * @param DOMDocument|string $actualXml
     * @param string $message
     */
    protected function assertXmlStringEqualsXmlString($expectedXml, $actualXml, $message = '')
    {
        Assert::assertXmlStringEqualsXmlString($expectedXml, $actualXml, $message);
    }

    /**
     * Asserts that two XML documents are not equal.
     *
     * @param string $expectedFile
     * @param DOMDocument|string $actualXml
     * @param string $message
     */
    protected function assertXmlStringNotEqualsXmlFile($expectedFile, $actualXml, $message = '')
    {
        Assert::assertXmlStringNotEqualsXmlFile($expectedFile, $actualXml, $message);
    }

    /**
     * Asserts that two XML documents are not equal.
     *
     * @param DOMDocument|string $expectedXml
     * @param DOMDocument|string $actualXml
     * @param string $message
     */
    protected function assertXmlStringNotEqualsXmlString($expectedXml, $actualXml, $message = '')
    {
        Assert::assertXmlStringNotEqualsXmlString($expectedXml, $actualXml, $message);
    }

    /**
     * Fails a test with the given message.
     *
     * @param string $message
     */
    protected function fail($message = '')
    {
        Assert::fail($message);
    }

    /**
     * Mark the test as incomplete.
     *
     * @param string $message
     */
    protected function markTestIncomplete($message = '')
    {
        Assert::markTestIncomplete($message);
    }

    /**
     * Mark the test as skipped.
     *
     * @param string $message
     */
    protected function markTestSkipped($message = '')
    {
        Assert::markTestSkipped($message);
    }
}