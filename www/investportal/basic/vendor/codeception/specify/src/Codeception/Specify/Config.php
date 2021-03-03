<?php
namespace Codeception\Specify;

/**
 * Global Specify configuration. Should be set in bootstrap.
 *
 * ```php
 * <?php
 * // disable deep cloning of properties inside specify block
 * \Codeception\Specify\Config::setDeepClone(false);
 * ?>
 * ```
 */
class Config
{
    protected static $ignoredClasses = [
        'Codeception\Actor',
        'Symfony\Component\EventDispatcher\EventDispatcher',
        'Codeception\Scenario',
        'Codeception\Lib\Parser'
    ];

    protected static $ignoredProperties = [

        // PHPUnit
        'backupGlobals',
        'backupGlobalsBlacklist',
        'backupStaticAttributes',
        'backupStaticAttributesBlacklist',
        'runTestInSeparateProcess',
        'preserveGlobalState',

        // Codeception
        'dependencies',
        'dependencyInput',
        'tester',
        'guy',
        'name'
    ];

    protected static $deepClone = true;

    public $is_deep = true;
    public $ignore = array();
    public $ignore_classes = array();
    public $shallow = array();
    public $deep = array();
    public $only = null;

    public function propertyIgnored($property)
    {
        if ($this->only) {
            return !in_array($property, $this->only);
        }
        return in_array($property, $this->ignore);
    }

    public function classIgnored($value)
    {
        if (!is_object($value)) return false;
        return in_array(get_class($value), $this->ignore_classes);
    }

    public function propertyIsShallowCloned($property)
    {
        if ($this->only and !$this->is_deep) {
            return in_array($property, $this->only);
        }
        if (!$this->is_deep and !in_array($property, $this->deep)) {
            return true;
        }
        return in_array($property, $this->shallow);
    }

    public function propertyIsDeeplyCloned($property)
    {
        if ($this->only and $this->is_deep) {
            return in_array($property, $this->only);
        }
        if ($this->is_deep and !in_array($property, $this->shallow)) {
            return true;
        }
        return in_array($property, $this->deep);
    }

    /**
     * Enable or disable using of deep cloning for objects by default.
     * Deep cloning is the default.
     *
     * @param boolean $deepClone
     */
    public static function setDeepClone($deepClone)
    {
        self::$deepClone = $deepClone;
    }

    /***
     * Set classes which are going to be ignored for cloning in specify blocks.
     *
     * @param array $ignoredClasses
     */
    public static function setIgnoredClasses($ignoredClasses)
    {
        self::$ignoredClasses = $ignoredClasses;
    }

    /**
     * Globally set class properties are going to be ignored for cloning in specify blocks.
     *
     * ```php
     * <?php
     * \Codeception\Specify\Config::setIgnoredProperties(['users', 'repository']);
     * ```
     *
     * @param array $ignoredProperties
     */
    public static function setIgnoredProperties($ignoredProperties)
    {
        self::$ignoredProperties = $ignoredProperties;
    }

    /**
     * Add specific classes to cloning ignore list. Instances of those classes won't be cloned for specify blocks.
     *
     * ```php
     * <?php
     * \Codeception\Specify\Config::addIgnoredClasses(['\Acme\Domain\UserRepo', '\Acme\Domain\PostRepo']);
     * ?>
     * ```
     *
     * @param $ignoredClasses
     */
    public static function addIgnoredClasses($ignoredClasses)
    {
        self::$ignoredClasses = array_merge(self::$ignoredClasses, $ignoredClasses);
    }

    private function __construct()
    {
    }

    /**
     * @return Config
     */
    static function create()
    {
        $config = new Config();
        $config->is_deep = self::$deepClone;
        $config->ignore = self::$ignoredProperties;
        $config->ignore_classes = self::$ignoredClasses;
        $config->shallow = array();
        $config->deep = array();
        $config->only = null;
        return $config;
    }
}