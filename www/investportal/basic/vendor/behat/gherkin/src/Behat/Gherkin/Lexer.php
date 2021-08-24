<?php

/*
 * This file is part of the Behat Gherkin.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Gherkin;

use Behat\Gherkin\Exception\LexerException;
use Behat\Gherkin\Keywords\KeywordsInterface;

/**
 * Gherkin lexer.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Lexer
{
    private $language;
    private $lines;
    private $linesCount;
    private $line;
    private $trimmedLine;
    private $lineNumber;
    private $eos;
    private $keywords;
    private $keywordsCache = array();
    private $stepKeywordTypesCache = array();
    private $deferredObjects = array();
    private $deferredObjectsCount = 0;
    private $stashedToken;
    private $inPyString = false;
    private $pyStringSwallow = 0;
    private $featureStarted = false;
    private $allowMultilineArguments = false;
    private $allowSteps = false;

    /**
     * Initializes lexer.
     *
     * @param KeywordsInterface $keywords Keywords holder
     */
    public function __construct(KeywordsInterface $keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Sets lexer input.
     *
     * @param string $input    Input string
     * @param string $language Language name
     *
     * @throws Exception\LexerException
     */
    public function analyse($input, $language = 'en')
    {
        // try to detect unsupported encoding
        if ('UTF-8' !== mb_detect_encoding($input, 'UTF-8', true)) {
            throw new LexerException('Feature file is not in UTF8 encoding');
        }

        $input = strtr($input, array("\r\n" => "\n", "\r" => "\n"));

        $this->lines = explode("\n", $input);
        $this->linesCount = count($this->lines);
        $this->line = $this->lines[0];
        $this->lineNumber = 1;
        $this->trimmedLine = null;
        $this->eos = false;

        $this->deferredObjects = array();
        $this->deferredObjectsCount = 0;
        $this->stashedToken = null;
        $this->inPyString = false;
        $this->pyStringSwallow = 0;

        $this->featureStarted = false;
        $this->allowMultilineArguments = false;
        $this->allowSteps = false;

        $this->keywords->setLanguage($this->language = $language);
        $this->keywordsCache = array();
        $this->stepKeywordTypesCache = array();
    }

    /**
     * Returns current lexer language.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Returns next token or previously stashed one.
     *
     * @return array
     */
    public function getAdvancedToken()
    {
        return $this->getStashedToken() ?: $this->getNextToken();
    }

    /**
     * Defers token.
     *
     * @param array $token Token to defer
     */
    public function deferToken(array $token)
    {
        $token['deferred'] = true;
        $this->deferredObjects[] = $token;
        ++$this->deferredObjectsCount;
    }

    /**
     * Predicts for number of tokens.
     *
     * @return array
     */
    public function predictToken()
    {
        if (null === $this->stashedToken) {
            $this->stashedToken = $this->getNextToken();
        }

        return $this->stashedToken;
    }

    /**
     * Constructs token with specified parameters.
     *
     * @param string $type  Token type
     * @param string $value Token value
     *
     * @return array
     */
    public function takeToken($type, $value = null)
    {
        return array(
            'type'     => $type,
            'line'     => $this->lineNumber,
            'value'    => $value ?: null,
            'deferred' => false
        );
    }

    /**
     * Consumes line from input & increments line counter.
     */
    protected function consumeLine()
    {
        ++$this->lineNumber;

        if (($this->lineNumber - 1) === $this->linesCount) {
            $this->eos = true;

            return;
        }

        $this->line = $this->lines[$this->lineNumber - 1];
        $this->trimmedLine = null;
    }

    /**
     * Returns trimmed version of line.
     *
     * @return string
     */
    protected function getTrimmedLine()
    {
        return null !== $this->trimmedLine ? $this->trimmedLine : $this->trimmedLine = trim($this->line);
    }

    /**
     * Returns stashed token or null if hasn't.
     *
     * @return array|null
     */
    protected function getStashedToken()
    {
        $stashedToken = $this->stashedToken;
        $this->stashedToken = null;

        return $stashedToken;
    }

    /**
     * Returns deferred token or null if hasn't.
     *
     * @return array|null
     */
    protected function getDeferredToken()
    {
        if (!$this->deferredObjectsCount) {
            return null;
        }

        --$this->deferredObjectsCount;

        return array_shift($this->deferredObjects);
    }

    /**
     * Returns next token from input.
     *
     * @return array
     */
    protected function getNextToken()
    {
        return $this->getDeferredToken()
            ?: $this->scanEOS()
            ?: $this->scanLanguage()
            ?: $this->scanComment()
            ?: $this->scanPyStringOp()
            ?: $this->scanPyStringContent()
            ?: $this->scanStep()
            ?: $this->scanScenario()
            ?: $this->scanBackground()
            ?: $this->scanOutline()
            ?: $this->scanExamples()
            ?: $this->scanFeature()
            ?: $this->scanTags()
            ?: $this->scanTableRow()
            ?: $this->scanNewline()
            ?: $this->scanText();
    }

    /**
     * Scans for token with specified regex.
     *
     * @param string $regex Regular expression
     * @param string $type  Expected token type
     *
     * @return null|array
     */
    protected function scanInput($regex, $type)
    {
        if (!preg_match($regex, $this->line, $matches)) {
            return null;
        }

        $token = $this->takeToken($type, $matches[1]);
        $this->consumeLine();

        return $token;
    }

    /**
     * Scans for token with specified keywords.
     *
     * @param string $keywords Keywords (splitted with |)
     * @param string $type     Expected token type
     *
     * @return null|array
     */
    protected function scanInputForKeywords($keywords, $type)
    {
        if (!preg_match('/^(\s*)(' . $keywords . '):\s*(.*)/u', $this->line, $matches)) {
            return null;
        }

        $token = $this->takeToken($type, $matches[3]);
        $token['keyword'] = $matches[2];
        $token['indent'] = mb_strlen($matches[1], 'utf8');

        $this->consumeLine();

        // turn off language searching
        if ('Feature' === $type) {
            $this->featureStarted = true;
        }

        // turn off PyString and Table searching
        if ('Feature' === $type || 'Scenario' === $type || 'Outline' === $type) {
            $this->allowMultilineArguments = false;
        } elseif ('Examples' === $type) {
            $this->allowMultilineArguments = true;
        }

        // turn on steps searching
        if ('Scenario' === $type || 'Background' === $type || 'Outline' === $type) {
            $this->allowSteps = true;
        }

        return $token;
    }

    /**
     * Scans EOS from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanEOS()
    {
        if (!$this->eos) {
            return null;
        }

        return $this->takeToken('EOS');
    }

    /**
     * Returns keywords for provided type.
     *
     * @param string $type Keyword type
     *
     * @return string
     */
    protected function getKeywords($type)
    {
        if (!isset($this->keywordsCache[$type])) {
            $getter = 'get' . $type . 'Keywords';
            $keywords = $this->keywords->$getter();

            if ('Step' === $type) {
                $padded = array();
                foreach (explode('|', $keywords) as $keyword) {
                    $padded[] = false !== mb_strpos($keyword, '<', 0, 'utf8')
                        ? preg_quote(mb_substr($keyword, 0, -1, 'utf8'), '/') . '\s*'
                        : preg_quote($keyword, '/') . '\s+';
                }

                $keywords = implode('|', $padded);
            }

            $this->keywordsCache[$type] = $keywords;
        }

        return $this->keywordsCache[$type];
    }

    /**
     * Scans Feature from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanFeature()
    {
        return $this->scanInputForKeywords($this->getKeywords('Feature'), 'Feature');
    }

    /**
     * Scans Background from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanBackground()
    {
        return $this->scanInputForKeywords($this->getKeywords('Background'), 'Background');
    }

    /**
     * Scans Scenario from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanScenario()
    {
        return $this->scanInputForKeywords($this->getKeywords('Scenario'), 'Scenario');
    }

    /**
     * Scans Scenario Outline from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanOutline()
    {
        return $this->scanInputForKeywords($this->getKeywords('Outline'), 'Outline');
    }

    /**
     * Scans Scenario Outline Examples from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanExamples()
    {
        return $this->scanInputForKeywords($this->getKeywords('Examples'), 'Examples');
    }

    /**
     * Scans Step from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanStep()
    {
        if (!$this->allowSteps) {
            return null;
        }

        $keywords = $this->getKeywords('Step');
        if (!preg_match('/^\s*(' . $keywords . ')([^\s].+)/u', $this->line, $matches)) {
            return null;
        }

        $keyword = trim($matches[1]);
        $token = $this->takeToken('Step', $keyword);
        $token['keyword_type'] = $this->getStepKeywordType($keyword);
        $token['text'] = $matches[2];

        $this->consumeLine();
        $this->allowMultilineArguments = true;

        return $token;
    }

    /**
     * Scans PyString from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanPyStringOp()
    {
        if (!$this->allowMultilineArguments) {
            return null;
        }

        if (false === ($pos = mb_strpos($this->line, '"""', 0, 'utf8'))) {
            return null;
        }

        $this->inPyString = !$this->inPyString;
        $token = $this->takeToken('PyStringOp');
        $this->pyStringSwallow = $pos;

        $this->consumeLine();

        return $token;
    }

    /**
     * Scans PyString content.
     *
     * @return null|array
     */
    protected function scanPyStringContent()
    {
        if (!$this->inPyString) {
            return null;
        }

        $token = $this->scanText();
        // swallow trailing spaces
        $token['value'] = preg_replace('/^\s{0,' . $this->pyStringSwallow . '}/u', '', $token['value']);

        return $token;
    }

    /**
     * Scans Table Row from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanTableRow()
    {
        if (!$this->allowMultilineArguments) {
            return null;
        }

        $line = $this->getTrimmedLine();
        if (!isset($line[0]) || '|' !== $line[0] || '|' !== substr($line, -1)) {
            return null;
        }

        $token = $this->takeToken('TableRow');
        $line = mb_substr($line, 1, mb_strlen($line, 'utf8') - 2, 'utf8');
        $columns = array_map(function ($column) {
            return trim(str_replace('\\|', '|', $column));
        }, preg_split('/(?<!\\\)\|/u', $line));
        $token['columns'] = $columns;

        $this->consumeLine();

        return $token;
    }

    /**
     * Scans Tags from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanTags()
    {
        $line = $this->getTrimmedLine();
        if (!isset($line[0]) || '@' !== $line[0]) {
            return null;
        }

        $token = $this->takeToken('Tag');
        $tags = explode('@', mb_substr($line, 1, mb_strlen($line, 'utf8') - 1, 'utf8'));
        $tags = array_map('trim', $tags);
        $token['tags'] = $tags;

        $this->consumeLine();

        return $token;
    }

    /**
     * Scans Language specifier from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanLanguage()
    {
        if ($this->featureStarted) {
            return null;
        }

        if ($this->inPyString) {
            return null;
        }

        if (0 !== mb_strpos(ltrim($this->line), '#', 0, 'utf8')) {
            return null;
        }

        return $this->scanInput('/^\s*\#\s*language:\s*([\w_\-]+)\s*$/', 'Language');
    }

    /**
     * Scans Comment from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanComment()
    {
        if ($this->inPyString) {
            return null;
        }

        $line = $this->getTrimmedLine();
        if (0 !== mb_strpos($line, '#', 0, 'utf8')) {
            return null;
        }

        $token = $this->takeToken('Comment', $line);
        $this->consumeLine();

        return $token;
    }

    /**
     * Scans Newline from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanNewline()
    {
        if ('' !== $this->getTrimmedLine()) {
            return null;
        }

        $token = $this->takeToken('Newline', mb_strlen($this->line, 'utf8'));
        $this->consumeLine();

        return $token;
    }

    /**
     * Scans text from input & returns it if found.
     *
     * @return null|array
     */
    protected function scanText()
    {
        $token = $this->takeToken('Text', $this->line);
        $this->consumeLine();

        return $token;
    }

    /**
     * Returns step type keyword (Given, When, Then, etc.).
     *
     * @param string $native Step keyword in provided language
     * @return string
     */
    private function getStepKeywordType($native)
    {
        // Consider "*" as a AND keyword so that it is normalized to the previous step type
        if ('*' === $native) {
            return 'And';
        }

        if (empty($this->stepKeywordTypesCache)) {
            $this->stepKeywordTypesCache = array(
                'Given' => explode('|', $this->keywords->getGivenKeywords()),
                'When' => explode('|', $this->keywords->getWhenKeywords()),
                'Then' => explode('|', $this->keywords->getThenKeywords()),
                'And' => explode('|', $this->keywords->getAndKeywords()),
                'But' => explode('|', $this->keywords->getButKeywords())
            );
        }

        foreach ($this->stepKeywordTypesCache as $type => $keywords) {
            if (in_array($native, $keywords) || in_array($native . '<', $keywords)) {
                return $type;
            }
        }

        return 'Given';
    }
}
