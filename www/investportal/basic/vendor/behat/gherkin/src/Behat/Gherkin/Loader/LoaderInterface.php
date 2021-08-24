<?php

/*
 * This file is part of the Behat Gherkin.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Gherkin\Loader;

use Behat\Gherkin\Node\FeatureNode;

/**
 * Loader interface.
 *
 * @author      Konstantin Kudryashov <ever.zet@gmail.com>
 */
interface LoaderInterface
{
    /**
     * Checks if current loader supports provided resource.
     *
     * @param mixed $resource Resource to load
     *
     * @return Boolean
     */
    public function supports($resource);

    /**
     * Loads features from provided resource.
     *
     * @param mixed $resource Resource to load
     *
     * @return FeatureNode[]
     */
    public function load($resource);
}
