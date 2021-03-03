
## Codeception\Specify\ConfigBuilder



Configure Specify usage.

Specify copies properties of object and restores them for each specify block.
Objects can be cloned deeply or using standard `clone` operator.
Specify can be configured to prevent specific properties in specify blocks, to choose default cloning method,
or cloning method for specific properties.

```php
<?php
$this->specifyConfig()
 ->ignore('user') // do not clone
?>
```

#### *public* __construct($config = null) 
#### *public* ignore($properties = null) 
Ignore cloning specific object properties in specify blocks.

```php
<?php
$this->user = new User;
$this->specifyConfig()->ignore('user');
$this->specify('change user name', function() {
     $this->user->name = 'davert';
});
$this->user->name == 'davert'; // name changed
?>
```

 * `param array` $properties
 * `return` $this

#### *public* ignoreClasses($classes = null) 
Adds specific class to ignore list, if property is an instance of class it will not be cloned for specify block.

 * `param array` $classes
 * `return` $this

#### *public* deepClone($properties = null) 
Turn on/off deep cloning mode.
Deep cloning mode can also be specified for specific properties.

```php
<?php
$this->user = new User;
$this->post = new Post;
$this->tag = new Tag;

// turn on deep cloning by default
$this->specifyConfig()->deepClone();

// turn off deep cloning by default
$this->specifyConfig()->deepClone(false);

// deep clone only user and tag property
$this->specifyConfig()->deepClone('user', 'tag');

// alternatively
$this->specifyConfig()->deepClone(['user', 'tag']);
?>
```

 * `param bool` $properties
 * `return` $this

#### *public* shallowClone($properties = null) 
Disable deep cloning mode, use shallow cloning by default, which is faster.
Deep cloning mode can also be disabled for specific properties.

```php
<?php
$this->user = new User;
$this->post = new Post;
$this->tag = new Tag;

// turn off deep cloning by default
$this->specifyConfig()->shallowClone();

// turn on deep cloning by default
$this->specifyConfig()->shallowClone(false);

// shallow clone only user and tag property
$this->specifyConfig()->shallowClone('user', 'tag');

// alternatively
$this->specifyConfig()->shallowClone(['user', 'tag']);
?>
```

 * `param bool` $properties
 * `return` $this

#### *public* cloneOnly($properties) 
Clone only specific properties

```php
<?php
$this->specifyConfig()->cloneOnly('user', 'post');
?>
```

 * `param` $properties
 * `return` $this


