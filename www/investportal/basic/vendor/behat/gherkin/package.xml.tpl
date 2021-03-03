<?xml version="1.0" encoding="UTF-8"?>
<package packagerversion="1.8.0" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
    http://pear.php.net/dtd/tasks-1.0.xsd
    http://pear.php.net/dtd/package-2.0
    http://pear.php.net/dtd/package-2.0.xsd">
    <name>gherkin</name>
    <channel>pear.behat.org</channel>
    <summary>Behat\Gherkin is a BDD DSL for PHP</summary>
    <description>
        Behat\Gherkin is an open source behavior driven development DSL for php 5.3.
    </description>
    <lead>
        <name>Konstantin Kudryashov</name>
        <user>everzet</user>
        <email>ever.zet@gmail.com</email>
        <active>yes</active>
    </lead>
    <date>##CURRENT_DATE##</date>
    <version>
        <release>##GHERKIN_VERSION##</release>
        <api>1.0.0</api>
    </version>
    <stability>
        <release>##STABILITY##</release>
        <api>##STABILITY##</api>
    </stability>
    <license uri="http://www.opensource.org/licenses/mit-license.php">MIT</license>
    <notes>-</notes>
    <contents>
        <dir name="/">

            ##SOURCE_FILES##

            <file role="php" baseinstalldir="gherkin" name="autoload.php" />
            <file role="php" baseinstalldir="gherkin" name="libpath.php" />
            <file role="php" baseinstalldir="gherkin" name="i18n.php" />

            <file role="php" baseinstalldir="gherkin" name="vendor/.composer/autoload.php" />
            <file role="php" baseinstalldir="gherkin" name="vendor/.composer/autoload_namespaces.php" />
            <file role="php" baseinstalldir="gherkin" name="phpdoc.ini.dist" />
            <file role="php" baseinstalldir="gherkin" name="phpunit.xml.dist" />
            <file role="php" baseinstalldir="gherkin" name="CHANGES.md" />
            <file role="php" baseinstalldir="gherkin" name="LICENSE" />
            <file role="php" baseinstalldir="gherkin" name="README.md" />

        </dir>
    </contents>
    <dependencies>
        <required>
            <php>
                <min>5.3.1</min>
            </php>
            <pearinstaller>
                <min>1.4.0</min>
            </pearinstaller>
            <extension>
                <name>pcre</name>
            </extension>
            <extension>
                <name>simplexml</name>
            </extension>
            <extension>
                <name>xml</name>
            </extension>
            <extension>
                <name>mbstring</name>
            </extension>
        </required>
    </dependencies>
    <phprelease />
</package>
