<?php

/*
 * This file is part of the Gherkin.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Gherkin\Filter;

use Behat\Gherkin\Node\ScenarioInterface;
use Behat\Gherkin\Node\FeatureNode;

/**
 * Filters features by their narrative using regular expression.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class NarrativeFilter extends SimpleFilter
{
    /**
     * @var string
     */
    private $regex;

    /**
     * Initializes filter.
     *
     * @param string $regex
     */
    public function __construct($regex)
    {
        $this->regex = $regex;
    }

    /**
     * Checks if Feature matches specified filter.
     *
     * @param FeatureNode $feature Feature instance
     *
     * @return Boolean
     */
    public function isFeatureMatch(FeatureNode $feature)
    {
        return 1 === preg_match($this->regex, $feature->getDescription());
    }

    /**
     * Checks if scenario or outline matches specified filter.
     *
     * @param ScenarioInterface $scenario Scenario or Outline node instance
     *
     * @return Boolean
     */
    public function isScenarioMatch(ScenarioInterface $scenario)
    {
        return false;
    }
}
