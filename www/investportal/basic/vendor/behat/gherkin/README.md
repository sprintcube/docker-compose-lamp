Behat Gherkin Parser
====================

This is the php Gherkin parser for Behat. It comes bundled with more than 40 native languages
(see `i18n.php`) support & clean architecture.

[![Build Status](https://travis-ci.org/Behat/Gherkin.svg?branch=master)](https://travis-ci.org/Behat/Gherkin)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Behat/Gherkin/badges/quality-score.png?s=04d9d0237c89f4c45a94ba5304234db861dfd036)](https://scrutinizer-ci.com/g/Behat/Gherkin/)
[![Code Coverage](https://scrutinizer-ci.com/g/Behat/Gherkin/badges/coverage.png?s=204ca44800469d295b73d18c91011cb9d2dc99fc)](https://scrutinizer-ci.com/g/Behat/Gherkin/)

Useful Links
------------

- Official Google Group is at [http://groups.google.com/group/behat](http://groups.google.com/group/behat)
- IRC channel on [#freenode](http://freenode.net/) is `#behat`
- [Note on Patches/Pull Requests](CONTRIBUTING.md)

Usage Example
-------------

``` php
<?php

$keywords = new Behat\Gherkin\Keywords\ArrayKeywords(array(
    'en' => array(
        'feature'          => 'Feature',
        'background'       => 'Background',
        'scenario'         => 'Scenario',
        'scenario_outline' => 'Scenario Outline|Scenario Template',
        'examples'         => 'Examples|Scenarios',
        'given'            => 'Given',
        'when'             => 'When',
        'then'             => 'Then',
        'and'              => 'And',
        'but'              => 'But'
    ),
    'en-pirate' => array(
        'feature'          => 'Ahoy matey!',
        'background'       => 'Yo-ho-ho',
        'scenario'         => 'Heave to',
        'scenario_outline' => 'Shiver me timbers',
        'examples'         => 'Dead men tell no tales',
        'given'            => 'Gangway!',
        'when'             => 'Blimey!',
        'then'             => 'Let go and haul',
        'and'              => 'Aye',
        'but'              => 'Avast!'
    )
));
$lexer  = new Behat\Gherkin\Lexer($keywords);
$parser = new Behat\Gherkin\Parser($lexer);

$feature = $parser->parse(file_get_contents('some.feature'));
```

Installing Dependencies
-----------------------

``` bash
$> curl http://getcomposer.org/installer | php
$> php composer.phar update
```

Contributors
------------

* Konstantin Kudryashov [everzet](http://github.com/everzet) [lead developer]
* Other [awesome developers](https://github.com/Behat/Gherkin/graphs/contributors)
