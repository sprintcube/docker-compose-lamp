<?php

namespace Tests\Behat\Gherkin\Filter;

use Behat\Gherkin\Filter\NameFilter;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioNode;

class NameFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterFeature()
    {
        $feature = new FeatureNode('feature1', null, array(), null, array(), null, null, null, 1);
        $filter = new NameFilter('feature1');
        $this->assertSame($feature, $filter->filterFeature($feature));

        $scenarios = array(
            new ScenarioNode('scenario1', array(), array(), null, 2),
            $matchedScenario = new ScenarioNode('scenario2', array(), array(), null, 4)
        );
        $feature = new FeatureNode('feature1', null, array(), null, $scenarios, null, null, null, 1);
        $filter = new NameFilter('scenario2');
        $filteredFeature = $filter->filterFeature($feature);

        $this->assertSame(array($matchedScenario), $filteredFeature->getScenarios());
    }

    public function testIsFeatureMatchFilter()
    {
        $feature = new FeatureNode('random feature title', null, array(), null, array(), null, null, null, 1);

        $filter = new NameFilter('feature1');
        $this->assertFalse($filter->isFeatureMatch($feature));

        $feature = new FeatureNode('feature1', null, array(), null, array(), null, null, null, 1);
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode('feature1 title', null, array(), null, array(), null, null, null, 1);
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode('some feature1 title', null, array(), null, array(), null, null, null, 1);
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode('some feature title', null, array(), null, array(), null, null, null, 1);
        $this->assertFalse($filter->isFeatureMatch($feature));

        $filter = new NameFilter('/fea.ure/');
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode('some feaSure title', null, array(), null, array(), null, null, null, 1);
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode('some feture title', null, array(), null, array(), null, null, null, 1);
        $this->assertFalse($filter->isFeatureMatch($feature));
    }

    public function testIsScenarioMatchFilter()
    {
        $filter = new NameFilter('scenario1');

        $scenario = new ScenarioNode('UNKNOWN', array(), array(), null, 2);
        $this->assertFalse($filter->isScenarioMatch($scenario));

        $scenario = new ScenarioNode('scenario1', array(), array(), null, 2);
        $this->assertTrue($filter->isScenarioMatch($scenario));

        $scenario = new ScenarioNode('scenario1 title', array(), array(), null, 2);
        $this->assertTrue($filter->isScenarioMatch($scenario));

        $scenario = new ScenarioNode('some scenario title', array(), array(), null, 2);
        $this->assertFalse($filter->isScenarioMatch($scenario));

        $filter = new NameFilter('/sce.ario/');
        $this->assertTrue($filter->isScenarioMatch($scenario));

        $filter = new NameFilter('/scen.rio/');
        $this->assertTrue($filter->isScenarioMatch($scenario));
    }
}
