<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Faker Extension for Yii 2</h1>
    <br>
</p>

This extension provides a [`Faker`](https://github.com/fzaninotto/Faker) fixture command for the [Yii framework 2.0](http://www.yiiframework.com).

For license information check the [LICENSE](LICENSE.md)-file.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-faker.svg)](https://packagist.org/packages/yiisoft/yii2-faker)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-faker.svg)](https://packagist.org/packages/yiisoft/yii2-faker)
[![Build Status](https://github.com/yiisoft/yii2-faker/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-faker/actions)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiisoft/yii2-faker
```

or add

```json
"yiisoft/yii2-faker": "~2.0.0"
```

to the require section of your composer.json.


Usage
-----

To use this extension,  simply add the following code in your application configuration (console.php):

```php
'controllerMap' => [
    'fixture' => [
        'class' => 'yii\faker\FixtureController',
    ],
],
```
