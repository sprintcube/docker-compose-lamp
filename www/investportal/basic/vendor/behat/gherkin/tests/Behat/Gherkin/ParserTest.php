<?php

namespace Tests\Behat\Gherkin;

use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Parser;
use Behat\Gherkin\Keywords\ArrayKeywords;
use Behat\Gherkin\Loader\YamlFileLoader;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    private $gherkin;
    private $yaml;

    public function parserTestDataProvider()
    {
        $data = array();

        foreach (glob(__DIR__ . '/Fixtures/etalons/*.yml') as $file) {
            $testname = basename($file, '.yml');

            $data[] = array($testname);
        }

        return $data;
    }

    /**
     * @dataProvider parserTestDataProvider
     *
     * @param string $fixtureName name of the fixture
     */
    public function testParser($fixtureName)
    {
        $etalon = $this->parseEtalon($fixtureName . '.yml');
        $features = $this->parseFixture($fixtureName . '.feature');

        $this->assertInternalType('array', $features);
        $this->assertEquals(1, count($features));
        $fixture = $features[0];

        $this->assertEquals($etalon, $fixture);
    }

    public function testParserResetsTagsBetweenFeatures()
    {
        $parser = $this->getGherkinParser();

        $parser->parse(<<<FEATURE
Feature:
Scenario:
Given step
@skipped
FEATURE
        );
        $feature2 = $parser->parse(<<<FEATURE
Feature:
Scenario:
Given step
FEATURE
        );

        $this->assertFalse($feature2->hasTags());
    }

    protected function getGherkinParser()
    {
        if (null === $this->gherkin) {
            $keywords       = new ArrayKeywords(array(
                'en' => array(
                    'feature'          => 'Feature',
                    'background'       => 'Background',
                    'scenario'         => 'Scenario',
                    'scenario_outline' => 'Scenario Outline',
                    'examples'         => 'Examples',
                    'given'            => 'Given',
                    'when'             => 'When',
                    'then'             => 'Then',
                    'and'              => 'And',
                    'but'              => 'But'
                ),
                'ru' => array(
                    'feature'          => 'Функционал',
                    'background'       => 'Предыстория',
                    'scenario'         => 'Сценарий',
                    'scenario_outline' => 'Структура сценария',
                    'examples'         => 'Примеры',
                    'given'            => 'Допустим',
                    'when'             => 'То',
                    'then'             => 'Если',
                    'and'              => 'И',
                    'but'              => 'Но'
                ),
                'ja' => array (
                    'feature'           => 'フィーチャ',
                    'background'        => '背景',
                    'scenario'          => 'シナリオ',
                    'scenario_outline'  => 'シナリオアウトライン',
                    'examples'          => '例|サンプル',
                    'given'             => '前提<',
                    'when'              => 'もし<',
                    'then'              => 'ならば<',
                    'and'               => 'かつ<',
                    'but'               => 'しかし<'
                )
            ));
            $this->gherkin  = new Parser(new Lexer($keywords));
        }

        return $this->gherkin;
    }

    protected function getYamlParser()
    {
        if (null === $this->yaml) {
            $this->yaml = new YamlFileLoader();
        }

        return $this->yaml;
    }

    protected function parseFixture($fixture)
    {
        $file = __DIR__ . '/Fixtures/features/' . $fixture;

        return array($this->getGherkinParser()->parse(file_get_contents($file), $file));
    }

    protected function parseEtalon($etalon)
    {
        $features = $this->getYamlParser()->load(__DIR__ . '/Fixtures/etalons/' . $etalon);
        $feature  = $features[0];

        return new FeatureNode(
            $feature->getTitle(),
            $feature->getDescription(),
            $feature->getTags(),
            $feature->getBackground(),
            $feature->getScenarios(),
            $feature->getKeyword(),
            $feature->getLanguage(),
            __DIR__ . '/Fixtures/features/' . basename($etalon, '.yml') . '.feature',
            $feature->getLine()
        );
    }
}
