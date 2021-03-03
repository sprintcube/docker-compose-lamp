Yii Framework 2 faker extension Change Log
==============================================

2.0.5 November 10, 2020
-----------------------

- Chg: Switch to faker/faker to ensure PHP 8 compatibility (samdark)


2.0.4 February 19, 2018
-----------------------

- Bug #29: Fixed `FixtureController::findTemplatesFiles()` trim `$templatePath` from `$fileName` correctly via `DIRECTORY_SEPARATOR` (ofixone)
- Enh #22: Made `FixtureController` private methods protected for better class extensibility (samdark)
- Enh #24: Added support for fixture templates in subdirectories (d1rtyf1ng3rs)
- Enh #28: `FixtureController::generateFixtureFile()` now uses `$templateName` to index fixtures for easier debug (drsdre)
- Chg: Switched to asset-packagist


2.0.3 March 01, 2015
--------------------

- no changes in this release.


2.0.2 January 11, 2015
----------------------

- no changes in this release.


2.0.1 December 07, 2014
-----------------------

- no changes in this release.


2.0.0 October 12, 2014
----------------------

- no changes in this release.


2.0.0-rc September 27, 2014
---------------------------

- Chg #4622: Simplified the way of creating a Faker fixture template file (qiangxue)


2.0.0-beta April 13, 2014
-------------------------

- Initial release.
