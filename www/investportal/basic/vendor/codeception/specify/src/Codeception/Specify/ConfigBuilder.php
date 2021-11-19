<?php
namespace Codeception\Specify;

/**
 * Configure Specify usage.
 *
 * Specify copies properties of object and restores them for each specify block.
 * Objects can be cloned deeply or using standard `clone` operator.
 * Specify can be configured to prevent specific properties in specify blocks, to choose default cloning method,
 * or cloning method for specific properties.
 *
 * ```php
 * <?php
 * $this->specifyConfig()
 *  ->ignore('user') // do not clone
 * ?>
 * ```
 */
class ConfigBuilder
{
    /**
     * @var Config
     */
    protected $config;

    public function __construct(Config $config = null)
    {
        $this->config = $config;
        if (!$config) {
            $this->config = Config::create();
        }
    }

    /**
     * Ignore cloning specific object properties in specify blocks.
     *
     * ```php
     * <?php
     * $this->user = new User;
     * $this->specifyConfig()->ignore('user');
     * $this->specify('change user name', function() {
     *      $this->user->name = 'davert';
     * });
     * $this->user->name == 'davert'; // name changed
     * ?>
     * ```
     *
     * @param array $properties
     * @return $this
     */
    public function ignore($properties = array())
    {
        if (!is_array($properties)) {
            $properties = func_get_args();
        }
        $this->config->ignore = array_merge($this->config->ignore, $properties);
        return $this;
    }

    /**
     * Adds specific class to ignore list, if property is an instance of class it will not be cloned for specify block.
     *
     * @param array $classes
     * @return $this
     */
    public function ignoreClasses($classes = array())
    {
        $this->config->ignore_classes = array_merge($this->config->ignore_classes, $classes);
        return $this;
    }

    /**
     * Turn on/off deep cloning mode.
     * Deep cloning mode can also be specified for specific properties.
     *
     * ```php
     * <?php
     * $this->user = new User;
     * $this->post = new Post;
     * $this->tag = new Tag;
     *
     * // turn on deep cloning by default
     * $this->specifyConfig()->deepClone();
     *
     * // turn off deep cloning by default
     * $this->specifyConfig()->deepClone(false);
     *
     * // deep clone only user and tag property
     * $this->specifyConfig()->deepClone('user', 'tag');
     *
     * // alternatively
     * $this->specifyConfig()->deepClone(['user', 'tag']);
     * ?>
     * ```
     *
     * @param bool $properties
     * @return $this
     */
    public function deepClone($properties = true)
    {
        if (is_bool($properties)) {
            $this->config->is_deep = $properties;
            return $this;
        }
        if (!is_array($properties)) {
            $properties = func_get_args();
        }
        $this->config->deep = $properties;
        return $this;
    }

    /**
     * Disable deep cloning mode, use shallow cloning by default, which is faster.
     * Deep cloning mode can also be disabled for specific properties.
     *
     * ```php
     * <?php
     * $this->user = new User;
     * $this->post = new Post;
     * $this->tag = new Tag;
     *
     * // turn off deep cloning by default
     * $this->specifyConfig()->shallowClone();
     *
     * // turn on deep cloning by default
     * $this->specifyConfig()->shallowClone(false);
     *
     * // shallow clone only user and tag property
     * $this->specifyConfig()->shallowClone('user', 'tag');
     *
     * // alternatively
     * $this->specifyConfig()->shallowClone(['user', 'tag']);
     * ?>
     * ```
     *
     * @param bool $properties
     * @return $this
     */
    public function shallowClone($properties = true)
    {
        if (is_bool($properties)) {
            $this->config->is_deep = !$properties;
            return $this;
        }
        if (!is_array($properties)) {
            $properties = func_get_args();
        }
        $this->config->shallow = array_merge($this->config->shallow, $properties);
        return $this;
    }

    /**
     * Clone only specific properties
     *
     * ```php
     * <?php
     * $this->specifyConfig()->cloneOnly('user', 'post');
     * ?>
     * ```
     *
     * @param $properties
     * @return $this
     */
    public function cloneOnly($properties)
    {
        if (!is_array($properties)) {
            $properties = func_get_args();
        }
        $this->config->only = $properties;
        return $this;
    }
} 