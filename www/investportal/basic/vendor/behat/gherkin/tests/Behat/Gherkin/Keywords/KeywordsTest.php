<?php

namespace Tests\Behat\Gherkin\Keywords;

use Behat\Gherkin\Keywords\KeywordsDumper;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Node\BackgroundNode;
use Behat\Gherkin\Node\ExampleTableNode;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\OutlineNode;
use Behat\Gherkin\Node\ScenarioNode;
use Behat\Gherkin\Parser;

abstract class KeywordsTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function getKeywords();
    abstract protected function getKeywordsArray();
    abstract protected function getSteps($keywords, $text, &$line, $keywordType);

    public function translationTestDataProvider()
    {
        $keywords = $this->getKeywords();
        $dumper = new KeywordsDumper($keywords);
        $keywordsArray = $this->getKeywordsArray();

        // Remove languages with repeated keywords
        unset($keywordsArray['en-old'], $keywordsArray['uz']);

        $data = array();
        foreach ($keywordsArray as $lang => $i18nKeywords) {
            $features = array();
            foreach (explode('|', $i18nKeywords['feature']) as $transNum => $featureKeyword) {
                $line = 1;
                if ('en' !== $lang) {
                    $line = 2;
                }

                $featureLine = $line;
                $line += 5;

                $keywords = explode('|', $i18nKeywords['background']);
                $backgroundLine = $line;
                $line += 1;
                $background = new BackgroundNode(null, array_merge(
                    $this->getSteps($i18nKeywords['given'], 'there is agent A', $line, 'Given'),
                    $this->getSteps($i18nKeywords['and'], 'there is agent B', $line, 'Given')
                ), $keywords[0], $backgroundLine);

                $line += 1;

                $scenarios = array();

                foreach (explode('|', $i18nKeywords['scenario']) as $scenarioKeyword) {
                    $scenarioLine = $line;
                    $line += 1;

                    $steps = array_merge(
                        $this->getSteps($i18nKeywords['given'], 'there is agent J', $line, 'Given'),
                        $this->getSteps($i18nKeywords['and'], 'there is agent K', $line, 'Given'),
                        $this->getSteps($i18nKeywords['when'], 'I erase agent K\'s memory', $line, 'When'),
                        $this->getSteps($i18nKeywords['then'], 'there should be agent J', $line, 'Then'),
                        $this->getSteps($i18nKeywords['but'], 'there should not be agent K', $line, 'Then')
                    );

                    $scenarios[] = new ScenarioNode('Erasing agent memory', array(), $steps, $scenarioKeyword, $scenarioLine);
                    $line += 1;
                }
                foreach (explode('|', $i18nKeywords['scenario_outline']) as $outlineKeyword) {
                    $outlineLine = $line;
                    $line += 1;

                    $steps = array_merge(
                        $this->getSteps($i18nKeywords['given'], 'there is agent <agent1>', $line, 'Given'),
                        $this->getSteps($i18nKeywords['and'], 'there is agent <agent2>', $line, 'Given'),
                        $this->getSteps($i18nKeywords['when'], 'I erase agent <agent2>\'s memory', $line, 'When'),
                        $this->getSteps($i18nKeywords['then'], 'there should be agent <agent1>', $line, 'Then'),
                        $this->getSteps($i18nKeywords['but'], 'there should not be agent <agent2>', $line, 'Then')
                    );
                    $line += 1;

                    $keywords = explode('|', $i18nKeywords['examples']);
                    $table = new ExampleTableNode(array(
                        ++$line => array('agent1', 'agent2'),
                        ++$line => array('D', 'M')
                    ), $keywords[0]);
                    $line += 1;

                    $scenarios[] = new OutlineNode('Erasing other agents\' memory', array(), $steps, $table, $outlineKeyword, $outlineLine);
                    $line += 1;
                }

                $features[] = new FeatureNode(
                    'Internal operations',
                    <<<DESC
In order to stay secret
As a secret organization
We need to be able to erase past agents' memory
DESC
                    ,
                    array(),
                    $background,
                    $scenarios,
                    $featureKeyword,
                    $lang,
                    __DIR__ . DIRECTORY_SEPARATOR . $lang . '_' . ($transNum + 1) . '.feature',
                    $featureLine
                );
            }

            $dumped = $dumper->dump($lang, false, true);

            foreach ($dumped as $num => $dumpedFeature) {
                $data[] = array($lang, $num, $features[$num], $dumpedFeature);
            }
        }

        return $data;
    }

    /**
     * @dataProvider translationTestDataProvider
     *
     * @param string      $language language name
     * @param int         $num      Fixture index for that language
     * @param FeatureNode $etalon   etalon features (to test against)
     * @param string      $source   gherkin source
     */
    public function testTranslation($language, $num, FeatureNode $etalon, $source)
    {
        $keywords = $this->getKeywords();
        $lexer = new Lexer($keywords);
        $parser = new Parser($lexer);

        try {
            $parsed = $parser->parse($source, __DIR__ . DIRECTORY_SEPARATOR . $language . '_' . ($num + 1) . '.feature');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage() . ":\n" . $source, 0, $e);
        }

        $this->assertEquals($etalon, $parsed);
    }
}
