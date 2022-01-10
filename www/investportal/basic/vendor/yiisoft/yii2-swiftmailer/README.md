<p align="center">
    <a href="https://swiftmailer.symfony.com/" target="_blank" rel="external">
        <img src="https://swiftmailer.symfony.com/images/logo.png" height="68px" style="background-color:#2a4fb7">
    </a>
    <h1 align="center">SwiftMailer Extension for Yii 2</h1>
    <br>
</p>

This extension provides a [SwiftMailer](https://swiftmailer.symfony.com/) mail solution for [Yii framework 2.0](http://www.yiiframework.com).

For license information check the [LICENSE](LICENSE.md)-file.

[![Latest Stable Version](https://poser.pugx.org/yiisoft/yii2-swiftmailer/v/stable.png)](https://packagist.org/packages/yiisoft/yii2-swiftmailer)
[![Total Downloads](https://poser.pugx.org/yiisoft/yii2-swiftmailer/downloads.png)](https://packagist.org/packages/yiisoft/yii2-swiftmailer)
[![Build Status](https://github.com/yiisoft/yii2-swiftmailer/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-swiftmailer/actions)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiisoft/yii2-swiftmailer
```

or add

```json
"yiisoft/yii2-swiftmailer": "~2.1.0"
```

to the require section of your composer.json.

> Note: Version 2.1 of this extensions uses Swiftmailer 6, which requires PHP 7. If you are using PHP 5, 
> you have to use version 2.0 of this extension, which uses Swiftmailer 5, which is compatible with 
> PHP 5.4 and higher. Use the following version constraint in that case:
> 
> ```json
> "yiisoft/yii2-swiftmailer": "~2.0.0"
> ```

Usage
-----

To use this extension,  simply add the following code in your application configuration:

```php
return [
    //....
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
    ],
];
```

You can then send an email as follows:

```php
Yii::$app->mailer->compose('contact/html')
     ->setFrom('from@domain.com')
     ->setTo($form->email)
     ->setSubject($form->subject)
     ->send();
```

For further instructions refer to the [related section in the Yii Definitive Guide](http://www.yiiframework.com/doc-2.0/guide-tutorial-mailing.html).

