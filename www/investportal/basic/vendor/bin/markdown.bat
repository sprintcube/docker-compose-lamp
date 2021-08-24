@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../cebe/markdown/bin/markdown
php "%BIN_TARGET%" %*
