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
 * Represents Gherkin PyString argument.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class PyStringNode implements ArgumentInterface
{
    /**
     * @var array
     */
    private $strings = array();
    /**
     * @var integer
     */
    private $line;

    /**
     * Initializes PyString.
     *
     * @param array   $strings String in form of [$stringLine]
     * @param integer $line    Line number where string been started
     */
    public function __construct(array $strings, $line)
    {
        $this->strings = $strings;
        $this->line = $line;
    }

    /**
     * Returns node type.
     *
     * @return string
     */
    public function getNodeType()
    {
        return 'PyString';
    }

    /**
     * Returns entire PyString lines set.
     *
     * @return array
     */
    public function getStrings()
    {
        return $this->strings;
    }

    /**
     * Returns raw string.
     *
     * @return string
     */
    public function getRaw()
    {
        return implode("\n", $this->strings);
    }

    /**
     * Converts PyString into string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getRaw();
    }

    /**
     * Returns line number at which PyString was started.
     *
     * @return integer
     */
    public function getLine()
    {
        return $this->line;
    }
}
