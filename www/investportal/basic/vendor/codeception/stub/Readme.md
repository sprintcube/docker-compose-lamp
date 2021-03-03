# Codeception\Stub

[![Build Status](https://travis-ci.org/Codeception/Stub.svg?branch=master)](https://travis-ci.org/Codeception/Stub)
[![Latest Stable Version](https://poser.pugx.org/codeception/stub/v/stable)](https://packagist.org/packages/codeception/stub)
[![Total Downloads](https://poser.pugx.org/codeception/stub/downloads)](https://packagist.org/packages/codeception/stub)
[![License](https://poser.pugx.org/codeception/stub/license)](https://packagist.org/packages/codeception/stub)

Library on top of PHPUnit's mock builder providing a highly simplified syntax:

## Reference

* [Stub](https://github.com/Codeception/Stub/blob/master/docs/Stub.md) - creating stub classes using static methods
* [Stub Trait](https://github.com/Codeception/Stub/blob/master/docs/StubTrait.md) - creating stubs and mocks using trait
* [Expected](https://github.com/Codeception/Stub/blob/master/docs/Expected.md) - defining expectations for mocks

## Install

Enabled by default in Codeception.
For PHPUnit install this package:

```
composer require codeception/stub --dev
```

## Stubs

Stubs can be constructed with `Codeception\Stub` static calls:

```php
<?php
// create a stub with find method replaced
$userRepository = Stub::make(UserRepository::class, ['find' => new User]);
$userRepository->find(1); // => User

// create a dummy
$userRepository = Stub::makeEmpty(UserRepository::class);

// create a stub with all methods replaced except one
$user = Stub::makeEmptyExcept(User::class, 'validate');
$user->validate($data);

// create a stub by calling constructor and replacing a method
$user = Stub::construct(User::class, ['name' => 'davert'], ['save' => false]);

// create a stub by calling constructor with empty methods
$user = Stub::constructEmpty(User::class, ['name' => 'davert']);

// create a stub by calling constructor with empty methods
$user = Stub::constructEmptyExcept(User::class, 'getName', ['name' => 'davert']);
$user->getName(); // => davert
$user->setName('jane'); // => this method is empty
$user->getName(); // => davert 
```

[See complete reference](https://github.com/Codeception/Stub/blob/master/docs/Stub.md)

Alternatively, stubs can be created by using [`Codeception\Test\Feature\Stub` trait](https://github.com/Codeception/Stub/blob/master/docs/StubTrait.md):

```php
<?php
$this->make(UserRepositry::class);
$this->makeEmpty(UserRepositry::class);
$this->construct(UserRepositry::class);
$this->constructEmpty(UserRepositry::class);
// ...
```

## Mocks

Mocks should be created by including [`Codeception\Test\Feature\Stub` trait](https://github.com/Codeception/Stub/blob/master/docs/StubTrait.md) into a test case.
Execution expectation are set with [`Codeception\Stub\Expected`](https://github.com/Codeception/Stub/blob/master/docs/Expected.md):

```php
<?php
// find should be never called
$userRepository = $this->make(UserRepository::class, [
    'find' => Codeception\Stub\Expected::never()
]);

// find should be called once and return a new user
$userRepository = $this->make(UserRepository::class, [
    'find' => Codeception\Stub\Expected::once(new User)
]);
```


## License 

MIT
