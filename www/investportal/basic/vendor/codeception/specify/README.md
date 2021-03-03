Specify
=======

BDD style code blocks for PHPUnit / Codeception

Specify allows you to write your tests in more readable BDD style, the same way you might have experienced with [Jasmine](https://jasmine.github.io/).
Inspired by MiniTest of Ruby now you combine BDD and classical TDD style in one test.

[![Build Status](https://travis-ci.org/Codeception/Specify.png?branch=master)](https://travis-ci.org/Codeception/Specify) [![Latest Stable Version](https://poser.pugx.org/codeception/specify/v/stable.png)](https://packagist.org/packages/codeception/specify)

Additionaly, we recommend to combine this with [**Codeception/Verify**](https://github.com/Codeception/Verify) library, to get BDD style assertions.

``` php
<?php
class UserTest extends PHPUnit_Framework_TestCase {

	use Codeception\Specify;

	public function setUp()
	{		
		$this->user = new User;
	}

	public function testValidation()
	{
		$this->assertInstanceOf('Model', $this->user);

		$this->specify("username is required", function() {
			$this->user->username = null;
			verify($this->user->validate(['username'])->false());	
		});

		$this->specify("username is too long", function() {
			$this->user->username = 'toolooooongnaaaaaaameeee',
			verify($this->user->validate(['username'])->false());			
		});

		// alternative, TDD assertions can be used too.
		$this->specify("username is ok", function() {
			$this->user->username = 'davert',
			$this->assertTrue($this->user->validate(['username']));			
		});				
	}
}
?>
```

## Purpose

This tiny library makes your tests a bit readable, by orginizing test in well described code blocks.
Each code block is isolated. 

This means call to `$this->specify` does not affect any instance variable of a test class.

``` php
<?php
$this->user->name = 'davert';
$this->specify("i can change my name", function() {
   $this->user->name = 'jon';
   $this->assertEquals('jon', $this->user->name);
});
       
$this->assertEquals('davert', $this->user->name);
?>        
```

Failure in `specify` block won't get your test stopped.

``` php
<?php
$this->specify("failing but test goes on", function() {
	$this->fail('bye');
});
$this->assertTrue(true);

// Assertions: 2, Failures: 1
?>
```

If a test fails you will see specification text in the result.

## Isolation

Isolation is achieved by **cloning object properties** for each specify block.
By default objects are cloned using deep cloning method.
This behavior can be customized in order to speed up test execution by preventing some objects from cloning or switching to shallow cloning using `clone` operator.
Some properties can be ignored from cloning using either global or local config settings.

### Global Configuration

Cloning configuration can be set globally

```php
<?php
// globally disabling cloning of properties
Codeception\Specify\Config::setIgnoredProperties(['user', 'repository']);
?>
```

See complete [reference](https://github.com/Codeception/Specify/blob/master/docs/GlobalConfig.md).

### Local Configuration

Configuring can be done locally per test case

```php
<?php
class UserTest extends \PHPUnit_Framework_TestCase
{
    use Codeception\Specify;

    function testUser()
    {
        // do not deep clone user property
        $this->specifyConfig()
            ->shallowClone('user');
    }
}
```

Only specific properties can be preserved in specify blocks:

```php
<?php
class UserTest extends \PHPUnit_Framework_TestCase
{
    use Codeception\Specify;
    protected $user;
    protected $post;

    function testUser()
    {
        $this->user = 'davert';
        $this->post = 'hello world';

        $this->specifyConfig()
            ->cloneOnly('user');

        $this->specify('post is not cloned', function() {
            $this->user = 'john';
            $this->post = 'bye world';
        });
        $this->assertEquals('davert', $this->user); // user is restored
        $this->assertEquals('bye world', $this->post); // post was not stored
    }
}
```


[Reference](https://github.com/Codeception/Specify/blob/master/docs/LocalConfig.md)


## Exceptions

You can wait for exception thrown inside a block.

``` php
<?php

$this->specify('404 if user does not exist', function() {
	$this->userController->show(999);
}, ['throws' => 'NotFoundException']);

// alternatively
$this->specify('404 if user does not exist', function() {
	$this->userController->show(999);
}, ['throws' => new NotFoundException]);
?>
```

Also you can handle fails inside a block. 

``` php
<?php

$this->specify('this assertion is failing', function() {
	$this->assertEquals(2, 3+5);
}, ['throws' => 'fail']);
?>
```

In both cases, you can optionally test the exception message

``` php
<?php

$this->specify('some exception with a message', function() {
	throw new NotFoundException('my error message');
}, ['throws' => ['NotFoundException', 'my error message']]);
?>
```

## Examples

DataProviders alternative. Quite useful for basic data providers.

``` php
<?php
$this->specify("should calculate square numbers", function($number, $square) {
	$this->assertEquals($square, $number*$number);
}, ['examples' => [
		[2,4],
		[3,9]
]]);
?>
```

You can also use DataProvider functions in `examples` param.

``` php
<?php
$this->specify("should calculate square numbers", function($number, $square) {
	$this->assertEquals($square, $number*$number);
}, ['examples' => $this->provider()]);
?>
```

## Before/After

There are also before and after callbacks, which act as setUp/tearDown but only for specify.

``` php
<?php
$this->beforeSpecify(function() {
	// prepare something;	
});
$this->afterSpecify(function() {
	// reset something
});
$this->cleanSpecify(); // removes before/after callbacks
?>
```

## Installation

*Requires PHP >= 5.4.*

Install with Composer:


```json
"require-dev": {
    "codeception/specify": "*",
    "codeception/verify": "*"

}
```
Include `Codeception\Specify` trait into your test.


License: MIT
