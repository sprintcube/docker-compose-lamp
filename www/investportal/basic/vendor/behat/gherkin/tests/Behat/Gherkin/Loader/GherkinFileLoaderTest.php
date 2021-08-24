<?php

namespace Tests\Behat\Gherkin\Loader;

use Behat\Gherkin\Keywords\CucumberKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Loader\GherkinFileLoader;
use Behat\Gherkin\Parser;

class GherkinFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GherkinFileLoader
     */
    private $loader;
    private $featuresPath;

    public function testSupports()
    {
        $this->assertFalse($this->loader->supports('non-existent path'));
        $this->assertFalse($this->loader->supports('non-existent path:2'));

        $this->assertFalse($this->loader->supports(__DIR__));
        $this->assertFalse($this->loader->supports(__DIR__ . ':d'));
        $this->assertFalse($this->loader->supports(__FILE__));
        $this->assertTrue($this->loader->supports(__DIR__ . '/../Fixtures/features/pystring.feature'));
    }

    public function testLoad()
    {
        $features = $this->loader->load($this->featuresPath . '/pystring.feature');
        $this->assertEquals(1, count($features));
        $this->assertEquals('A py string feature', $features[0]->getTitle());
        $this->assertEquals($this->featuresPath . DIRECTORY_SEPARATOR . 'pystring.feature', $features[0]->getFile());

        $features = $this->loader->load($this->featuresPath . '/multiline_name.feature');
        $this->assertEquals(1, count($features));
        $this->assertEquals('multiline', $features[0]->getTitle());
        $this->assertEquals($this->featuresPath . DIRECTORY_SEPARATOR . 'multiline_name.feature', $features[0]->getFile());
    }

    public function testParsingUncachedFeature()
    {
        $cache = $this->getMockBuilder('Behat\Gherkin\Cache\CacheInterface')->getMock();
        $this->loader->setCache($cache);

        $cache->expects($this->once())
            ->method('isFresh')
            ->with($path = $this->featuresPath . DIRECTORY_SEPARATOR . 'pystring.feature', filemtime($path))
            ->will($this->returnValue(false));

        $cache->expects($this->once())
            ->method('write');

        $features = $this->loader->load($this->featuresPath . '/pystring.feature');
        $this->assertEquals(1, count($features));
    }

    public function testParsingCachedFeature()
    {
        $cache = $this->getMockBuilder('Behat\Gherkin\Cache\CacheInterface')->getMock();
        $this->loader->setCache($cache);

        $cache->expects($this->once())
            ->method('isFresh')
            ->with($path = $this->featuresPath . DIRECTORY_SEPARATOR . 'pystring.feature', filemtime($path))
            ->will($this->returnValue(true));

        $cache->expects($this->once())
            ->method('read')
            ->with($path)
            ->will($this->returnValue('cache'));

        $cache->expects($this->never())
            ->method('write');

        $features = $this->loader->load($this->featuresPath . '/pystring.feature');
        $this->assertEquals('cache', $features[0]);
    }

    public function testBasePath()
    {
        $this->assertFalse($this->loader->supports('features'));
        $this->assertFalse($this->loader->supports('tables.feature'));

        $this->loader->setBasePath($this->featuresPath . '/../');
        $this->assertFalse($this->loader->supports('features'));
        $this->assertFalse($this->loader->supports('tables.feature'));
        $this->assertTrue($this->loader->supports('features/tables.feature'));

        $features = $this->loader->load('features/pystring.feature');
        $this->assertEquals(1, count($features));
        $this->assertEquals('A py string feature', $features[0]->getTitle());
        $this->assertEquals(realpath($this->featuresPath . DIRECTORY_SEPARATOR . 'pystring.feature'), $features[0]->getFile());

        $this->loader->setBasePath($this->featuresPath);
        $features = $this->loader->load('multiline_name.feature');
        $this->assertEquals(1, count($features));
        $this->assertEquals('multiline', $features[0]->getTitle());
        $this->assertEquals(realpath($this->featuresPath . DIRECTORY_SEPARATOR . 'multiline_name.feature'), $features[0]->getFile());
    }

    protected function setUp()
    {
        $keywords = new CucumberKeywords(__DIR__ . '/../Fixtures/i18n.yml');
        $parser = new Parser(new Lexer($keywords));
        $this->loader = new GherkinFileLoader($parser);

        $this->featuresPath = realpath(__DIR__ . '/../Fixtures/features');
    }
}
