<?php

/*
* This file is part of the Behat Gherkin.
* (c) Konstantin Kudryashov <ever.zet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Behat\Gherkin\Cache;

use Behat\Gherkin\Node\FeatureNode;

/**
 * Memory cache.
 * Caches feature into a memory.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class MemoryCache implements CacheInterface
{
    private $features = array();
    private $timestamps = array();

    /**
     * Checks that cache for feature exists and is fresh.
     *
     * @param string  $path      Feature path
     * @param integer $timestamp The last time feature was updated
     *
     * @return Boolean
     */
    public function isFresh($path, $timestamp)
    {
        if (!isset($this->features[$path])) {
            return false;
        }

        return $this->timestamps[$path] > $timestamp;
    }

    /**
     * Reads feature cache from path.
     *
     * @param string $path Feature path
     *
     * @return FeatureNode
     */
    public function read($path)
    {
        return $this->features[$path];
    }

    /**
     * Caches feature node.
     *
     * @param string      $path    Feature path
     * @param FeatureNode $feature Feature instance
     */
    public function write($path, FeatureNode $feature)
    {
        $this->features[$path]   = $feature;
        $this->timestamps[$path] = time();
    }
}
