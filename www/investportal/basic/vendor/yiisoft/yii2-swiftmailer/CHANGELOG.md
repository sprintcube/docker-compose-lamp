Yii Framework 2 swiftmailer extension Change Log
================================================

2.1.3 December 30, 2021
-----------------------

- Enh #91: Clone signers when cloning `Message`, emit warning when `opendkim` is used (WinterSilence)


2.1.2 September 24, 2018
------------------------

- Bug #67: If transport is gone, try to restart transport (mikk150)
- Bug #67: Test if transport is gone each time someone tries to send email, not if connection is initially made (mikk150)
- Enh #63: Added ability to specify the disposition of an attachment by supplying a `setDisposition` value when embedding content in a message (CorWatts)


2.1.1 April 25, 2018
--------------------

- Bug #61: Fixed `yii\swiftmailer\Mailer::setTransport` has no effect after sending of first message (dmitry-kulikov)


2.1.0 August 04, 2017
---------------------

- Enh #31: Added support for SwiftMailer 6.0.x (klimov-paul)


2.0.7 May 01, 2017
------------------

- Bug #46: Fixed `yii\swiftmailer\Message` does not clones `$swiftMessage` during its own cloning (evpav, klimov-paul)
- Enh #37: `yii\swiftmailer\Logger` now chooses logging level depending on incoming entry format (klimov-paul)
- Enh #40: Added `yii\swiftmailer\Message::setHeaders()` allowing to setup custom headers in batch (klimov-paul)


2.0.6 September 09, 2016
------------------------

- Enh #6: Added ability to specify custom mail header at `yii\swiftmailer\Message` (klimov-paul)
- Enh #23: Added `yii\swiftmailer\Message::setReturnPath()` shortcut method (klimov-paul)
- Enh #27: Added ability to specify message signature (klimov-paul)
- Enh #32: Added `yii\swiftmailer\Message::setReadReceiptTo()` shortcut method (klimov-paul)
- Enh: Added `yii\swiftmailer\Message::setPriority()` shortcut method (klimov-paul)


2.0.5 March 17, 2016
--------------------

- Bug #9: Fixed `Mailer` does not check if property is public, while configuring 'Swift' object (brandonkelly, klimov-paul)


2.0.4 May 10, 2015
------------------

- Enh #4: Added ability to pass SwiftMailer log entries to `Yii::info()` (klimov-paul)


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

- no changes in this release.


2.0.0-beta April 13, 2014
-------------------------

- Bug #1817: Message charset not applied for alternative bodies (klimov-paul)

2.0.0-alpha, December 1, 2013
-----------------------------

- Initial release.
