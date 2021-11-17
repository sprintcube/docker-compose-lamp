Yii Framework 2 debug extension Change Log
==========================================

2.1.18 August 09, 2021
----------------------

- Enh #458: Added CIDR support for allowedIPs (rhertogh)


2.1.17 May 05, 2021
-------------------

- Enh #454: Add `yii\debug\panels\DbPanel::$dbEventNames` that allows specifying event names used to get profile logs for db panel (atiline)


2.1.16 December 23, 2020
------------------------

- Bug #446: Fix bug with simple action config detection (bizley)
- Bug #449: Fix bug with scanned controllers being unnecessarily initiated (bizley)
- Enh #441: Added PHP 7.4 and 8.0 compatibility for tests (bizley)


2.1.15 November 13, 2020
------------------------

- Bug #443: Fix bug with fetching wrong URL rule name (bizley)


2.1.14 November 10, 2020
------------------------

- Bug #434: Toolbar logo could have the wrong size (brandonkelly)
- Enh #428: Extra routing data in Router panel (bizley)
- Enh #433: Add ability for toolbar to skip URLs of AJAX requests from being displayed (naduvko)


2.1.13 January 17, 2020
-----------------------

- Bug #427: Fix missing import yii\helpers\Html in DumpPanel (zhukovra)


2.1.12 November 19, 2019
------------------------

- Bug #424: Fixes missing timeline panel tooltips (My6UoT9)
- Enh #425: Add warning message related to `traceLevel` on db panel (kjusupov)


2.1.11 November 05, 2019
------------------------

- Bug #325: Remove staled data files i.e. files that are not in the current index file (zhukovra)
- Bug #329: Fix logging AJAX request if URL has domain (zhukovra)
- Bug #423: Fix duplicated toolbar when loading the iframe from a different origin (My6UoT9, samdark)
- Enh #202: Add buttons for navigation between requests (zhukovra)


2.1.10 October 22, 2019
-----------------------

- Enh #412: Improved layout of the Logs panel (machour, pistej)


2.1.9 September 18, 2019
------------------------

- Bug #409: Fixed "Since previous" column's value calculation (Ir00man)


2.1.8 August 20, 2019
---------------------

- Bug #333: Ignore normalizer in debug bar URL rules (simialbi)
- Bug #347: Fixed "Cannot read property 'addEventListener' of null" bug of user switch (simialbi)


2.1.7 July 30, 2019
-------------------

- Bug #401: Partial revert of #390, use var dumper on dump panel as serializer to deal with closures in SPL Objects (Sarke)
- Bug #404: Fix insufficient data escaping in debugger views (0xbug, Artem Myshenkov, samdark)
- Enh #40: Add "since previous" and sequential number to "logs" panel detail view (Deele, samdark)


2.1.6 July 23, 2019
-------------------

- Enh #167: Add total request processing time and peak memory consumption on index page (pistej)
- Enh #379: Better error message when no debug data is found (Sarke)
- Enh #380: Enhanced browser compatibility (samdark)
- Enh #390: Use opis/closure to serialize data (Sarke)
- Enh #392: Add `tracePathMappings` property to module (Sarke)


2.1.5 June 04, 2019
-------------------

- Enh #391: Dispatching event when toolbar is added to DOM, and added class that allows clicks in toolbar block (rhertogh)


2.1.4 May 14, 2019
------------------

- Bug #376: Fixed user panel when using custom user component (iridance)
- Bug #377: Fixed serialization of Closure error in `DumpPanel` (Sarke)
- Bug #381: Fixed text wrapping inside tables (machour)
- Bug #388: Fixed tooltip display in timeline (machour)
- Enh #376: Allow setting user panel title (iridance)


2.1.3 April 30, 2019
--------------------

- Enh #375: Made var dumper in `DumpPanel` extensible with `varDumpCallback` (Sarke)


2.1.2 April 23, 2019
--------------------

- Bug: ⚠️ in MailPanel was not a string (samdark)


2.1.1 March 30, 2019
--------------------

- Bug #237: Delay attaching to `View` events until action takes place (machour)
- Bug #275: Avoid initialization errors in `AssetPanel` (machour)
- Bug #298: Fix keyboard navigation when bar is collapsed/hidden (machour)
- Bug #354: Fixed the formatting of data in table cells (machour)
- Bug #355: Fixed color highlighting on debug index (Sarke)
- Bug #358: Enhance error message if `index.data` file is not accessible (machour)
- Bug #359: Fixed the console warning about sourcemaps (machour)
- Enh #103: Allow customizing the `UrlRule` class used to generate rules in bootstrap (machour)
- Enh #213: Made the debug panel resizeable, and allowed setting default height in configuration (machour)
- Enh #353: Added module `pageTitle` property that allows setting page title to be used (m-wardany)
- Enh #371: Improved toolbar accessibility (machour)


2.1.0 March 17, 2019
--------------------

- Bug #342: Toolbar animates on every page load in Chrome 70 (ricpelo)
- Bug #343: Fixed errors on "Roles and permissions" tab (simialbi)
- Bug #352: Fixed failure to serialize emails causing summary and thus all panels not to render (sammousa)
- Enh #88: Allow custom module access check (simialbi)
- Enh #247: Added dump panel that collects and displays debug messages with `Logger::LEVEL_TRACE` (pistej, simialbi)
- Enh #296, #326, #340: Removed bootstrap as dependency, bundled Bootstrap 4 (simialbi)
- Enh #349: Router link is now separated (rustamwin)
- Enh #350: Use smaller padding for tables cells (machour)


2.0.14 September 24, 2018
-------------------------

- Bug #300: Fixed email files are not deleted by GC (pistej)
- Bug #302: Fixed panel usage with suffixes in UrlManager (kyrylo-permiakov)
- Bug #327: Fix animation on page load when the toolbar is expanded (brandonkelly)
- Bug #332: Fix error when trace is missing from message (cornernote)
- Enh #77: Added "Events" panel (klimov-paul)
- Enh #301: Added configuration option to toggle IP address restriction warning on / off (jkrasniewski)
- Enh #311: Adjusted module's code to use `->get()` for dependencies (samdark)
- Enh #316: Prevent multiple lines in toolbar (ZAYEC77)


2.0.13 December 5, 2017
-----------------------

- Bug #284: Fixed "TypeError: input.substr is not a function" (leopold537)
- Bug #290: Fixed "fetch request profile link" (leopold537)
- Enh #274: Made user component configurable for `UserSwitch` and `UserPanel` (samdark)
- Enh #283: Send debug headers in AJAX requests in order to be able to link to debug panel from single page apps (glendemon)
- Enh #283: Duplicated queries count on DB panel (pistej)
- Enh #294: Added a "General Info" table to the Request panel (brandonkelly)
- Chg #292: Added PHP 7.2 compatibility (brandonkelly)
- Chg: Changed `default/view` not to depend on `db` panel (silverfire)


2.0.12 October 09, 2017
-----------------------

- Bug #271: Fixed regression in 2.0.11 causing debug fail with some custom classes implementing IdentityInterface (zertex)
- Bug #279: Fixed incomplete initialization of path aliases while using non-web application (samdark)


2.0.11 September 06, 2017
------------------------

- Bug #262: Fixed issue when identity ID is stored in a field different from `id` (samdark)
- Bug #265: Fixed calling `isMainUser()` on null regression in 2.0.10 (samdark)


2.0.10 September 04, 2017
-------------------------

- Bug #221: Fixed the decimal point issue in Timeline when using various locales (bashkarev)
- Bug #223: Limit the height during the opening animation (nkovacs)
- Bug #226: Fixed issue in user panel when you use custom RBAC module that does not implement `\yii\rbac\ManagerInterface` (pana1990)
- Bug #236: Fixed rendering AJAX errors to use `innerText` instead of `innerHTML` (samdark)
- Bug #239: Fixed an issue in the user panel when using console application with debug module enabled (pana1990)
- Bug #241: Fixed double query to the user table (LAV45)
- Bug #242: Fixed silent crash by omitting AssetsPanel creation when yii/web/AssetManager not being used like in REST apps (tunecino)
- Bug #244: Fixed copying SQL via triple-click in Firefox (arzzen)
- Bug #249: Fixed toolbar not displayed because of misconfigured authManager (samdark)
- Bug #251: User panel was displaying current user info instead of user info at the moment of request (samdark)
- Bug #252, #234, #220, #242: Reworked error handling to be error-resistent and display errors in the panel itself (bashkarev)
- Bug #257: Fixed user panel to properly display object attributes (samdark)
- Enh #188: Added `RequestPanel::$displayVars` that lists allowed variables in request panel (samdark)
- Enh #204: Switch users from the panel (sam002)
- Enh #208: All identity models get converted to arrays when saving User panel data now, not just ActiveRecord models (brandonkelly)
- Enh #208: Identity model packaging for User panels is now done in an `identityData()` method, making it easier for subclasses to customize (brandonkelly)
- Enh #218: Hide the debug toolbar when an HTML page is printed (githubjeka)
- Enh #225: Added classes to use bootstrap styles for filter inputs in Timeline panel (johonunu)
- Enh #256: Catch fetch AJAX requests (leopold537)


2.0.9 February 21, 2017
-----------------------

- Bug #195: Fixed failure when user model has timestamp behavior attached (sam002)
- Bug #199: Do not use user panel in case component isn't properly defined in the application (samdark)
- Bug #200: Fixed error in user panel when RBAC role or permission contains non-string data (samdark)


2.0.8 February 19, 2017
-----------------------

- Bug #82: Fixed debug crashing when there's a closure in log message (samdark)
- Bug #176: Use module's real ID instead of hardcoded "debug" (samdark)
- Enh #34: Added memory graph to timeline panel (bashkarev)
- Enh #174: Added routing panel (bashkarev, samdark)
- Enh #179: Increased request time logging accuracy and precision (samdark)
- Enh #181: Added user panel (pana1990)
- Enh #185: Added meta tag to prevent indexing of debug by search engines in case it's exposed (aminkt, samdark)
- Enh #196: Added language information to config panel (cebe)


2.0.7 November 24, 2016
-----------------------

- Bug #61: Fixed toolbar not to be cached by using renderDynamic (dynasource)
- Bug #93: Fixed `AssetPanel` error when bundle `$js` or `$css` contained `jsOptions` overrides (Razzwan, samdark)
- Bug #99: Avoid serializing php7 errors (zuozp8)
- Bug #111: Fixed `LogTarget` to work properly when tests are ran via Codeception (samdark, nlmedina)
- Bug #120: Fixed toolbar height changing when opened/closed and when using bootstrap (nkovacs)
- Bug #148: Don't animate iframe needlessly when window is resized. (nkovacs)
- Bug #150: Fixed "Cannot read property 'replaceChild' of null" error (BetsuNo)
- Bug #152: Fixed log search to work with non-scalar values (samdark)
- Bug #160: Remove height as it prevents the background from stretching, causing unreadable overlapping texts over background (dynasource)
- Bug #168: Fixed wrong toggle button direction (fps01)
- Enh #8: Added ability to configure default sorting and filtering for Database panel (laszlovl)
- Enh #27: Adjusted sorting defaults, removed row numbers from database, log and profiling panels (samdark)
- Enh #58: Added timeline panel (bashkarev)
- Enh #97: Added AJAX requests handling (bashkarev)
- Enh #105: Enhanced `ConfigPanel` to detect and report memcached extension presence (samdark)
- Enh #115: Make the default panel configurable and set it to `log` (mikehaertl)
- Enh #117: Added ability to customize the logo with `Module::setYiiLogo()` (brandonkelly)
- Enh #143: Added application version display at `ConfigPanel` (klimov-paul)
- Enh #145: The error and warning labels of the log section on the summary bar now link directly to the log page filtered by log level type (rhertogh)
- Enh #162: Added ability to config the trace file and line number (thiagotalma)
- Enh: Mouse wheel click, or Ctrl+Click opens debugger in new tab (silverfire)
- Enh: `yii\debug\Module::defaultVersion()` implemented to pick up 'yiisoft/yii2-debug' extension version (klimov-paul)


2.0.6 March 17, 2016
--------------------

- Bug #41: Debug toolbar was unable to work without asset manager, removed `ToolbarAsset` class (samdark)
- Bug #51: Explain wasn't displayig all data available (lichunqiang)
- Bug #66: Fixed debug panel not working inside applications with response format different from HTML (creocoder, cebe)
- Bug #70: Exception was throwed when `UrlManager::ruleConfig` class was setted with `yii\rest\UrlRule` (lichunqiang)
- Bug: Fixed error when `Yii::$app->db` is not an instance of `yii\db\Connection` (cebe, jafaripur)
- Bug: Fixed exception when no data was recorded for db and profiling panel (cebe, jafaripur)
- Enh #44: Improved display of memory usage to use 3 decimals (dynasource)
- Enh #47: LogTarget storage directory is now created recursively if it does not exist (thiagotalma)
- Enh #63: Enhanced reliablity of request panel in case session is misconfigured (arisk)
- Enh #67: Ability to change permissions for debugger data files and directories (mg-code)
- Enh #83: Debug toolbar now works at the page in async manner (JiLiZART)


2.0.5 August 06, 2015
---------------------

- Bug #33: Fixed `LogTarget::collect()` to call `export()` in a proper way (cornernote)
- Bug #7305: Logging of Exception objects resulted in failure of the logger and no debug data was present (cebe)
- Bug #9112: Fixed initial state of debug toolbar placeholder to prevent "blink" on loading (samdark)
- Bug #9169: Fixed incorrect toolbar image mime causing XML5605 errors in IE console (samdark)
- Enh #16: Added ability to EXPLAIN queries in Database panel for MySQL, SQLite, PostgreSQL and Cubrid (laszlovl, samdark)
- Enh #19: Mark selected log item in dropdown list with bold font and an arrow (idMolotov)
- Enh #25: Make use of full screen width in debug toolbar backend (dynasource, cebe)
- Enh #36: Added check for EXPLAIN support in DbPanel (webdevsega)
- Enh: More compact toolbar (samdark)
- Enh: Display colorful status at index page (samdark)
- Enh: More readable format for date and time at index page (samdark)
- Enh: Toolbar script and styles are now properly registered instead of just echoed (samdark)
- Enh: Toolbar data URL is now HTML-escaped producing valid HTML (samdark)


2.0.4 May 10, 2015
------------------

- Bug #7222: Improved debug toolbar display in rtl pages (mohammadhosain, cebe, samdark)
- Enh #7655: Added ability to filter access by hostname (thiagotalma)
- Enh #7746: Background color of request selector is now choosen based on the current requests status (githubjeka, cebe)


2.0.3 March 01, 2015
--------------------

- Bug #6903: Fixed display issue with phpinfo() table (kalayda, cebe)
- Bug #7222: Debug toolbar wasn't displayed properly in rtl pages (mohammadhosain, johonunu, samdark)
- Enh #6890: Added ability to filter by query type (pana1990)


2.0.2 January 11, 2015
----------------------

- Bug #4820: Fixed reading incomplete debug index data in case of high request concurrency (martingeorg, samdark)
- Chg #6572: Allow panels to stay even if they do not receive any debug data (qiangxue)


2.0.1 December 07, 2014
-----------------------

- Bug #5402: Debugger was not loading when there were closures in asset classes (samdark)
- Bug #5745: Gii and debug modules may cause 404 exception when the route contains dashes (qiangxue)
- Enh #5600: Allow configuring debug panels in `yii\debug\Module::panels` as panel class name strings (qiangxue)
- Enh #6113: Improved configuration and request UI (schmunk42)
- Enh: Made `DefaultController::getManifest()` more robust against corrupt files (cebe)


2.0.0 October 12, 2014
----------------------

- no changes in this release.


2.0.0-rc September 27, 2014
---------------------------

- Bug #1263: Fixed the issue that Gii and Debug modules might be affected by incompatible asset manager configuration (qiangxue)
- Bug #3956: Debug toolbar was affecting flash message removal (samdark)
- Bug #4812: Fixed search filter (samdark)
- Bug #5126: Fixed text body and charset not being set for multipart mail (nkovacs)
- Enh #2299: Date and time in request list is now never wrapped (samdark)
- Enh #3088: The debug module will manage their own URL rules now (qiangxue)
- Enh #3103: debugger panel is now not displayed when printing a page (githubjeka)
- Enh #3108: Added `yii\debug\Module::enableDebugLogs` to disable logging debug logs by default (qiangxue)
- Enh #3810: Added "Latest" button on panels page (thiagotalma)
- Enh #4031: Http status codes were hardcoded in filter (sdkiller)
- Enh #5089: Added asset debugger panel (arturf, qiangxue)

2.0.0-beta April 13, 2014
-------------------------

- Bug #1783: Using VarDumper::dumpAsString() instead var_export(), because var_export() does not handle circular references. (djagya)
- Bug #1504: Debug toolbar isn't loaded successfully in some environments when xdebug is enabled (qiangxue)
- Bug #1747: Fixed problems with displaying toolbar on small screens (cebe)
- Bug #1827: Debugger toolbar is loaded twice if an action is calling `run()` to execute another action (qiangxue)
- Enh #1667: Added mail panel (Ragazzo, 6pblcb)
- Enh #2006: Added total queries count monitoring (o-rey, Ragazzo)

2.0.0-alpha, December 1, 2013
-----------------------------

- Initial release.
