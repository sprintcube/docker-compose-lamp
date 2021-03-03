<?php

namespace Tests\Behat\Gherkin\Filter;

use Behat\Gherkin\Filter\RoleFilter;
use Behat\Gherkin\Node\FeatureNode;

class RoleFilterTest extends FilterTest
{
    public function testIsFeatureMatchFilter()
    {
        $description = <<<NAR
In order to be able to read news in my own language
As a french user
I need to be able to switch website language to french
NAR;
        $feature = new FeatureNode(null, $description, array(), null, array(), null, null, null, 1);

        $filter = new RoleFilter('french user');
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new RoleFilter('french *');
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new RoleFilter('french');
        $this->assertFalse($filter->isFeatureMatch($feature));

        $filter = new RoleFilter('user');
        $this->assertFalse($filter->isFeatureMatch($feature));

        $filter = new RoleFilter('*user');
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new RoleFilter('French User');
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode(null, null, array(), null, array(), null, null, null, 1);
        $filter = new RoleFilter('French User');
        $this->assertFalse($filter->isFeatureMatch($feature));
    }

    public function testFeatureRolePrefixedWithAn()
    {
        $description = <<<NAR
In order to be able to read news in my own language
As an american user
I need to be able to switch website language to french
NAR;
        $feature = new FeatureNode(null, $description, array(), null, array(), null, null, null, 1);

        $filter = new RoleFilter('american user');
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new RoleFilter('american *');
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new RoleFilter('american');
        $this->assertFalse($filter->isFeatureMatch($feature));

        $filter = new RoleFilter('user');
        $this->assertFalse($filter->isFeatureMatch($feature));

        $filter = new RoleFilter('*user');
        $this->assertTrue($filter->isFeatureMatch($feature));
        
        $filter = new RoleFilter('[\w\s]+user');
        $this->assertFalse($filter->isFeatureMatch($feature));

        $filter = new RoleFilter('American User');
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode(null, null, array(), null, array(), null, null, null, 1);
        $filter = new RoleFilter('American User');
        $this->assertFalse($filter->isFeatureMatch($feature));
    }
}
