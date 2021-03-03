<?php

/*
 * This file is part of the Behat Gherkin.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Gherkin\Filter;

use Behat\Gherkin\Node\ExampleTableNode;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\OutlineNode;
use Behat\Gherkin\Node\ScenarioInterface;

/**
 * Filters scenarios by definition line number range.
 *
 * @author Fabian Kiss <headrevision@gmail.com>
 */
class LineRangeFilter implements FilterInterface
{
    protected $filterMinLine;
    protected $filterMaxLine;

    /**
     * Initializes filter.
     *
     * @param string $filterMinLine Minimum line of a scenario to filter on
     * @param string $filterMaxLine Maximum line of a scenario to filter on
     */
    public function __construct($filterMinLine, $filterMaxLine)
    {
        $this->filterMinLine = intval($filterMinLine);
        if ($filterMaxLine == '*') {
            $this->filterMaxLine = PHP_INT_MAX;
        } else {
            $this->filterMaxLine = intval($filterMaxLine);
        }
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
        return $this->filterMinLine <= $feature->getLine()
            && $this->filterMaxLine >= $feature->getLine();
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
        if ($this->filterMinLine <= $scenario->getLine() && $this->filterMaxLine >= $scenario->getLine()) {
            return true;
        }

        if ($scenario instanceof OutlineNode && $scenario->hasExamples()) {
            foreach ($scenario->getExampleTable()->getLines() as $line) {
                if ($this->filterMinLine <= $line && $this->filterMaxLine >= $line) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Filters feature according to the filter.
     *
     * @param FeatureNode $feature
     *
     * @return FeatureNode
     */
    public function filterFeature(FeatureNode $feature)
    {
        $scenarios = array();
        foreach ($feature->getScenarios() as $scenario) {
            if (!$this->isScenarioMatch($scenario)) {
                continue;
            }

            if ($scenario instanceof OutlineNode && $scenario->hasExamples()) {
                $table = $scenario->getExampleTable()->getTable();
                $lines = array_keys($table);

                $filteredTable = array($lines[0] => $table[$lines[0]]);
                unset($table[$lines[0]]);

                foreach ($table as $line => $row) {
                    if ($this->filterMinLine <= $line && $this->filterMaxLine >= $line) {
                        $filteredTable[$line] = $row;
                    }
                }

                $scenario = new OutlineNode(
                    $scenario->getTitle(),
                    $scenario->getTags(),
                    $scenario->getSteps(),
                    new ExampleTableNode($filteredTable, $scenario->getExampleTable()->getKeyword()),
                    $scenario->getKeyword(),
                    $scenario->getLine()
                );
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
