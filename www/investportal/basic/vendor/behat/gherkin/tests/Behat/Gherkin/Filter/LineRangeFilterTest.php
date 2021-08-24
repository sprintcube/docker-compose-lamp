<?php

namespace Tests\Behat\Gherkin\Filter;

use Behat\Gherkin\Filter\LineRangeFilter;
use Behat\Gherkin\Node\ExampleTableNode;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\OutlineNode;
use Behat\Gherkin\Node\ScenarioNode;

class LineRangeFilterTest extends FilterTest
{
    public function featureLineRangeProvider()
    {
        return array(
            array('1', '1', true),
            array('1', '2', true),
            array('1', '*', true),
            array('2', '2', false),
            array('2', '*', false)
        );
    }

    /**
     * @dataProvider featureLineRangeProvider
     */
    public function testIsFeatureMatchFilter($filterMinLine, $filterMaxLine, $expected)
    {
        $feature = new FeatureNode(null, null, array(), null, array(), null, null, null, 1);

        $filter = new LineRangeFilter($filterMinLine, $filterMaxLine);
        $this->assertSame($expected, $filter->isFeatureMatch($feature));
    }

    public function scenarioLineRangeProvider()
    {
        return array(
            array('1', '2', 1),
            array('1', '*', 2),
            array('2', '2', 1),
            array('2', '*', 2),
            array('3', '3', 1),
            array('3', '*', 1),
            array('1', '1', 0),
            array('4', '4', 0),
            array('4', '*', 0)
        );
    }

    /**
     * @dataProvider scenarioLineRangeProvider
     */
    public function testIsScenarioMatchFilter($filterMinLine, $filterMaxLine, $expectedNumberOfMatches)
    {
        $scenario = new ScenarioNode(null, array(), array(), null, 2);
        $outline = new OutlineNode(null, array(), array(), new ExampleTableNode(array(), null), null, 3);

        $filter = new LineRangeFilter($filterMinLine, $filterMaxLine);
        $this->assertEquals(
            $expectedNumberOfMatches,
            intval($filter->isScenarioMatch($scenario)) + intval($filter->isScenarioMatch($outline))
        );
    }

    public function testFilterFeatureScenario()
    {
        $filter = new LineRangeFilter(1, 3);
        $feature = $filter->filterFeature($this->getParsedFeature());
        $this->assertCount(1, $scenarios = $feature->getScenarios());
        $this->assertSame('Scenario#1', $scenarios[0]->getTitle());

        $filter = new LineRangeFilter(5, 9);
        $feature = $filter->filterFeature($this->getParsedFeature());
        $this->assertCount(1, $scenarios = $feature->getScenarios());
        $this->assertSame('Scenario#2', $scenarios[0]->getTitle());

        $filter = new LineRangeFilter(5, 6);
        $feature = $filter->filterFeature($this->getParsedFeature());
        $this->assertCount(0, $scenarios = $feature->getScenarios());
    }

    public function testFilterFeatureOutline()
    {
        $filter = new LineRangeFilter(12, 14);
        $feature = $filter->filterFeature($this->getParsedFeature());
        $this->assertCount(1, $scenarios = $feature->getScenarios());
        $this->assertSame('Scenario#3', $scenarios[0]->getTitle());
        $this->assertCount(1, $scenarios[0]->getExampleTable()->getRows());

        $filter = new LineRangeFilter(15, 20);
        $feature = $filter->filterFeature($this->getParsedFeature());
        $this->assertCount(1, $scenarios = $feature->getScenarios());
        $this->assertSame('Scenario#3', $scenarios[0]->getTitle());
        $this->assertCount(3, $scenarios[0]->getExampleTable()->getRows());
        $this->assertSame(array(
            array('action', 'outcome'),
            array('act#1', 'out#1'),
            array('act#2', 'out#2'),
        ), $scenarios[0]->getExampleTable()->getRows());
    }
}
