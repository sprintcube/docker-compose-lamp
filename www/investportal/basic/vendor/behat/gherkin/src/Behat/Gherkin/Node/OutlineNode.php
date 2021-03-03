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
     * @var ExampleTableNode
     */
    private $table;
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
     * @param ExampleTableNode $table
     * @param string           $keyword
     * @param integer          $line
     */
    public function __construct(
        $title,
        array $tags,
        array $steps,
        ExampleTableNode $table,
        $keyword,
        $line
    ) {
        $this->title = $title;
        $this->tags = $tags;
        $this->steps = $steps;
        $this->table = $table;
        $this->keyword = $keyword;
        $this->line = $line;
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
     * @return Boolean
     */
    public function hasTag($tag)
    {
        return in_array($tag, $this->getTags());
    }

    /**
     * Checks if outline has tags (both inherited from feature and own).
     *
     * @return Boolean
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
     * @return Boolean
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
     * @return Boolean
     */
    public function hasExamples()
    {
        return 0 < count($this->table->getColumnsHash());
    }

    /**
     * Returns examples table.
     *
     * @return ExampleTableNode
     */
    public function getExampleTable()
    {
        return $this->table;
    }

    /**
     * Returns list of examples for the outline.
     *
     * @return ExampleNode[]
     */
    public function getExamples()
    {
        return $this->examples = $this->examples ? : $this->createExamples();
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
        foreach ($this->table->getColumnsHash() as $rowNum => $row) {
            $examples[] = new ExampleNode(
                $this->table->getRowAsString($rowNum + 1),
                $this->tags,
                $this->getSteps(),
                $row,
                $this->table->getRowLine($rowNum + 1),
                $this->getTitle()
            );
        }

        return $examples;
    }
}
