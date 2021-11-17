<?php

/*
 * This file is part of the Behat Gherkin.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Gherkin\Node;

/**
 * Represents Gherkin Outline.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class OutlineNode implements ScenarioInterface
{
    /**
     * @var string
     */
    private $title;
    /**
     * @var string[]
     */
    private $tags;
    /**
     * @var StepNode[]
     */
    private $steps;
    /**
     * @var ExampleTableNode|ExampleTableNode[]
     */
    private $tables;
    /**
     * @var string
     */
    private $keyword;
    /**
     * @var integer
     */
    private $line;
    /**
     * @var null|ExampleNode[]
     */
    private $examples;

    /**
     * Initializes outline.
     *
     * @param null|string      $title
     * @param string[]         $tags
     * @param StepNode[]       $steps
     * @param ExampleTableNode|ExampleTableNode[]  $tables
     * @param string           $keyword
     * @param integer          $line
     */
    public function __construct(
        $title,
        array $tags,
        array $steps,
        $tables,
        $keyword,
        $line
    ) {
        $this->title = $title;
        $this->tags = $tags;
        $this->steps = $steps;
        $this->keyword = $keyword;
        $this->line = $line;
        if (!is_array($tables)) {
           $this->tables = array($tables);
        } else {
            $this->tables = $tables;
        }
    }

    /**
     * Returns node type string
     *
     * @return string
     */
    public function getNodeType()
    {
        return 'Outline';
    }

    /**
     * Returns outline title.
     *
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Checks if outline is tagged with tag.
     *
     * @param string $tag
     *
     * @return bool
     */
    public function hasTag($tag)
    {
        return in_array($tag, $this->getTags());
    }

    /**
     * Checks if outline has tags (both inherited from feature and own).
     *
     * @return bool
     */
    public function hasTags()
    {
        return 0 < count($this->getTags());
    }

    /**
     * Returns outline tags (including inherited from feature).
     *
     * @return string[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Checks if outline has steps.
     *
     * @return bool
     */
    public function hasSteps()
    {
        return 0 < count($this->steps);
    }

    /**
     * Returns outline steps.
     *
     * @return StepNode[]
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * Checks if outline has examples.
     *
     * @return bool
     */
    public function hasExamples()
    {
        return 0 < count($this->tables);
    }

    /**
     * Builds and returns examples table for the outline.
     *
     * WARNING: it returns a merged table with tags lost.
     *
     * @deprecated use getExampleTables instead
     * @return ExampleTableNode
     */
    public function getExampleTable()
    {
        $table = array();
        foreach ($this->tables[0]->getTable() as $k => $v) {
            $table[$k] = $v;
        }

        /** @var ExampleTableNode $exampleTableNode */
        $exampleTableNode = new ExampleTableNode($table, $this->tables[0]->getKeyword());
        for ($i = 1; $i < count($this->tables); $i++) {
            $exampleTableNode->mergeRowsFromTable($this->tables[$i]);
        }
        return $exampleTableNode;
    }

    /**
     * Returns list of examples for the outline.
     * @return ExampleNode[]
     */
    public function getExamples()
    {
        return $this->examples = $this->examples ?: $this->createExamples();
    }

    /**
     * Returns examples tables array for the outline.
     * @return ExampleTableNode[]
     */
    public function getExampleTables()
    {
        return $this->tables;
    }

    /**
     * Returns outline keyword.
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Returns outline declaration line number.
     *
     * @return integer
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Creates examples for this outline using examples table.
     *
     * @return ExampleNode[]
     */
    protected function createExamples()
    {
        $examples = array();

        foreach ($this->getExampleTables() as $exampleTable) {
            foreach ($exampleTable->getColumnsHash() as $rowNum => $row) {
                $examples[] = new ExampleNode(
                    $exampleTable->getRowAsString($rowNum + 1),
                    array_merge($this->tags, $exampleTable->getTags()),
                    $this->getSteps(),
                    $row,
                    $exampleTable->getRowLine($rowNum + 1),
                    $this->getTitle()
                );
            }
        }

        return $examples;
    }
}
