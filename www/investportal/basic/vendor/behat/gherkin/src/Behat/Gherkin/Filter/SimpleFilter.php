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
 * Abstract filter class.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
abstract class SimpleFilter implements FilterInterface
{
    /**
     * Filters feature according to the filter.
     *
     * @param FeatureNode $feature
     *
     * @return FeatureNode
     */
    public function filterFeature(FeatureNode $feature)
    {
        if ($this->isFeatureMatch($feature)) {
            return $feature;
        }

        $scenarios = array();
        foreach ($feature->getScenarios() as $scenario) {
            if (!$this->isScenarioMatch($scenario)) {
                continue;
            }

            $scenarios[] = $scenario;
        }

        return new FeatureNode(
            $feature->getTitle(),
            $feature->getDescription(),
            $feature->getTags(),
            $feature->getBackground(),
            $scenarios,
            $feature->getKeyword(),
            $feature->getLanguage(),
            $feature->getFile(),
            $feature->getLine()
        );
    }
}
