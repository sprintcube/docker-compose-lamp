<?php

/*
 * This file is part of the Behat Gherkin.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Gherkin\Filter;

use Behat\Gherkin\Node\FeatureNode;

/**
 * Feature filter interface.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
interface FeatureFilterInterface
{
    /**
     * Checks if Feature matches specified filter.
     *
     * @param FeatureNode $feature Feature instance
     *
     * @return Boolean
     */
    public function isFeatureMatch(FeatureNode $feature);

    /**
     * Filters feature according to the filter and returns new one.
     *
     * @param FeatureNode $feature
     *
     * @return FeatureNode
     */
    public function filterFeature(FeatureNode $feature);
}
