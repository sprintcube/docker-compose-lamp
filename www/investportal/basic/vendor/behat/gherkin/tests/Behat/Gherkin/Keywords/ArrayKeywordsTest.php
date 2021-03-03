<?php

namespace Tests\Behat\Gherkin\Keywords;

use Behat\Gherkin\Keywords\ArrayKeywords;
use Behat\Gherkin\Node\StepNode;

class ArrayKeywordsTest extends KeywordsTest
{
    protected function getKeywords()
    {
        return new ArrayKeywords($this->getKeywordsArray());
    }

    protected function getKeywordsArray()
    {
        return array(
            'with_special_chars' => array(
                'and' => 'And/foo',
                'background' => 'Background.',
                'but' => 'But[',
                'examples' => 'Examples|Scenarios',
                'feature' => 'Feature|Business Need|Ability',
                'given' => 'Given',
                'name' => 'English',
                'native' => 'English',
                'scenario' => 'Scenario',
                'scenario_outline' => 'Scenario Outline|Scenario Template',
                'then' => 'Then',
                'when' => 'When',
            ),
        );
    }

    protected function getSteps($keywords, $text, &$line, $keywordType)
    {
        $steps = array();
        foreach (explode('|', $keywords) as $keyword) {
            if (false !== mb_strpos($keyword, '<')) {
                $keyword = mb_substr($keyword, 0, -1);
            }

            $steps[] = new StepNode($keyword, $text, array(), $line++, $keywordType);
        }

        return $steps;
    }
}
