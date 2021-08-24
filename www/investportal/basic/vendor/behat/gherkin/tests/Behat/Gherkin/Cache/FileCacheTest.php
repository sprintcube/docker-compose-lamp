<?php

namespace Tests\Behat\Gherkin\Cache;

use Behat\Gherkin\Cache\FileCache;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioNode;
use Behat\Gherkin\Gherkin;

class FileCacheTest extends \PHPUnit_Framework_TestCase
{
    private $path;
    private $cache;

    public function testIsFreshWhenThereIsNoFile()
    {
        $this->assertFalse($this->cache->isFresh('unexisting', time() + 100));
    }

    public function testIsFreshOnFreshFile()
    {
        $feature = new FeatureNode(null, null, array(), null, array(), null, null, null, null);

        $this->cache->write('some_path', $feature);

        $this->assertFalse($this->cache->isFresh('some_path', time() + 100));
    }

    public function testIsFreshOnOutdated()
    {
        $feature = new FeatureNode(null, null, array(), null, array(), null, null, null, null);

        $this->cache->write('some_path', $feature);

        $this->assertTrue($this->cache->isFresh('some_path', time() - 100));
    }

    public function testCacheAndRead()
    {
        $scenarios = array(new ScenarioNode('Some scenario', array(), array(), null, null));
        $feature = new FeatureNode('Some feature', 'some description', array(), null, $scenarios, null, null, null, null);

        $this->cache->write('some_feature', $feature);
        $featureRead = $this->cache->read('some_feature');

        $this->assertEquals($feature, $featureRead);
    }

    public function testBrokenCacheRead()
    {
        $this->setExpectedException('Behat\Gherkin\Exception\CacheException');

        touch($this->path . '/v' . Gherkin::VERSION . '/' . md5('broken_feature') . '.feature.cache');
        $this->cache->read('broken_feature');
    }

    public function testUnwriteableCacheDir()
    {
        $this->setExpectedException('Behat\Gherkin\Exception\CacheException');

        new FileCache('/dev/null/gherkin-test');
    }

    protected function setUp()
    {
        $this->cache = new FileCache($this->path = sys_get_temp_dir() . '/gherkin-test');
    }

    protected function tearDown()
    {
        foreach (glob($this->path . '/*.feature.cache') as $file) {
            unlink((string) $file);
        }
    }
}
