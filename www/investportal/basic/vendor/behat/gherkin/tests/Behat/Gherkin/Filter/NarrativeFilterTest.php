<?php

namespace Tests\Behat\Gherkin\Filter;

use Behat\Gherkin\Filter\NarrativeFilter;
use Behat\Gherkin\Node\FeatureNode;

class NarrativeFilterTest extends FilterTest
{
    public function testIsFeatureMatchFilter()
    {
        $description = <<<NAR
In order to be able to read news in my own language
As a french user
I need to be able to switch website language to french
NAR;
        $feature = new FeatureNode(null, $description, array(), null, array(), null, null, null, 1);

        $filter = new NarrativeFilter('/as (?:a|an) french user/');
        $this->assertFalse($filter->isFeatureMatch($feature));

        $filter = new NarrativeFilter('/as (?:a|an) french user/i');
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new NarrativeFilter('/french .*/');
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new NarrativeFilter('/^french/');
        $this->assertFalse($filter->isFeatureMatch($feature));

        $filter = new NarrativeFilter('/user$/');
        $this->assertFalse($filter->isFeatureMatch($feature));
    }
}
