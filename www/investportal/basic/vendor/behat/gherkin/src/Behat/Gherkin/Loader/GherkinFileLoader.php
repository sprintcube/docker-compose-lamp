<?php

/*
 * This file is part of the Behat Gherkin.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Gherkin\Loader;

use Behat\Gherkin\Cache\CacheInterface;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Parser;

/**
 * Gherkin *.feature files loader.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class GherkinFileLoader extends AbstractFileLoader
{
    protected $parser;
    protected $cache;

    /**
     * Initializes loader.
     *
     * @param Parser         $parser Parser
     * @param CacheInterface $cache  Cache layer
     */
    public function __construct(Parser $parser, CacheInterface $cache = null)
    {
        $this->parser = $parser;
        $this->cache = $cache;
    }

    /**
     * Sets cache layer.
     *
     * @param CacheInterface $cache Cache layer
     */
    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Checks if current loader supports provided resource.
     *
     * @param mixed $path Resource to load
     *
     * @return Boolean
     */
    public function supports($path)
    {
        return is_string($path)
        && is_file($absolute = $this->findAbsolutePath($path))
        && 'feature' === pathinfo($absolute, PATHINFO_EXTENSION);
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

        if ($this->cache) {
            if ($this->cache->isFresh($path, filemtime($path))) {
                $feature = $this->cache->read($path);
            } elseif (null !== $feature = $this->parseFeature($path)) {
                $this->cache->write($path, $feature);
            }
        } else {
            $feature = $this->parseFeature($path);
        }

        return null !== $feature ? array($feature) : array();
    }

    /**
     * Parses feature at provided absolute path.
     *
     * @param string $path Feature path
     *
     * @return FeatureNode
     */
    protected function parseFeature($path)
    {
        $content = file_get_contents($path);
        $feature = $this->parser->parse($content, $path);

        return $feature;
    }
}
