<?php

namespace Tests\Behat\Gherkin\Filter;

use Behat\Gherkin\Filter\LineFilter;
use Behat\Gherkin\Node\ExampleTableNode;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\OutlineNode;
use Behat\Gherkin\Node\ScenarioNode;

class LineFilterTest extends FilterTest
{
    public function testIsFeatureMatchFilter()
    {
        $feature = new FeatureNode(null, null, array(), null, array(), null, null, null, 1);

        $filter = new LineFilter(1);
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new LineFilter(2);
        $this->assertFalse($filter->isFeatureMatch($feature));

        $filter = new LineFilter(3);
        $this->assertFalse($filter->isFeatureMatch($feature));
    }

    public function testIsScenarioMatchFilter()
    {
        $scenario = new ScenarioNode(null, array(), array(), null, 2);

        $filter = new LineFilter(2);
        $this->assertTrue($filter->isScenarioMatch($scenario));

        $filter = new LineFilter(1);
        $this->assertFalse($filter->isScenarioMatch($scenario));

        $filter = new LineFilter(5);
        $this->assertFalse($filter->isScenarioMatch($scenario));

        $outline = new OutlineNode(null, array(), array(), new ExampleTableNode(array(), null), null, 20);

        $filter = new LineFilter(5);
        $this->assertFalse($filter->isScenarioMatch($outline));

        $filter = new LineFilter(20);
        $this->assertTrue($filter->isScenarioMatch($outline));
    }

    public function testFilterFeatureScenario()
    {
        $filter = new LineFilter(2);
        $feature = $filter->filterFeature($this->getParsedFeature());
        $this->assertCount(1, $scenarios = $feature->getScenarios());
        $this->assertSame('Scenario#1', $scenarios[0]->getTitle());

        $filter = new LineFilter(7);
        $feature = $filter->filterFeature($this->getParsedFeature());
        $this->assertCount(1, $scenarios = $feature->getScenarios());
        $this->assertSame('Scenario#2', $scenarios[0]->getTitle());

        $filter = new LineFilter(5);
        $feature = $filter->filterFeature($this->getParsedFeature());
        $this->assertCount(0, $scenarios = $feature->getScenarios());
    }

    public function testFilterFeatureOutline()
    {
        $filter = new LineFilter(13);
        $feature = $filter->filterFeature($this->getParsedFeature());
        $this->assertCount(1, $scenarios = $feature->getScenarios());
        $this->assertSame('Scenario#3', $scenarios[0]->getTitle());
        $this->assertCount(4, $scenarios[0]->getExampleTable()->getRows());

        $filter = new LineFilter(19);
        $feature = $filter->filterFeature($this->getParsedFeature());
        $this->assertCount(1, $scenarios = $feature->getScenarios());
        $this->assertSame('Scenario#3', $scenarios[0]->getTitle());
        $this->assertCount(2, $scenarios[0]->getExampleTable()->getRows());
        $this->assertSame(array(
            array('action', 'outcome'),
            array('act#1', 'out#1'),
        ), $scenarios[0]->getExampleTable()->getRows());

        $filter = new LineFilter(21);
        $feature = $filter->filterFeature($this->getParsedFeature());
        $this->assertCount(1, $scenarios = $feature->getScenarios());
        $this->assertSame('Scenario#3', $scenarios[0]->getTitle());
        $this->assertCount(2, $scenarios[0]->getExampleTable()->getRows());
        $this->assertSame(array(
            array('action', 'outcome'),
            array('act#3', 'out#3'),
        ), $scenarios[0]->getExampleTable()->getRows());

        $filter = new LineFilter(18);
        $feature = $filter->filterFeature($this->getParsedFeature());
        $this->assertCount(1, $scenarios = $feature->getScenarios());
        $this->assertSame('Scenario#3', $scenarios[0]->getTitle());
        $this->assertCount(1, $scenarios[0]->getExampleTable()->getRows());
        $this->assertSame(array(
            array('action', 'outcome'),
        ), $scenarios[0]->getExampleTable()->getRows());
    }
}
