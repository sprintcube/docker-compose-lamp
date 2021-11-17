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
 * Parser cache interface.
 *
 * @author     Konstantin Kudryashov <ever.zet@gmail.com>
 */
interface CacheInterface
{
    /**
     * Checks that cache for feature exists and is fresh.
     *
     * @param string  $path      Feature path
     * @param integer $timestamp The last time feature was updated
     *
     * @return bool
     */
    public function isFresh($path, $timestamp);

    /**
     * Reads feature cache from path.
     *
     * @param string $path Feature path
     *
     * @return FeatureNode
     */
    public function read($path);

    /**
     * Caches feature node.
     *
     * @param string      $path    Feature path
     * @param FeatureNode $feature Feature instance
     */
    public function write($path, FeatureNode $feature);
}
