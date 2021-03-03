<?php

namespace Tests\Behat\Gherkin\Filter;

use Behat\Gherkin\Filter\PathsFilter;
use Behat\Gherkin\Node\FeatureNode;

class PathsFilterTest extends FilterTest
{
    public function testIsFeatureMatchFilter()
    {
        $feature = new FeatureNode(null, null, array(), null, array(), null, null, __FILE__, 1);

        $filter = new PathsFilter(array(__DIR__));
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new PathsFilter(array('/abc', '/def', dirname(__DIR__)));
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new PathsFilter(array('/abc', '/def', __DIR__));
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new PathsFilter(array('/abc', __DIR__, '/def'));
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new PathsFilter(array('/abc', '/def', '/wrong/path'));
        $this->assertFalse($filter->isFeatureMatch($feature));
    }

    public function testItDoesNotMatchPartialPaths()
    {
        $fixtures = __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR;

        $feature = new FeatureNode(null, null, array(), null, array(), null, null, $fixtures . 'full_path' . DIRECTORY_SEPARATOR . 'file1', 1);

        $filter = new PathsFilter(array($fixtures . 'full'));
        $this->assertFalse($filter->isFeatureMatch($feature));

        $filter = new PathsFilter(array($fixtures . 'full' . DIRECTORY_SEPARATOR));
        $this->assertFalse($filter->isFeatureMatch($feature));

        $filter = new PathsFilter(array($fixtures . 'full_path' . DIRECTORY_SEPARATOR));
        $this->assertTrue($filter->isFeatureMatch($feature));

        $filter = new PathsFilter(array($fixtures . 'full_path'));
        $this->assertTrue($filter->isFeatureMatch($feature));
        
        $filter = new PathsFilter(array($fixtures . 'ful._path')); // Don't accept regexp
        $this->assertFalse($filter->isFeatureMatch($feature));
    }

    public function testItDoesNotMatchIfFileWithSameNameButNotPathExistsInFolder()
    {
        $fixtures = __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR;

        $feature = new FeatureNode(null, null, array(), null, array(), null, null, $fixtures . 'full_path' . DIRECTORY_SEPARATOR . 'file1', 1);

        $filter = new PathsFilter(array($fixtures . 'full'));
        $this->assertFalse($filter->isFeatureMatch($feature));
    }
}
