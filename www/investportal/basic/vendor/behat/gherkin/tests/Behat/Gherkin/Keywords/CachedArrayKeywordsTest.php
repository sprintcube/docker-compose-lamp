<?php

namespace Tests\Behat\Gherkin\Keywords;

use Behat\Gherkin\Keywords\CachedArrayKeywords;
use Behat\Gherkin\Node\StepNode;

class CachedArrayKeywordsTest extends KeywordsTest
{
    protected function getKeywords()
    {
        return new CachedArrayKeywords(__DIR__ . '/../../../../i18n.php');
    }

    protected function getKeywordsArray()
    {
        return include(__DIR__ . '/../../../../i18n.php');
    }

    protected function getSteps($keywords, $text, &$line, $keywordType)
    {
        $steps = array();
        foreach (explode('|', $keywords) as $keyword) {
            if ('*' === $keyword) {
                continue;
            }

            if (false !== mb_strpos($keyword, '<')) {
                $keyword = mb_substr($keyword, 0, -1);
            }

            $steps[] = new StepNode($keyword, $text, array(), $line++, $keywordType);
        }

        return $steps;
    }
}
