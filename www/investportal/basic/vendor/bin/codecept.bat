@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../codeception/codeception/codecept
php "%BIN_TARGET%" %*
