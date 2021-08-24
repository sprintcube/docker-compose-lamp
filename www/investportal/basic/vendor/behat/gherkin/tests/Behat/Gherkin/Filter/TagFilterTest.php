<?php

namespace Tests\Behat\Gherkin\Filter;

use Behat\Gherkin\Filter\TagFilter;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioNode;

class TagFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterFeature()
    {
        $feature = new FeatureNode(null, null, array('wip'), null, array(), null, null, null, 1);
        $filter = new TagFilter('@wip');
        $this->assertEquals($feature, $filter->filterFeature($feature));

        $scenarios = array(
            new ScenarioNode(null, array(), array(), null, 2),
            $matchedScenario = new ScenarioNode(null, array('wip'), array(), null, 4)
        );
        $feature = new FeatureNode(null, null, array(), null, $scenarios, null, null, null, 1);
        $filteredFeature = $filter->filterFeature($feature);

        $this->assertSame(array($matchedScenario), $filteredFeature->getScenarios());

        $filter = new TagFilter('~@wip');
        $scenarios = array(
            $matchedScenario = new ScenarioNode(null, array(), array(), null, 2),
            new ScenarioNode(null, array('wip'), array(), null, 4)
        );
        $feature = new FeatureNode(null, null, array(), null, $scenarios, null, null, null, 1);
        $filteredFeature = $filter->filterFeature($feature);

        $this->assertSame(array($matchedScenario), $filteredFeature->getScenarios());
    }

    public function testIsFeatureMatchFilter()
    {
        $feature = new FeatureNode(null, null, array(), null, array(), null, null, null, 1);

        $filter = new TagFilter('@wip');
        $this->assertFalse($filter->isFeatureMatch($feature));

        $feature = new FeatureNode(null, null, array('wip'), null, array(), null, null, null, 1);
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new TagFilter('~@done');
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode(null, null, array('wip', 'done'), null, array(), null, null, null, 1);
        $this->assertFalse($filter->isFeatureMatch($feature));

        $feature = new FeatureNode(null, null, array('tag1', 'tag2', 'tag3'), null, array(), null, null, null, 1);
        $filter = new TagFilter('@tag5,@tag4,@tag6');
        $this->assertFalse($filter->isFeatureMatch($feature));

        $feature = new FeatureNode(null, null, array(
            'tag1',
            'tag2',
            'tag3',
            'tag5'
        ), null, array(), null, null, null, 1);
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new TagFilter('@wip&&@vip');
        $feature = new FeatureNode(null, null, array('wip', 'done'), null, array(), null, null, null, 1);
        $this->assertFalse($filter->isFeatureMatch($feature));

        $feature = new FeatureNode(null, null, array('wip', 'done', 'vip'), null, array(), null, null, null, 1);
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new TagFilter('@wip,@vip&&@user');
        $feature = new FeatureNode(null, null, array('wip'), null, array(), null, null, null, 1);
        $this->assertFalse($filter->isFeatureMatch($feature));

        $feature = new FeatureNode(null, null, array('vip'), null, array(), null, null, null, 1);
        $this->assertFalse($filter->isFeatureMatch($feature));

        $feature = new FeatureNode(null, null, array('wip', 'user'), null, array(), null, null, null, 1);
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode(null, null, array('vip', 'user'), null, array(), null, null, null, 1);
        $this->assertTrue($filter->isFeatureMatch($feature));
    }

    public function testIsScenarioMatchFilter()
    {
        $feature = new FeatureNode(null, null, array('feature-tag'), null, array(), null, null, null, 1);
        $scenario = new ScenarioNode(null, array(), array(), null, 2);

        $filter = new TagFilter('@wip');
        $this->assertFalse($filter->isScenarioMatch($feature, $scenario));

        $filter = new TagFilter('~@done');
        $this->assertTrue($filter->isScenarioMatch($feature, $scenario));

        $scenario = new ScenarioNode(null, array(
            'tag1',
            'tag2',
            'tag3'
        ), array(), null, 2);
        $filter = new TagFilter('@tag5,@tag4,@tag6');
        $this->assertFalse($filter->isScenarioMatch($feature, $scenario));

        $scenario = new ScenarioNode(null, array(
            'tag1',
            'tag2',
            'tag3',
            'tag5'
        ), array(), null, 2);
        $this->assertTrue($filter->isScenarioMatch($feature, $scenario));

        $filter = new TagFilter('@wip&&@vip');
        $scenario = new ScenarioNode(null, array('wip', 'not-done'), array(), null, 2);
        $this->assertFalse($filter->isScenarioMatch($feature, $scenario));

        $scenario = new ScenarioNode(null, array(
            'wip',
            'not-done',
            'vip'
        ), array(), null, 2);
        $this->assertTrue($filter->isScenarioMatch($feature, $scenario));

        $filter = new TagFilter('@wip,@vip&&@user');
        $scenario = new ScenarioNode(null, array(
            'wip'
        ), array(), null, 2);
        $this->assertFalse($filter->isScenarioMatch($feature, $scenario));

        $scenario = new ScenarioNode(null, array('vip'), array(), null, 2);
        $this->assertFalse($filter->isScenarioMatch($feature, $scenario));

        $scenario = new ScenarioNode(null, array('wip', 'user'), array(), null, 2);
        $this->assertTrue($filter->isScenarioMatch($feature, $scenario));

        $filter = new TagFilter('@feature-tag&&@user');
        $scenario = new ScenarioNode(null, array('wip', 'user'), array(), null, 2);
        $this->assertTrue($filter->isScenarioMatch($feature, $scenario));

        $filter = new TagFilter('@feature-tag&&@user');
        $scenario = new ScenarioNode(null, array('wip'), array(), null, 2);
        $this->assertFalse($filter->isScenarioMatch($feature, $scenario));
    }
}
