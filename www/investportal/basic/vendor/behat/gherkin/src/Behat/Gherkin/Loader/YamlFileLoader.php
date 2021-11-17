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
use Symfony\Component\Yaml\Yaml;

/**
 * Yaml files loader.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class YamlFileLoader extends AbstractFileLoader
{
    private $loader;

    public function __construct()
    {
        $this->loader = new ArrayLoader();
    }

    /**
     * Checks if current loader supports provided resource.
     *
     * @param mixed $path Resource to load
     *
     * @return bool
     */
    public function supports($path)
    {
        return is_string($path)
            && is_file($absolute = $this->findAbsolutePath($path))
            && 'yml' === pathinfo($absolute, PATHINFO_EXTENSION);
    }

    /**
     * Loads features from provided resource.
     *
     * @param string $path Resource to load
     *
     * @return FeatureNode[]
     */
    public function load($path)
    {
        $path = $this->findAbsolutePath($path);
        $hash = Yaml::parse(file_get_contents($path));

        $features = $this->loader->load($hash);

        return array_map(function (FeatureNode $feature) use ($path) {
            return new FeatureNode(
                $feature->getTitle(),
                $feature->getDescription(),
                $feature->getTags(),
                $feature->getBackground(),
                $feature->getScenarios(),
                $feature->getKeyword(),
                $feature->getLanguage(),
                $path,
                $feature->getLine()
            );
        }, $features);
    }
}
