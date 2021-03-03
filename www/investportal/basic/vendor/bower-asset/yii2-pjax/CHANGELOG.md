yii-pjax Change Log
===================

v2.0.7.1 under development
------------------------
- Bug #61: Restore the last semicolon for Yii asset/compress (tanakahisateru)

2.0.7 Sep 27, 2017
-----------------
- Bug #46: Allow "data-pjax" attribute with no value in `handleClick` function (arogachev)
- Bug #52: Fixed form submit handling to prevent submit when event result is `false` (derekisbusy)
- Bug #52: Fixed PJAX initialization to prevent multiple PJAX handlers attachment on the same element (derekisbusy, silverfire)
- Bug #54: Fixed PJAX initialization not to modify history, when it is disabled (silverfire)
- Enh #57: jQuery 3.x compatibility (a761208, silverfire)
- Enh #51: Added `scrollOffset` option, updated `scrollTo` to support callback (derekisbusy)
- Bug: Fixed stylesheets loading to prevent styles override (voskobovich, silverfire)
- Merged updates from upstream. The update contains backward-incompatible changes, see [changes list](https://github.com/yiisoft/jquery-pjax/issues/55#issuecomment-310109608) to adjust your application accordingly. (silverfire)

2.0.6 Mar 4, 2016
-----------------
- Bug #15: Fixed duplication of `_pjax` GET variable (Alex-Code)
- Bug #21: Fixed non-persistence of `cache` option after backward navigation (nkovacs)
- Bug #23: Fixed loading of scripts in pjax containers (nkovacs, silverfire)
- Bug #37: Added `X-Ie-Redirect-Compatibility` header for IE. Fixes error on 302 redirect without `Location` header (silverfire)
- Enh #25: Blur the focused element if it's inside Pjax container (GeorgeGardiner)
- Enh #27: Added `pushRedirect`, `replaceRedirectOptions` options (beowulfenator)
- Chg: JavaScripts load through PJAX will be processed by `jQuery.ajaxPrefiler` when it's configured (silverfire)
- New: Added `skipOuterContainers` option (silverfire)

2.0.3 Mar 7, 2015
-----------------
- Chg: Merged changes from upstream (samdark)

2.0.2 Dec 4, 2014
-----------------
- Chg #12: Merged changes from upstream (samdark)

2.0.1 Oct 10, 2014
------------------
- Bug #9: Fixed missing history option in default settings (tonydspaniard)
- New #11: add new option "cache" (macklay)


2.0.0 Mar 20, 2014
------------------
- Bug: Fixed avoid duplicates of _pjax parameter (tof06)
- Bug: Fixed Pjax/GridView and back button (klevron, tof06, tonydspaniard)

