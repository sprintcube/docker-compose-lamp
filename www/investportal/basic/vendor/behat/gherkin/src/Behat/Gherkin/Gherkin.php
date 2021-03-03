<?php

/*
 * This file is part of the Behat Gherkin.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Gherkin;

use Behat\Gherkin\Filter\FeatureFilterInterface;
use Behat\Gherkin\Filter\LineFilter;
use Behat\Gherkin\Filter\LineRangeFilter;
use Behat\Gherkin\Loader\FileLoaderInterface;
use Behat\Gherkin\Loader\LoaderInterface;

/**
 * Gherkin manager.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Gherkin
{
    const VERSION = '4.6.2';

    /**
     * @var LoaderInterface[]
     */
    protected $loaders = array();
    /**
     * @var FeatureFilterInterface[]
     */
    protected $filters = array();

    /**
     * Adds loader to manager.
     *
     * @param LoaderInterface $loader Feature loader
     */
    public function addLoader(LoaderInterface $loader)
    {
        $this->loaders[] = $loader;
    }

    /**
     * Adds filter to manager.
     *
     * @param FeatureFilterInterface $filter Feature filter
     */
    public function addFilter(FeatureFilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Sets filters to the parser.
     *
     * @param FeatureFilterInterface[] $filters
     */
    public function setFilters(array $filters)
    {
        $this->filters = array();
        array_map(array($this, 'addFilter'), $filters);
    }

    /**
     * Sets base features path.
     *
     * @param string $path Loaders base path
     */
    public function setBasePath($path)
    {
        foreach ($this->loaders as $loader) {
            if ($loader instanceof FileLoaderInterface) {
                $loader->setBasePath($path);
            }
        }
    }

    /**
     * Loads & filters resource with added loaders.
     *
     * @param mixed                    $resource Resource to load
     * @param FeatureFilterInterface[] $filters  Additional filters
     *
     * @return array
     */
    public function load($resource, array $filters = array())
    {
        $filters = array_merge($this->filters, $filters);

        $matches = array();
        if (preg_match('/^(.*)\:(\d+)-(\d+|\*)$/', $resource, $matches)) {
            $resource = $matches[1];
            $filters[] = new LineRangeFilter($matches[2], $matches[3]);
        } elseif (preg_match('/^(.*)\:(\d+)$/', $resource, $matches)) {
            $resource = $matches[1];
            $filters[] = new LineFilter($matches[2]);
        }

        $loader = $this->resolveLoader($resource);

        if (null === $loader) {
            return array();
        }

        $features = array();
        foreach ($loader->load($resource) as $feature) {
            foreach ($filters as $filter) {
                $feature = $filter->filterFeature($feature);

                if (!$feature->hasScenarios() && !$filter->isFeatureMatch($feature)) {
                    continue 2;
                }
            }

            $features[] = $feature;
        }

        return $features;
    }

    /**
     * Resolves loader by resource.
     *
     * @param mixed $resource Resource to load
     *
     * @return LoaderInterface
     */
    public function resolveLoader($resource)
    {
        foreach ($this->loaders as $loader) {
            if ($loader->supports($resource)) {
                return $loader;
            }
        }

        return null;
    }
}
