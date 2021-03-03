
## Codeception\Stub\Expected



#### *public static* never($params = null) 
Checks if a method never has been invoked

If method invoked, it will immediately throw an
exception.

```php
<?php
use \Codeception\Stub\Expected;

$user = $this->make('User', [
     'getName' => Expected::never(),
     'someMethod' => function() {}
]);
$user->someMethod();
?>
```

 * `param mixed` $params
 * return StubMarshaler

#### *public static* once($params = null) 
Checks if a method has been invoked exactly one
time.

If the number is less or greater it will later be checked in verify() and also throw an
exception.

```php
<?php
use \Codeception\Stub\Expected;

$user = $this->make(
    'User',
    array(
        'getName' => Expected::once('Davert'),
        'someMethod' => function() {}
    )
);
$userName = $user->getName();
$this->assertEquals('Davert', $userName);
?>
```
Alternatively, a function can be passed as parameter:

```php
<?php
Expected::once(function() { return Faker::name(); });
```

 * `param mixed` $params

 * return StubMarshaler

#### *public static* atLeastOnce($params = null) 
Checks if a method has been invoked at least one
time.

If the number of invocations is 0 it will throw an exception in verify.

```php
<?php
use \Codeception\Stub\Expected;

$user = $this->make(
    'User',
    array(
        'getName' => Expected::atLeastOnce('Davert')),
        'someMethod' => function() {}
    )
);
$user->getName();
$userName = $user->getName();
$this->assertEquals('Davert', $userName);
?>
```

Alternatively, a function can be passed as parameter:

```php
<?php
Expected::atLeastOnce(function() { return Faker::name(); });
```

 * `param mixed` $params

 * return StubMarshaler

#### *public static* exactly($count, $params = null) 
Checks if a method has been invoked a certain amount
of times.
If the number of invocations exceeds the value it will immediately throw an
exception,
If the number is less it will later be checked in verify() and also throw an
exception.

``` php
<?php
use \Codeception\Stub;
use \Codeception\Stub\Expected;

$user = $this->make(
    'User',
    array(
        'getName' => Expected::exactly(3, 'Davert'),
        'someMethod' => function() {}
    )
);
$user->getName();
$user->getName();
$userName = $user->getName();
$this->assertEquals('Davert', $userName);
?>
```
Alternatively, a function can be passed as parameter:

```php
<?php
Expected::exactly(function() { return Faker::name() });
```

 * `param int` $count
 * `param mixed` $params

 * return StubMarshaler


