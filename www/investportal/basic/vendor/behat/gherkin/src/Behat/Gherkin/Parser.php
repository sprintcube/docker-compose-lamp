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
use Behat\Gherkin\Exception\NodeException;
use Behat\Gherkin\Exception\ParserException;
use Behat\Gherkin\Node\BackgroundNode;
use Behat\Gherkin\Node\ExampleTableNode;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\OutlineNode;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\ScenarioInterface;
use Behat\Gherkin\Node\ScenarioNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Gherkin parser.
 *
 * $lexer  = new Behat\Gherkin\Lexer($keywords);
 * $parser = new Behat\Gherkin\Parser($lexer);
 * $featuresArray = $parser->parse('/path/to/feature.feature');
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Parser
{
    private $lexer;
    private $input;
    private $file;
    private $tags = array();
    private $languageSpecifierLine;

    private $passedNodesStack = array();

    /**
     * Initializes parser.
     *
     * @param Lexer $lexer Lexer instance
     */
    public function __construct(Lexer $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     * Parses input & returns features array.
     *
     * @param string $input Gherkin string document
     * @param string $file  File name
     *
     * @return FeatureNode|null
     *
     * @throws ParserException
     */
    public function parse($input, $file = null)
    {
        $this->languageSpecifierLine = null;
        $this->input = $input;
        $this->file = $file;
        $this->tags = array();

        try {
            $this->lexer->analyse($this->input, 'en');
        } catch (LexerException $e) {
            throw new ParserException(
                sprintf('Lexer exception "%s" thrown for file %s', $e->getMessage(), $file),
                0,
                $e
            );
        }

        $feature = null;
        while ('EOS' !== ($predicted = $this->predictTokenType())) {
            $node = $this->parseExpression();

            if (null === $node || "\n" === $node) {
                continue;
            }

            if (!$feature && $node instanceof FeatureNode) {
                $feature = $node;
                continue;
            }

            if ($feature && $node instanceof FeatureNode) {
                throw new ParserException(sprintf(
                    'Only one feature is allowed per feature file. But %s got multiple.',
                    $this->file
                ));
            }

            if (is_string($node)) {
                throw new ParserException(sprintf(
                    'Expected Feature, but got text: "%s"%s',
                    $node,
                    $this->file ? ' in file: ' . $this->file : ''
                ));
            }

            if (!$node instanceof FeatureNode) {
                throw new ParserException(sprintf(
                    'Expected Feature, but got %s on line: %d%s',
                    $node->getKeyword(),
                    $node->getLine(),
                    $this->file ? ' in file: ' . $this->file : ''
                ));
            }
        }

        return $feature;
    }

    /**
     * Returns next token if it's type equals to expected.
     *
     * @param string $type Token type
     *
     * @return array
     *
     * @throws Exception\ParserException
     */
    protected function expectTokenType($type)
    {
        $types = (array) $type;
        if (in_array($this->predictTokenType(), $types)) {
            return $this->lexer->getAdvancedToken();
        }

        $token = $this->lexer->predictToken();

        throw new ParserException(sprintf(
            'Expected %s token, but got %s on line: %d%s',
            implode(' or ', $types),
            $this->predictTokenType(),
            $token['line'],
            $this->file ? ' in file: ' . $this->file : ''
        ));
    }

    /**
     * Returns next token if it's type equals to expected.
     *
     * @param string $type Token type
     *
     * @return null|array
     */
    protected function acceptTokenType($type)
    {
        if ($type !== $this->predictTokenType()) {
            return null;
        }

        return $this->lexer->getAdvancedToken();
    }

    /**
     * Returns next token type without real input reading (prediction).
     *
     * @return string
     */
    protected function predictTokenType()
    {
        $token = $this->lexer->predictToken();

        return $token['type'];
    }

    /**
     * Parses current expression & returns Node.
     *
     * @return string|FeatureNode|BackgroundNode|ScenarioNode|OutlineNode|TableNode|StepNode
     *
     * @throws ParserException
     */
    protected function parseExpression()
    {
        $type = $this->predictTokenType();

        while ($type === 'Comment') {
            $this->expectTokenType('Comment');

            $type = $this->predictTokenType();
        }

        switch ($type) {
            case 'Feature':
                return $this->parseFeature();
            case 'Background':
                return $this->parseBackground();
            case 'Scenario':
                return $this->parseScenario();
            case 'Outline':
                return $this->parseOutline();
            case 'Examples':
                return $this->parseExamples();
            case 'TableRow':
                return $this->parseTable();
            case 'PyStringOp':
                return $this->parsePyString();
            case 'Step':
                return $this->parseStep();
            case 'Text':
                return $this->parseText();
            case 'Newline':
                return $this->parseNewline();
            case 'Tag':
                return $this->parseTags();
            case 'Language':
                return $this->parseLanguage();
            case 'EOS':
                return '';
        }

        throw new ParserException(sprintf('Unknown token type: %s', $type));
    }

    /**
     * Parses feature token & returns it's node.
     *
     * @return FeatureNode
     *
     * @throws ParserException
     */
    protected function parseFeature()
    {
        $token = $this->expectTokenType('Feature');

        $title = trim($token['value'] ?? '');
        $description = null;
        $tags = $this->popTags();
        $background = null;
        $scenarios = array();
        $keyword = $token['keyword'];
        $language = $this->lexer->getLanguage();
        $file = $this->file;
        $line = $token['line'];

        array_push($this->passedNodesStack, 'Feature');

        // Parse description, background, scenarios & outlines
        while ('EOS' !== $this->predictTokenType()) {
            $node = $this->parseExpression();

            if (is_string($node)) {
                $text = preg_replace('/^\s{0,' . ($token['indent'] + 2) . '}|\s*$/', '', $node);
                $description .= (null !== $description ? "\n" : '') . $text;
                continue;
            }

            if (!$background && $node instanceof BackgroundNode) {
                $background = $node;
                continue;
            }

            if ($node instanceof ScenarioInterface) {
                $scenarios[] = $node;
                continue;
            }

            if ($background instanceof BackgroundNode && $node instanceof BackgroundNode) {
                throw new ParserException(sprintf(
                    'Each Feature could have only one Background, but found multiple on lines %d and %d%s',
                    $background->getLine(),
                    $node->getLine(),
                    $this->file ? ' in file: ' . $this->file : ''
                ));
            }

            if (!$node instanceof ScenarioNode) {
                throw new ParserException(sprintf(
                    'Expected Scenario, Outline or Background, but got %s on line: %d%s',
                    $node->getNodeType(),
                    $node->getLine(),
                    $this->file ? ' in file: ' . $this->file : ''
                ));
            }
        }

        return new FeatureNode(
            rtrim($title) ?: null,
            rtrim($description ?? '') ?: null,
            $tags,
            $background,
            $scenarios,
            $keyword,
            $language,
            $file,
            $line
        );
    }

    /**
     * Parses background token & returns it's node.
     *
     * @return BackgroundNode
     *
     * @throws ParserException
     */
    protected function parseBackground()
    {
        $token = $this->expectTokenType('Background');

        $title = trim($token['value'] ?? '');
        $keyword = $token['keyword'];
        $line = $token['line'];

        if (count($this->popTags())) {
            throw new ParserException(sprintf(
                'Background can not be tagged, but it is on line: %d%s',
                $line,
                $this->file ? ' in file: ' . $this->file : ''
            ));
        }

        // Parse description and steps
        $steps = array();
        $allowedTokenTypes = array('Step', 'Newline', 'Text', 'Comment');
        while (in_array($this->predictTokenType(), $allowedTokenTypes)) {
            $node = $this->parseExpression();

            if ($node instanceof StepNode) {
                $steps[] = $this->normalizeStepNodeKeywordType($node, $steps);
                continue;
            }

            if (!count($steps) && is_string($node)) {
                $text = preg_replace('/^\s{0,' . ($token['indent'] + 2) . '}|\s*$/', '', $node);
                $title .= "\n" . $text;
                continue;
            }

            if ("\n" === $node) {
                continue;
            }

            if (is_string($node)) {
                throw new ParserException(sprintf(
                    'Expected Step, but got text: "%s"%s',
                    $node,
                    $this->file ? ' in file: ' . $this->file : ''
                ));
            }

            if (!$node instanceof StepNode) {
                throw new ParserException(sprintf(
                    'Expected Step, but got %s on line: %d%s',
                    $node->getNodeType(),
                    $node->getLine(),
                    $this->file ? ' in file: ' . $this->file : ''
                ));
            }
        }

        return new BackgroundNode(rtrim($title) ?: null, $steps, $keyword, $line);
    }

    /**
     * Parses scenario token & returns it's node.
     *
     * @return ScenarioNode
     *
     * @throws ParserException
     */
    protected function parseScenario()
    {
        $token = $this->expectTokenType('Scenario');

        $title = trim($token['value'] ?? '');
        $tags = $this->popTags();
        $keyword = $token['keyword'];
        $line = $token['line'];

        array_push($this->passedNodesStack, 'Scenario');

        // Parse description and steps
        $steps = array();
        while (in_array($this->predictTokenType(), array('Step', 'Newline', 'Text', 'Comment'))) {
            $node = $this->parseExpression();

            if ($node instanceof StepNode) {
                $steps[] = $this->normalizeStepNodeKeywordType($node, $steps);
                continue;
            }

            if (!count($steps) && is_string($node)) {
                $text = preg_replace('/^\s{0,' . ($token['indent'] + 2) . '}|\s*$/', '', $node);
                $title .= "\n" . $text;
                continue;
            }

            if ("\n" === $node) {
                continue;
            }

            if (is_string($node)) {
                throw new ParserException(sprintf(
                    'Expected Step, but got text: "%s"%s',
                    $node,
                    $this->file ? ' in file: ' . $this->file : ''
                ));
            }

            if (!$node instanceof StepNode) {
                throw new ParserException(sprintf(
                    'Expected Step, but got %s on line: %d%s',
                    $node->getNodeType(),
                    $node->getLine(),
                    $this->file ? ' in file: ' . $this->file : ''
                ));
            }
        }

        array_pop($this->passedNodesStack);

        return new ScenarioNode(rtrim($title) ?: null, $tags, $steps, $keyword, $line);
    }

    /**
     * Parses scenario outline token & returns it's node.
     *
     * @return OutlineNode
     *
     * @throws ParserException
     */
    protected function parseOutline()
    {
        $token = $this->expectTokenType('Outline');

        $title = trim($token['value'] ?? '');
        $tags = $this->popTags();
        $keyword = $token['keyword'];

        /** @var ExampleTableNode $examples */
        $examples = array();
        $line = $token['line'];

        // Parse description, steps and examples
        $steps = array();

        array_push($this->passedNodesStack, 'Outline');

        while (in_array($nextTokenType = $this->predictTokenType(), array('Step', 'Examples', 'Newline', 'Text', 'Comment', 'Tag'))) {
            if ($nextTokenType === 'Comment') {
                $this->lexer->skipPredictedToken();
                continue;
            }

            $node = $this->parseExpression();

            if ($node instanceof StepNode) {
                $steps[] = $this->normalizeStepNodeKeywordType($node, $steps);
                continue;
            }

            if ($node instanceof ExampleTableNode) {
                $examples[] = $node;

                continue;
            }

            if (!count($steps) && is_string($node)) {
                $text = preg_replace('/^\s{0,' . ($token['indent'] + 2) . '}|\s*$/', '', $node);
                $title .= "\n" . $text;
                continue;
            }

            if ("\n" === $node) {
                continue;
            }

            if (is_string($node)) {
                throw new ParserException(sprintf(
                    'Expected Step or Examples table, but got text: "%s"%s',
                    $node,
                    $this->file ? ' in file: ' . $this->file : ''
                ));
            }

            if (!$node instanceof StepNode) {
                throw new ParserException(sprintf(
                    'Expected Step or Examples table, but got %s on line: %d%s',
                    $node->getNodeType(),
                    $node->getLine(),
                    $this->file ? ' in file: ' . $this->file : ''
                ));
            }
        }

        if (empty($examples)) {
            throw new ParserException(sprintf(
                'Outline should have examples table, but got none for outline "%s" on line: %d%s',
                rtrim($title),
                $line,
                $this->file ? ' in file: ' . $this->file : ''
            ));
        }

        return new OutlineNode(rtrim($title) ?: null, $tags, $steps, $examples, $keyword, $line);
    }

    /**
     * Parses step token & returns it's node.
     *
     * @return StepNode
     */
    protected function parseStep()
    {
        $token = $this->expectTokenType('Step');

        $keyword = $token['value'];
        $keywordType = $token['keyword_type'];
        $text = trim($token['text']);
        $line = $token['line'];

        array_push($this->passedNodesStack, 'Step');

        $arguments = array();
        while (in_array($predicted = $this->predictTokenType(), array('PyStringOp', 'TableRow', 'Newline', 'Comment'))) {
            if ('Comment' === $predicted || 'Newline' === $predicted) {
                $this->acceptTokenType($predicted);
                continue;
            }

            $node = $this->parseExpression();

            if ($node instanceof PyStringNode || $node instanceof TableNode) {
                $arguments[] = $node;
            }
        }

        array_pop($this->passedNodesStack);

        return new StepNode($keyword, $text, $arguments, $line, $keywordType);
    }

    /**
     * Parses examples table node.
     *
     * @return ExampleTableNode
     */
    protected function parseExamples()
    {
        $keyword = ($this->expectTokenType('Examples'))['keyword'];
        $tags = empty($this->tags) ? array() : $this->popTags();
        $table = $this->parseTableRows();

        try {
            return new ExampleTableNode($table, $keyword, $tags);
        } catch(NodeException $e) {
            $this->rethrowNodeException($e);
        }
    }

    /**
     * Parses table token & returns it's node.
     *
     * @return TableNode
     */
    protected function parseTable()
    {
        $table = $this->parseTableRows();

        try {
            return new TableNode($table);
        } catch(NodeException $e) {
            $this->rethrowNodeException($e);
        }
    }

    /**
     * Parses PyString token & returns it's node.
     *
     * @return PyStringNode
     */
    protected function parsePyString()
    {
        $token = $this->expectTokenType('PyStringOp');

        $line = $token['line'];

        $strings = array();
        while ('PyStringOp' !== ($predicted = $this->predictTokenType()) && 'Text' === $predicted) {
            $token = $this->expectTokenType('Text');

            $strings[] = $token['value'];
        }

        $this->expectTokenType('PyStringOp');

        return new PyStringNode($strings, $line);
    }

    /**
     * Parses tags.
     *
     * @return BackgroundNode|FeatureNode|OutlineNode|ScenarioNode|StepNode|TableNode|string
     */
    protected function parseTags()
    {
        $token = $this->expectTokenType('Tag');

        $this->guardTags($token['tags']);

        $this->tags = array_merge($this->tags, $token['tags']);

        $possibleTransitions = array(
            'Outline' => array(
                'Examples',
                'Step'
            )
        );

        $currentType = '-1';
        // check if that is ok to go inside:
        if (!empty($this->passedNodesStack)) {
            $currentType = $this->passedNodesStack[count($this->passedNodesStack) - 1];
        }

        $nextType = $this->predictTokenType();
        if (!isset($possibleTransitions[$currentType]) || in_array($nextType, $possibleTransitions[$currentType])) {
            return $this->parseExpression();
        }

        return "\n";
    }

    /**
     * Returns current set of tags and clears tag buffer.
     *
     * @return array
     */
    protected function popTags()
    {
        $tags = $this->tags;
        $this->tags = array();

        return $tags;
    }

    /**
     * Checks the tags fit the required format
     *
     * @param string[] $tags
     */
    protected function guardTags(array $tags)
    {
        foreach ($tags as $tag) {
            if (preg_match('/\s/', $tag)) {
                trigger_error('Whitespace in tags is deprecated, found "$tag"', E_USER_DEPRECATED);
            }
        }
    }

    /**
     * Parses next text line & returns it.
     *
     * @return string
     */
    protected function parseText()
    {
        $token = $this->expectTokenType('Text');

        return $token['value'];
    }

    /**
     * Parses next newline & returns \n.
     *
     * @return string
     */
    protected function parseNewline()
    {
        $this->expectTokenType('Newline');

        return "\n";
    }

    /**
     * Parses language block and updates lexer configuration based on it.
     *
     * @return BackgroundNode|FeatureNode|OutlineNode|ScenarioNode|StepNode|TableNode|string
     *
     * @throws ParserException
     */
    protected function parseLanguage()
    {
        $token = $this->expectTokenType('Language');

        if (null === $this->languageSpecifierLine) {
            $this->lexer->analyse($this->input, $token['value']);
            $this->languageSpecifierLine = $token['line'];
        } elseif ($token['line'] !== $this->languageSpecifierLine) {
            throw new ParserException(sprintf(
                'Ambiguous language specifiers on lines: %d and %d%s',
                $this->languageSpecifierLine,
                $token['line'],
                $this->file ? ' in file: ' . $this->file : ''
            ));
        }

        return $this->parseExpression();
    }

    /**
     * Parses the rows of a table
     *
     * @return string[][]
     */
    private function parseTableRows()
    {
        $table = array();
        while (in_array($predicted = $this->predictTokenType(), array('TableRow', 'Newline', 'Comment'))) {
            if ('Comment' === $predicted || 'Newline' === $predicted) {
                $this->acceptTokenType($predicted);
                continue;
            }

            $token = $this->expectTokenType('TableRow');

            $table[$token['line']] = $token['columns'];
        }

        return $table;
    }

    /**
     * Changes step node type for types But, And to type of previous step if it exists else sets to Given
     *
     * @param StepNode   $node
     * @param StepNode[] $steps
     * @return StepNode
     */
    private function normalizeStepNodeKeywordType(StepNode $node, array $steps = array())
    {
        if (in_array($node->getKeywordType(), array('And', 'But'))) {
            if (($prev = end($steps))) {
                $keywordType = $prev->getKeywordType();
            } else {
                $keywordType = 'Given';
            }

            $node = new StepNode(
                $node->getKeyword(),
                $node->getText(),
                $node->getArguments(),
                $node->getLine(),
                $keywordType
            );
        }
        return $node;
    }

    private function rethrowNodeException(NodeException $e): void
    {
        throw new ParserException(
            $e->getMessage() . ($this->file ? ' in file ' . $this->file : ''),
            0,
            $e
        );
    }
}
