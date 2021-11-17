<?php

/*
* This file is part of the Behat Gherkin.
* (c) Konstantin Kudryashov <ever.zet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Behat\Gherkin\Cache;

use Behat\Gherkin\Exception\CacheException;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Gherkin;

/**
 * File cache.
 * Caches feature into a file.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class FileCache implements CacheInterface
{
    private $path;

    /**
     * Initializes file cache.
     *
     * @param string $path Path to the folder where to store caches.
     *
     * @throws CacheException
     */
    public function __construct($path)
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'v'.Gherkin::VERSION;

        if (!is_dir($this->path)) {
            @mkdir($this->path, 0777, true);
        }

        if (!is_writeable($this->path)) {
            throw new CacheException(sprintf('Cache path "%s" is not writeable. Check your filesystem permissions or disable Gherkin file cache.', $this->path));
        }
    }

    /**
     * Checks that cache for feature exists and is fresh.
     *
     * @param string  $path      Feature path
     * @param integer $timestamp The last time feature was updated
     *
     * @return bool
     */
    public function isFresh($path, $timestamp)
    {
        $cachePath = $this->getCachePathFor($path);

        if (!file_exists($cachePath)) {
            return false;
        }

        return filemtime($cachePath) > $timestamp;
    }

    /**
     * Reads feature cache from path.
     *
     * @param string $path Feature path
     *
     * @return FeatureNode
     *
     * @throws CacheException
     */
    public function read($path)
    {
        $cachePath = $this->getCachePathFor($path);
        $feature = unserialize(file_get_contents($cachePath));

        if (!$feature instanceof FeatureNode) {
            throw new CacheException(sprintf('Can not load cache for a feature "%s" from "%s".', $path, $cachePath ));
        }

        return $feature;
    }

    /**
     * Caches feature node.
     *
     * @param string      $path    Feature path
     * @param FeatureNode $feature Feature instance
     */
    public function write($path, FeatureNode $feature)
    {
        file_put_contents($this->getCachePathFor($path), serialize($feature));
    }

    /**
     * Returns feature cache file path from features path.
     *
     * @param string $path Feature path
     *
     * @return string
     */
    protected function getCachePathFor($path)
    {
        return $this->path.'/'.md5($path).'.feature.cache';
    }
}
