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
 * Represents Gherkin Background.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class BackgroundNode implements ScenarioLikeInterface
{
    /**
     * @var string
     */
    private $title;
    /**
     * @var StepNode[]
     */
    private $steps = array();
    /**
     * @var string
     */
    private $keyword;
    /**
     * @var integer
     */
    private $line;

    /**
     * Initializes background.
     *
     * @param null|string $title
     * @param StepNode[]  $steps
     * @param string      $keyword
     * @param integer     $line
     */
    public function __construct($title, array $steps, $keyword, $line)
    {
        $this->title = $title;
        $this->steps = $steps;
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
        return 'Background';
    }

    /**
     * Returns background title.
     *
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Checks if background has steps.
     *
     * @return bool
     */
    public function hasSteps()
    {
        return 0 < count($this->steps);
    }

    /**
     * Returns background steps.
     *
     * @return StepNode[]
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * Returns background keyword.
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Returns background declaration line number.
     *
     * @return integer
     */
    public function getLine()
    {
        return $this->line;
    }
}
