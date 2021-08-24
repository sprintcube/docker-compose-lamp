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
 * Represents Gherkin Outline Example Table.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class ExampleTableNode extends TableNode
{
    /**
     * @var string
     */
    private $keyword;

    /**
     * Initializes example table.
     *
     * @param array  $table   Table in form of [$rowLineNumber => [$val1, $val2, $val3]]
     * @param string $keyword
     */
    public function __construct(array $table, $keyword)
    {
        $this->keyword = $keyword;

        parent::__construct($table);
    }

    /**
     * Returns node type string
     *
     * @return string
     */
    public function getNodeType()
    {
        return 'ExampleTable';
    }

    /**
     * Returns example table keyword.
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }
}
