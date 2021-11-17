Yii Framework 2 gii extension Change Log
========================================

2.2.3 August 09, 2021
---------------------

- Enh #453: Allow CRUD to work with non-RDBMS ARs (WinterSilence)
- Enh #458: Add CIDR support for allowedIPs (rhertogh)
- Enh #462: Add support for viewing file differences on the CLI (rhertogh)


2.2.2 May 06, 2021
------------------

- Bug #433: Fix insufficient category validation (samdark)
- Bug #439: Replace client-side generation of model class name with an AJAX request and a serverside implementation to take options into account (WinterSilence)
- Enh #444: Updated reserved keywords in generator (WinterSilence)
- Enh #450: Add behaviors merging, pagination example, sorting example, loading defaults for a model to CRUD controller (WinterSilence)


2.2.1 May 02, 2020
------------------

- Bug #428: Permit the usage of anonymous generators using dependency injection (aguevaraIL)


2.2.0 March 24, 2020
--------------------

- Enh #424: Added support for `via()` junction relations in model generator (rhertogh)


2.1.4 January 17, 2020
----------------------

- Bug #422: Fix relational query getter documentation style (mikk150)
- Enh #287: Model generator is now generating relation's phpdoc hints with target ActiveQuery class (bscheshirwork)


2.1.3 November 19, 2019
-----------------------

- Bug #417: Fixed issue where RTL implementation for foreign keys causes problems with LTR tables names (NickvdMeij)
- Enh #416: Improved generation of model attributes and type annotations (uldisn)


2.1.2 October 08, 2019
----------------------

- Bug #413: Controller Generator produces invalid alias when namespace starts with backslash (cebe)


2.1.1 August 13, 2019
---------------------

- Bug #410: Inserted rows in the diff were not highlighted (albertborsos)


2.1.0 March 17, 2019
--------------------

- Enh #390, Bug #260: Create (bootstrap)-independent version (simialbi)
- Bug #386: Move "Create" button outside of pjax container to avoid redirect (alexkart)
- Bug #398, #397: Use strict mode when generating view folder name (machour)
- Enh #395: Made `yii\gii\CodeFile` independent of controller context, do not apply `$newDirMode` and `$newFileMode` if module is not available (CeBe)
- Enh #399: Option to allow singularize class names in model generator (alexkart)


2.0.8 December 08, 2018
-----------------------

- Bug #327: Fixed bug in Model generator when $baseClass is an abstract class (rhertogh)
- Bug #379: Fixed bug in view page where delete button not work well (zacksleo)
- Bug #383: Fix incorrect title generation in CRUD update view (bscheshirwork)
- Enh #366: Option to allow standardized class names capitals in model generator (slinstj)
- Enh #378: Remove useless import of `Yii` from CRUD generator search model template (CeBe)


2.0.7 May 3, 2018
-----------------

- Bug #185: Fixed bug in Model generators when FKs pointing to non-existing tables (adipriyantobpn)
- Bug #328: Fixed bug in CRUD update view generator (ricpelo)
- Bug #333: Fixed incorrect validation rule for TINYINT column type (nostop8)
- Bug #340: Fixed bug in CRUD SearchModel generator (JeanWolf)
- Bug #351: Fixed incorrect validation rule for JSON column type (silverfire)


2.0.6 December 23, 2017
-----------------------

- Bug #97: Fixed errors and wrong directories created when using backslash in view paths and output paths of CRUD, Controller and Extension generators (lubosdz, samdark)
- Bug #100, #102: Fixed "Check This File" button in the preview modal (Insensus, thiagotalma)
- Bug #126, #139: Fixed model generator form validation when "ActiveQuery Class" is invalid but unused (kikimor)
- Bug #149: Relation names no longer override existing methods and properties (Faryshta)
- Bug #152: Fixed generating model without any rules (and800)
- Bug #166: Fixed "Trying to get property of non-object" during model generation (zlakomanoff)
- Bug #179: Fixed indentation and newlines for Pjax widget in CRUD index view (nkovacs)
- Bug #182: Fixed wrong link after generating controller located in sub-namespace of controllers namespace (MKiselev)
- Bug #186: Fixed incorrect database name exception (zlakomanoff, shirase)
- Bug #198: Fixed false-positive detection of URL fields in CRUD generator (cebe)
- Bug #200: Fixed Pjax and Listview with CRUD generator (ariestattoo)
- Bug #224: Add default validator with `null` value for integers when db is PostgreSQL (MKiselev)
- Bug #232: Fixed Help documentation link (drdim)
- Bug #255: Fixed error when getting database driver name when db is not an instance of `yii\db\Connection` (MKiselev)
- Bug #271: Fixed absolute namespace of model class in form generator (CeBe, amin3mej)
- Bug #274: Added `useTablePrefix` and `generateQuery` to `stickyAttributes` (luyi61)
- Bug #290: Fixed model generator to work properly with `schema.table` as table name (SwoDs)
- Bug #317: Force HTML content type in response to display HTML when app is configured for REST API (microThread)
- Bug #318: Use `yii\base\BaseObject` instead `yii\base\Object` in `CodeFile.php` (MKiselev)
- Enh #131: Allow using table comments for PHPdoc property description (stmswitcher, michaelarnauts)
- Enh #153: Added filename filter to generated files list preview (thiagotalma)
- Enh #162: Model generator now detects foreign keys named as `id_*` (mootensai, samdark)
- Enh #167: Added "generating relations from current schema" option to model generator (zlakomanoff)
- Enh #174: `NotFoundHttpException` message in CRUD now uses i18n (bscheshirwork)
- Enh #223: Use `ilike` operator when generating search model for PostgreSQL (MKiselev, arogachev)
- Enh #230: Allowed underscores for extension namespaces (Nex Otaku)
- Enh #234: Changed submit button label from "Update" and "Create" to "Save" (MKiselev)
- Enh #238: Use `int`/`bool` instead of `integer`/`boolean` in phpdoc blocks generated (MKiselev)
- Enh #241: Remove message for unique validator (MKiselev)
- Enh #249: unique validation rule is now generated for tables with multiple primary keys (dmirogin)
- Enh #252: Added meta tag to prevent indexing of debug by search engines in case it's exposed (bashkarev)
- Enh #293: Do not generate redundant `else` after `return` (bscheshirwork)
- Enh #295: Allowed to use aliases in generator's templates (dmirogin)
- Enh #300: Removed space from commented out code so when uncommenting in IDEs there's no extra spacing (bscheshirwork)
- Enh #315: Make `yii\gii\generators\model\Generator` `generateProperties` protected (claudejanz)
- Enh #319: Added `@throws` tags for 404 exceptions in CRUD actions (and800)
- Enh: `yii\gii\Module::defaultVersion()` implemented to pick up 'yiisoft/yii2-gii' extension version (klimov-paul)
- Chg #246: Changed the way CRUD generator translates "Update X id". Now it's a whole string because of translation difficulties  (bscheshirwork)


2.0.5 March 18, 2016
--------------------

- Bug #66: It was impossible to use tables with spaces (cornernote)
- Bug #79: There was no form element to toggle using schema name for class name (phpniki)
- Bug #83: Files were overwritten regardless of answers in console Gii (chernyshev, jeicd)
- Bug #104: Allow reuse of the Gii Module for running multiple actions (cebe)
- Bug #109: Exception was thrown when `yii\rest\UrlRule` was used in `UrlManager::ruleConfig` (lichunqiang)
- Bug #116: Added table prefix autoremoving from the generated model className (umanamente, silverfire)
- Bug #134: Model generator was not validating ActiveQuery namespace (zetamen)
- Enh #20: Added support for composite (multi-column) foreign keys in junction tables (nineinchnick)
- Enh #34: Model generator now skips FKs pointing to non-existing tables (samdark)
- Enh #42: Entire preview code now can be copied by pressing CTRL+C (thiagotalma, samdark)
- Enh #54: Model generator is now able to generate reverse relations (nineinchnick)
- Enh #56: Model generator now generates exist rules based on table foreign keys (Faryshta, samdark)
- Enh #95: More parameters are now available in `query.php` view of model generator (demisang)
- Enh #99: Added `enablePjax` option to wrap GridView with Pjax (Faryshta, silverfire)
- Enh #135: Footer now sticks to the bottom of the page (zetamen)
- Chg #38: Added compatibility with latest Typeahead version (razvanphp)


2.0.4 May 10, 2015
------------------

- Bug #5098: Properly detect hasOne relations (nineinchnick)
- Bug #6667: Gii form generator rendering mistake view (pana1990)
- Bug (CVE-2015-3397): Using `Json::htmlEncode()` for safer JSON data encoding in HTML code (samdark, Tomasz Tokarski)
- Enh #2109: Added ability to generate ActiveQuery class for model (klimov-paul)
- Enh #7830: Added ability to detect relations between multiple schemas (nineinchnick)


2.0.3 March 01, 2015
--------------------

- Chg #7328: Changed the way CRUD generator translates "Create X". Now it's a whole string because of translation difficulties (samdark)


2.0.2 January 11, 2015
----------------------

- Bug #6463: The Gii controller generator generates incorrect controller namespace (pana1990)
- Enh #3665: Better default behavior for ModelSearch generated by the crud generator (qiangxue, mdmunir)


2.0.1 December 07, 2014
-----------------------

- Bug #5070: Gii controller generator should use controller class name instead of controller ID to specify new controller (qiangxue)
- Bug #5745: Gii and debug modules may cause 404 exception when the route contains dashes (qiangxue)
- Bug #6367: Added `yii\gii\generators\crud\Generator` to support customizing view path for the generated CRUD controller (qiangxue)
- Bug: Gii console command help information does not contain global options (qiangxue)
- Enh #5613: Added `--overwrite` option to Gii console command to support overwriting all files (motin, qiangxue)


2.0.0 October 12, 2014
----------------------

- Bug #5408: Gii console command incorrectly reports errors when there is actually no error (qiangxue)
- Bug: Fixed table name regression caused by changed introduced in #4971 (samdark)


2.0.0-rc September 27, 2014
---------------------------

- Bug #1263: Fixed the issue that Gii and Debug modules might be affected by incompatible asset manager configuration (qiangxue)
- Bug #2314: Gii model generator does not generate correct relation type in some special case (qiangxue)
- Bug #3265: Fixed incorrect controller class name validation (suralc)
- Bug #3693: Fixed broken Gii preview when a file is unchanged (cebe)
- Bug #4410: Fixed Gii to preserve database column order in generated _form.php  (kmindi)
- Bug #4971: Fixed hardcoded table names in `viaTable` expression in model generator (stepanselyuk)
- Enh #2018: Search model is not required anymore in CRUD generator (johonunu)
- Enh #3088: The gii module will manage their own URL rules now (qiangxue)
- Enh #3222: Added `useTablePrefix` option to the model generator for Gii (horizons2)
- Enh #3811: Now Gii model generator makes autocomplete for model class field (mitalcoi)
- New #1280: Gii can now be run from command line (schmunk42, cebe, qiangxue)


2.0.0-beta April 13, 2014
-------------------------

- Bug #1405: fixed disambiguation of relation names generated by gii (qiangxue)
- Bug #1904: Fixed autocomplete to work with underscore inputs "_" (tonydspaniard)
- Bug #2298: Fixed the bug that Gii controller generator did not allow digit in the controller ID (qiangxue)
- Bug #2712: Fixed missing id in code file preview url (klevron)
- Bug: fixed controller in crud template to avoid returning query in findModel() (cebe)
- Enh #1624: generate rules for unique indexes (lucianobaraglia)
- Enh #1818: Do not display checkbox column if all rows are empty (johonunu)
- Enh #1897: diff markup is now copy paste friendly (samdark)
- Enh #2327: better visual representation of changed files, added header and refresh button to diff modal (thiagotalma)
- Enh #2491: Added support for using the same base class name of search model and data model in Gii (qiangxue)
- Enh #2595: Browse through all generated files using right and left arrows (thiagotalma)
- Enh #2633: Keyboard shortcuts to browse through files (thiagotalma)
- Enh #2822: possibility to generate I18N messages (lucianobaraglia)
- Enh #2843: Option to filter files according to the action. (thiagotalma)

2.0.0-alpha, December 1, 2013
-----------------------------

- Initial release.
