<?php

/*
 * This file is part of the Behat Gherkin.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Gherkin\Keywords;

/**
 * Gherkin keywords dumper.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class KeywordsDumper
{
    private $keywords;
    private $keywordsDumper;

    /**
     * Initializes dumper.
     *
     * @param KeywordsInterface $keywords Keywords instance
     */
    public function __construct(KeywordsInterface $keywords)
    {
        $this->keywords = $keywords;
        $this->keywordsDumper = array($this, 'dumpKeywords');
    }

    /**
     * Sets keywords mapper function.
     *
     * Callable should accept 2 arguments (array $keywords and bool $isShort)
     *
     * @param callable $mapper Mapper function
     */
    public function setKeywordsDumperFunction($mapper)
    {
        $this->keywordsDumper = $mapper;
    }

    /**
     * Defaults keywords dumper.
     *
     * @param array $keywords Keywords list
     * @param bool  $isShort  Is short version
     *
     * @return string
     */
    public function dumpKeywords(array $keywords, $isShort)
    {
        if ($isShort) {
            return 1 < count($keywords) ? '(' . implode('|', $keywords) . ')' : $keywords[0];
        }

        return $keywords[0];
    }

    /**
     * Dumps keyworded feature into string.
     *
     * @param string $language Keywords language
     * @param bool   $short    Dump short version
     * @param bool   $excludeAsterisk
     *
     * @return string|array String for short version and array of features for extended
     */
    public function dump($language, $short = true, $excludeAsterisk = false)
    {
        $this->keywords->setLanguage($language);
        $languageComment = '';
        if ('en' !== $language) {
            $languageComment = "# language: $language\n";
        }

        $keywords = explode('|', $this->keywords->getFeatureKeywords());

        if ($short) {
            $keywords = call_user_func($this->keywordsDumper, $keywords, $short);

            return trim($languageComment . $this->dumpFeature($keywords, $short, $excludeAsterisk));
        }

        $features = array();
        foreach ($keywords as $keyword) {
            $keyword = call_user_func($this->keywordsDumper, array($keyword), $short);
            $features[] = trim($languageComment . $this->dumpFeature($keyword, $short, $excludeAsterisk));
        }

        return $features;
    }

    /**
     * Dumps feature example.
     *
     * @param string  $keyword Item keyword
     * @param bool    $short   Dump short version?
     *
     * @return string
     */
    protected function dumpFeature($keyword, $short = true, $excludeAsterisk = false)
    {
        $dump = <<<GHERKIN
{$keyword}: Internal operations
  In order to stay secret
  As a secret organization
  We need to be able to erase past agents' memory


GHERKIN;

        // Background
        $keywords = explode('|', $this->keywords->getBackgroundKeywords());
        if ($short) {
            $keywords = call_user_func($this->keywordsDumper, $keywords, $short);
            $dump .= $this->dumpBackground($keywords, $short, $excludeAsterisk);
        } else {
            $keyword = call_user_func($this->keywordsDumper, array($keywords[0]), $short);
            $dump .= $this->dumpBackground($keyword, $short, $excludeAsterisk);
        }

        // Scenario
        $keywords = explode('|', $this->keywords->getScenarioKeywords());
        if ($short) {
            $keywords = call_user_func($this->keywordsDumper, $keywords, $short);
            $dump .= $this->dumpScenario($keywords, $short, $excludeAsterisk);
        } else {
            foreach ($keywords as $keyword) {
                $keyword = call_user_func($this->keywordsDumper, array($keyword), $short);
                $dump .= $this->dumpScenario($keyword, $short, $excludeAsterisk);
            }
        }

        // Outline
        $keywords = explode('|', $this->keywords->getOutlineKeywords());
        if ($short) {
            $keywords = call_user_func($this->keywordsDumper, $keywords, $short);
            $dump .= $this->dumpOutline($keywords, $short, $excludeAsterisk);
        } else {
            foreach ($keywords as $keyword) {
                $keyword = call_user_func($this->keywordsDumper, array($keyword), $short);
                $dump .= $this->dumpOutline($keyword, $short, $excludeAsterisk);
            }
        }

        return $dump;
    }

    /**
     * Dumps background example.
     *
     * @param string $keyword Item keyword
     * @param bool   $short   Dump short version?
     *
     * @return string
     */
    protected function dumpBackground($keyword, $short = true, $excludeAsterisk = false)
    {
        $dump = <<<GHERKIN
  {$keyword}:

GHERKIN;

        // Given
        $dump .= $this->dumpStep(
            $this->keywords->getGivenKeywords(),
            'there is agent A',
            $short,
            $excludeAsterisk
        );

        // And
        $dump .= $this->dumpStep(
            $this->keywords->getAndKeywords(),
            'there is agent B',
            $short,
            $excludeAsterisk
        );

        return $dump . "\n";
    }

    /**
     * Dumps scenario example.
     *
     * @param string $keyword Item keyword
     * @param bool   $short   Dump short version?
     *
     * @return string
     */
    protected function dumpScenario($keyword, $short = true, $excludeAsterisk = false)
    {
        $dump = <<<GHERKIN
  {$keyword}: Erasing agent memory

GHERKIN;

        // Given
        $dump .= $this->dumpStep(
            $this->keywords->getGivenKeywords(),
            'there is agent J',
            $short,
            $excludeAsterisk
        );

        // And
        $dump .= $this->dumpStep(
            $this->keywords->getAndKeywords(),
            'there is agent K',
            $short,
            $excludeAsterisk
        );

        // When
        $dump .= $this->dumpStep(
            $this->keywords->getWhenKeywords(),
            'I erase agent K\'s memory',
            $short,
            $excludeAsterisk
        );

        // Then
        $dump .= $this->dumpStep(
            $this->keywords->getThenKeywords(),
            'there should be agent J',
            $short,
            $excludeAsterisk
        );

        // But
        $dump .= $this->dumpStep(
            $this->keywords->getButKeywords(),
            'there should not be agent K',
            $short,
            $excludeAsterisk
        );

        return $dump . "\n";
    }

    /**
     * Dumps outline example.
     *
     * @param string $keyword Item keyword
     * @param bool   $short   Dump short version?
     *
     * @return string
     */
    protected function dumpOutline($keyword, $short = true, $excludeAsterisk = false)
    {
        $dump = <<<GHERKIN
  {$keyword}: Erasing other agents' memory

GHERKIN;

        // Given
        $dump .= $this->dumpStep(
            $this->keywords->getGivenKeywords(),
            'there is agent <agent1>',
            $short,
            $excludeAsterisk
        );

        // And
        $dump .= $this->dumpStep(
            $this->keywords->getAndKeywords(),
            'there is agent <agent2>',
            $short,
            $excludeAsterisk
        );

        // When
        $dump .= $this->dumpStep(
            $this->keywords->getWhenKeywords(),
            'I erase agent <agent2>\'s memory',
            $short,
            $excludeAsterisk
        );

        // Then
        $dump .= $this->dumpStep(
            $this->keywords->getThenKeywords(),
            'there should be agent <agent1>',
            $short,
            $excludeAsterisk
        );

        // But
        $dump .= $this->dumpStep(
            $this->keywords->getButKeywords(),
            'there should not be agent <agent2>',
            $short,
            $excludeAsterisk
        );

        $keywords = explode('|', $this->keywords->getExamplesKeywords());
        if ($short) {
            $keyword = call_user_func($this->keywordsDumper, $keywords, $short);
        } else {
            $keyword = call_user_func($this->keywordsDumper, array($keywords[0]), $short);
        }

        $dump .= <<<GHERKIN

    {$keyword}:
      | agent1 | agent2 |
      | D      | M      |

GHERKIN;

        return $dump . "\n";
    }

    /**
     * Dumps step example.
     *
     * @param string $keywords Item keyword
     * @param string $text     Step text
     * @param bool   $short    Dump short version?
     *
     * @return string
     */
    protected function dumpStep($keywords, $text, $short = true, $excludeAsterisk = false)
    {
        $dump = '';

        $keywords = explode('|', $keywords);
        if ($short) {
            $keywords = array_map(
                function ($keyword) {
                    return str_replace('<', '', $keyword);
                },
                $keywords
            );
            $keywords = call_user_func($this->keywordsDumper, $keywords, $short);
            $dump .= <<<GHERKIN
    {$keywords} {$text}

GHERKIN;
        } else {
            foreach ($keywords as $keyword) {
                if ($excludeAsterisk && '*' === $keyword) {
                    continue;
                }

                $indent = ' ';
                if (false !== mb_strpos($keyword, '<', 0, 'utf8')) {
                    $keyword = mb_substr($keyword, 0, -1, 'utf8');
                    $indent = '';
                }
                $keyword = call_user_func($this->keywordsDumper, array($keyword), $short);
                $dump .= <<<GHERKIN
    {$keyword}{$indent}{$text}

GHERKIN;
            }
        }

        return $dump;
    }
}
