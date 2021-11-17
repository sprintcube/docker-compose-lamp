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
 * Gherkin step container interface.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
interface StepContainerInterface extends NodeInterface
{
    /**
     * Checks if container has steps.
     *
     * @return bool
     */
    public function hasSteps();

    /**
     * Returns container steps.
     *
     * @return StepNode[]
     */
    public function getSteps();
}
