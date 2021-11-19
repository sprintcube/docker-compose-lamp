
## Codeception\Specify\Config



Global Specify configuration. Should be set in bootstrap.

```php
<?php
// disable deep cloning of properties inside specify block
\Codeception\Specify\Config::setDeepClone(false);
?>
```

#### *public* propertyIgnored($property) 
#### *public* classIgnored($value) 
#### *public* propertyIsShallowCloned($property) 
#### *public* propertyIsDeeplyCloned($property) 
#### *public static* setDeepClone($deepClone) 
Enable or disable using of deep cloning for objects by default.
Deep cloning is the default.

 * `param boolean` $deepClone

#### *public static* setIgnoredClasses($ignoredClasses) 
#### *public static* setIgnoredProperties($ignoredProperties) 
Globally set class properties are going to be ignored for cloning in specify blocks.

```php
<?php
\Codeception\Specify\Config::setIgnoredProperties(['users', 'repository']);
```

 * `param array` $ignoredProperties

#### *public static* addIgnoredClasses($ignoredClasses) 
Add specific classes to cloning ignore list. Instances of those classes won't be cloned for specify blocks.

```php
<?php
\Codeception\Specify\Config::addIgnoredClasses(['\Acme\Domain\UserRepo', '\Acme\Domain\PostRepo']);
?>
```

 * `param` $ignoredClasses

#### *public static* create() 
@return Config


