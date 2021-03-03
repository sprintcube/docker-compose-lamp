<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\composer;

use Composer\Composer;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Package\Version\VersionParser;
use Composer\Plugin\PluginInterface;
use Composer\Script;
use Composer\Script\ScriptEvents;

/**
 * Plugin is the composer plugin that registers the Yii composer installer.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var Installer
     */
    private $_installer;
    /**
     * @var array noted package updates.
     */
    private $_packageUpdates = [];
    /**
     * @var string path to the vendor directory.
     */
    private $_vendorDir;


    /**
     * @inheritdoc
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->_installer = new Installer($io, $composer);
        $composer->getInstallationManager()->addInstaller($this->_installer);
        $this->_vendorDir = rtrim($composer->getConfig()->get('vendor-dir'), '/');
        $file = $this->_vendorDir . '/yiisoft/extensions.php';
        if (!is_file($file)) {
            @mkdir(dirname($file), 0777, true);
            file_put_contents($file, "<?php\n\nreturn [];\n");
        }
    }

    /**
     * @inheritdoc
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
        $composer->getInstallationManager()->removeInstaller($this->_installer);
    }

    /**
     * @inheritdoc
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    /**
     * @inheritdoc
     * @return array The event names to listen to.
     */
    public static function getSubscribedEvents()
    {
        return [
            PackageEvents::POST_PACKAGE_UPDATE => 'checkPackageUpdates',
            ScriptEvents::POST_UPDATE_CMD => 'showUpgradeNotes',
        ];
    }


    /**
     * Listen to POST_PACKAGE_UPDATE event and take note of the package updates.
     * @param PackageEvent $event
     */
    public function checkPackageUpdates(PackageEvent $event)
    {
        $operation = $event->getOperation();
        if ($operation instanceof UpdateOperation) {
            $this->_packageUpdates[$operation->getInitialPackage()->getName()] = [
                'from' => $operation->getInitialPackage()->getVersion(),
                'fromPretty' => $operation->getInitialPackage()->getPrettyVersion(),
                'to' => $operation->getTargetPackage()->getVersion(),
                'toPretty' => $operation->getTargetPackage()->getPrettyVersion(),
                'direction' => $this->_isUpgrade($event, $operation) ? 'up' : 'down',
            ];
        }
    }

    /**
     * @param PackageEvent $event
     * @param UpdateOperation $operation
     * @return bool
     */
    private function _isUpgrade(PackageEvent $event, UpdateOperation $operation)
    {
        // Composer 1.7.0+
        if (method_exists('Composer\Package\Version\VersionParser', 'isUpgrade')) {
            return VersionParser::isUpgrade(
                $operation->getInitialPackage()->getVersion(),
                $operation->getTargetPackage()->getVersion()
            );
        }

        return $event->getPolicy()->versionCompare(
            $operation->getInitialPackage(),
            $operation->getTargetPackage(),
            '<'
        );
    }

    /**
     * Listen to POST_UPDATE_CMD event to display information about upgrade notes if appropriate.
     * @param Script\Event $event
     */
    public function showUpgradeNotes(Script\Event $event)
    {
        $packageName = 'yiisoft/yii2';
        if (!isset($this->_packageUpdates[$packageName])) {
            return;
        }

        $package = $this->_packageUpdates['yiisoft/yii2'];

        // do not show a notice on up/downgrades between dev versions
        // avoid messages like from version dev-master to dev-master
        if ($package['fromPretty'] == $package['toPretty']) {
            return;
        }

        $io = $event->getIO();

        // print the relevant upgrade notes for the upgrade
        // - only on upgrade, not on downgrade
        // - only if the "from" version is non-dev, otherwise we have no idea which notes to show
        if ($package['direction'] === 'up' && $this->isNumericVersion($package['fromPretty'])) {

            $notes = $this->findUpgradeNotes($packageName, $package['fromPretty']);
            if ($notes !== false && empty($notes)) {
                // no relevent upgrade notes, do not show anything.
                return;
            }

            $this->printUpgradeIntro($io, $package);

            if ($notes) {
                // safety check: do not display notes if they are too many
                if (count($notes) > 250) {
                    $io->write("\n  <fg=yellow;options=bold>The relevant notes for your upgrade are too long to be displayed here.</>");
                } else {
                    $io->write("\n  " . trim(implode("\n  ", $notes)));
                }
            }

            $io->write("\n  You can find the upgrade notes for all versions online at:");
        } else {
            $this->printUpgradeIntro($io, $package);
            $io->write("\n  You can find the upgrade notes online at:");
        }
        $this->printUpgradeLink($io, $package);
    }

    /**
     * Print link to upgrade notes
     * @param IOInterface $io
     * @param array $package
     */
    private function printUpgradeLink($io, $package)
    {
        $maxVersion = $package['direction'] === 'up' ? $package['toPretty'] : $package['fromPretty'];
        // make sure to always show a valid link, even if $maxVersion is something like dev-master
        if (!$this->isNumericVersion($maxVersion)) {
            $maxVersion = 'master';
        }
        $io->write("  https://github.com/yiisoft/yii2/blob/$maxVersion/framework/UPGRADE.md\n");
    }

    /**
     * Print upgrade intro
     * @param IOInterface $io
     * @param array $package
     */
    private function printUpgradeIntro($io, $package)
    {
        $io->write("\n  <fg=yellow;options=bold>Seems you have "
            . ($package['direction'] === 'up' ? 'upgraded' : 'downgraded')
            . ' Yii Framework from version '
            . $package['fromPretty'] . ' to ' . $package['toPretty'] . '.</>'
        );
        $io->write("\n  <options=bold>Please check the upgrade notes for possible incompatible changes");
        $io->write('  and adjust your application code accordingly.</>');
    }

    /**
     * Read upgrade notes from a files and returns an array of lines
     * @param string $packageName
     * @param string $fromVersion until which version to read the notes
     * @return array|false
     */
    private function findUpgradeNotes($packageName, $fromVersion)
    {
        if (preg_match('/^([0-9]\.[0-9]+\.?[0-9]*)/', $fromVersion, $m)) {
            $fromVersionMajor = $m[1];
        } else {
            $fromVersionMajor = $fromVersion;
        }

        $upgradeFile = $this->_vendorDir . '/' . $packageName . '/UPGRADE.md';
        if (!is_file($upgradeFile) || !is_readable($upgradeFile)) {
            return false;
        }
        $lines = preg_split('~\R~', file_get_contents($upgradeFile));
        $relevantLines = [];
        $consuming = false;
        // whether an exact match on $fromVersion has been encountered
        $foundExactMatch = false;
        foreach($lines as $line) {
            if (preg_match('/^Upgrade from Yii ([0-9]\.[0-9]+\.?[0-9\.]*)/i', $line, $matches)) {
                if ($matches[1] === $fromVersion) {
                    $foundExactMatch = true;
                }
                if (version_compare($matches[1], $fromVersion, '<') && ($foundExactMatch || version_compare($matches[1], $fromVersionMajor, '<'))) {
                    break;
                }
                $consuming = true;
            }
            if ($consuming) {
                $relevantLines[] = $line;
            }
        }
        return $relevantLines;
    }

    /**
     * Check whether a version is numeric, e.g. 2.0.10.
     * @param string $version
     * @return bool
     */
    private function isNumericVersion($version)
    {
        return (bool) preg_match('~^([0-9]\.[0-9]+\.?[0-9\.]*)~', $version);
    }
}
