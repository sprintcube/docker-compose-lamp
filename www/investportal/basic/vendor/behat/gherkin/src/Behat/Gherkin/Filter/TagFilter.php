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
use Behat\Gherkin\Node\ScenarioInterface;

/**
 * Filters scenarios by feature/scenario tag.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class TagFilter extends ComplexFilter
{
    protected $filterString;

    /**
     * Initializes filter.
     *
     * @param string $filterString Name filter string
     */
    public function __construct($filterString)
    {
        $this->filterString = trim($filterString);
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
        return $this->isTagsMatchCondition($feature->getTags());
    }

    /**
     * Checks if scenario or outline matches specified filter.
     *
     * @param FeatureNode       $feature  Feature node instance
     * @param ScenarioInterface $scenario Scenario or Outline node instance
     *
     * @return Boolean
     */
    public function isScenarioMatch(FeatureNode $feature, ScenarioInterface $scenario)
    {
        return $this->isTagsMatchCondition(array_merge($feature->getTags(), $scenario->getTags()));
    }

    /**
     * Checks that node matches condition.
     *
     * @param string[] $tags
     *
     * @return Boolean
     */
    protected function isTagsMatchCondition($tags)
    {
        $satisfies = true;

        foreach (explode('&&', $this->filterString) as $andTags) {
            $satisfiesComma = false;

            foreach (explode(',', $andTags) as $tag) {
                $tag = str_replace('@', '', trim($tag));

                if ('~' === $tag[0]) {
                    $tag = mb_substr($tag, 1, mb_strlen($tag, 'utf8') - 1, 'utf8');
                    $satisfiesComma = !in_array($tag, $tags) || $satisfiesComma;
                } else {
                    $satisfiesComma = in_array($tag, $tags) || $satisfiesComma;
                }
            }

            $satisfies = (false !== $satisfiesComma && $satisfies && $satisfiesComma) || false;
        }

        return $satisfies;
    }
}
