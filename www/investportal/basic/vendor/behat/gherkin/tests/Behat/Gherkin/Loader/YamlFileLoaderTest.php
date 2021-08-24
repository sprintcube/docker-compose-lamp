<?php

namespace Tests\Behat\Gherkin\Loader;

use Behat\Gherkin\Loader\YamlFileLoader;

class YamlFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    private $loader;

    protected function setUp()
    {
        $this->loader = new YamlFileLoader();
    }

    public function testSupports()
    {
        $this->assertFalse($this->loader->supports(__DIR__));
        $this->assertFalse($this->loader->supports(__FILE__));
        $this->assertFalse($this->loader->supports('string'));
        $this->assertFalse($this->loader->supports(__DIR__ . '/file.yml'));
        $this->assertTrue($this->loader->supports(__DIR__ . '/../Fixtures/etalons/addition.yml'));
    }

    public function testLoadAddition()
    {
        $basePath = __DIR__ . '/../Fixtures';
        $this->loader->setBasePath($basePath);
        $features = $this->loader->load('etalons/addition.yml');

        $this->assertEquals(1, count($features));
        $this->assertEquals(realpath($basePath . DIRECTORY_SEPARATOR . 'etalons' . DIRECTORY_SEPARATOR . 'addition.yml'), $features[0]->getFile());
        $this->assertEquals('Addition', $features[0]->getTitle());
        $this->assertEquals(2, $features[0]->getLine());
        $this->assertEquals('en', $features[0]->getLanguage());
        $expectedDescription = <<<EOS
In order to avoid silly mistakes
As a math idiot
I want to be told the sum of two numbers
EOS;
        $this->assertEquals($expectedDescription, $features[0]->getDescription());

        $scenarios = $features[0]->getScenarios();

        $this->assertEquals(2, count($scenarios));
        $this->assertInstanceOf('Behat\Gherkin\Node\ScenarioNode', $scenarios[0]);
        $this->assertEquals(7, $scenarios[0]->getLine());
        $this->assertEquals('Add two numbers', $scenarios[0]->getTitle());
        $steps = $scenarios[0]->getSteps();
        $this->assertEquals(4, count($steps));
        $this->assertEquals(9, $steps[1]->getLine());
        $this->assertEquals('And', $steps[1]->getType());
        $this->assertEquals('And', $steps[1]->getKeyword());
        $this->assertEquals('Given', $steps[1]->getKeywordType());
        $this->assertEquals('I have entered 12 into the calculator', $steps[1]->getText());

        $this->assertInstanceOf('Behat\Gherkin\Node\ScenarioNode', $scenarios[1]);
        $this->assertEquals(13, $scenarios[1]->getLine());
        $this->assertEquals('Div two numbers', $scenarios[1]->getTitle());
        $steps = $scenarios[1]->getSteps();
        $this->assertEquals(4, count($steps));
        $this->assertEquals(16, $steps[2]->getLine());
        $this->assertEquals('When', $steps[2]->getType());
        $this->assertEquals('When', $steps[2]->getKeyword());
        $this->assertEquals('When', $steps[2]->getKeywordType());
        $this->assertEquals('I press div', $steps[2]->getText());
    }
}
