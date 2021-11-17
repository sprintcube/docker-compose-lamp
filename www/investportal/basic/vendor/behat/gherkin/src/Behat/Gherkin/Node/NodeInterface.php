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
 * Gherkin node interface.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
interface NodeInterface
{
    /**
     * Returns node type string
     *
     * @return string
     */
    public function getNodeType();

    /**
     * Returns feature declaration line number.
     *
     * @return integer
     */
    public function getLine();
}
