Yii Framework 2 composer extension Change Log
=============================================

2.0.10 June 24, 2020
--------------------

- Enh #31: Add Composer 2 parallel unzip compatibility (samdark)


2.0.9 April 20, 2020
--------------------

- Bug #30: Fix PHP error when upgrading/downgrading a Yii2 extension (brandonkelly)
- Enh #27: Support for Composer 2 (brandonkelly, cebe)


2.0.8 July 16, 2019
-------------------

- Bug #23: Fixed another an error that would occur if the Zend OPcache extension was installed, but its "restrict_api" setting was enabled (Lachee)


2.0.7 July 05, 2018
-------------------

- Bug #18: Fixed an error that would occur if the Zend OPcache extension was installed, but its "restrict_api" setting was enabled (angrybrad)


2.0.6 March 21, 2018
--------------------

- Bug #16: Upgrade notes were not shown when upgrading from a patch version (cebe)


2.0.5 December 20, 2016
-----------------------

- Bug #11: `generateCookieValidationKey()` now saves config file only when `cookieValidationKey` was generated (rob006)
- Enh #10: Added `yii\composer\Installer::postInstall()` method (rob006)
- Enh #12: Added `yii\composer\Installer::copyFiles()` method (rob006)
- Enh #14: A note about yii UPGRADE notes file is shown after upgrading Yii to make user aware of it (cebe)


2.0.4 February 06, 2016
-----------------------

- Bug #7735: Composer failed to install extensions with multiple base paths in "psr-4" autoload section (cebe)
- Enh #2: Better error handling for the case when installer is unable to change permissions (dbavscc)
- Enh #3: `loadExtensions()` and `saveExtensions()` now access `EXTENSION_FILE` constant with late static binding (karneds)


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

- Bug #3438: Fixed support for non-lowercase package names (cebe)
- Chg: Added `yii\composer\Installer::postCreateProject()` and modified the syntax of calling installer methods in composer.json (qiangxue)

2.0.0-beta April 13, 2014
-------------------------

- Bug #1480: Fixed issue with creating extensions.php when php opcache is enabled (cebe)
- Enh: Added support for installing packages conforming to PSR-4 standard (qiangxue)

2.0.0-alpha, December 1, 2013
-----------------------------

- Initial release.
