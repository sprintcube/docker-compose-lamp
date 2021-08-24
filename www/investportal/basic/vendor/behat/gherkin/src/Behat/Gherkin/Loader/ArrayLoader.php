<?php

/*
 * This file is part of the Behat Gherkin.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Gherkin\Loader;

use Behat\Gherkin\Node\BackgroundNode;
use Behat\Gherkin\Node\ExampleTableNode;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\OutlineNode;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\ScenarioNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Gherkin\Node\TableNode;

/**
 * From-array loader.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class ArrayLoader implements LoaderInterface
{
    /**
     * Checks if current loader supports provided resource.
     *
     * @param mixed $resource Resource to load
     *
     * @return Boolean
     */
    public function supports($resource)
    {
        return is_array($resource) && (isset($resource['features']) || isset($resource['feature']));
    }

    /**
     * Loads features from provided resource.
     *
     * @param mixed $resource Resource to load
     *
     * @return FeatureNode[]
     */
    public function load($resource)
    {
        $features = array();

        if (isset($resource['features'])) {
            foreach ($resource['features'] as $iterator => $hash) {
                $feature = $this->loadFeatureHash($hash, $iterator);
                $features[] = $feature;
            }
        } elseif (isset($resource['feature'])) {
            $feature = $this->loadFeatureHash($resource['feature']);
            $features[] = $feature;
        }

        return $features;
    }

    /**
     * Loads feature from provided feature hash.
     *
     * @param array   $hash Feature hash
     * @param integer $line
     *
     * @return FeatureNode
     */
    protected function loadFeatureHash(array $hash, $line = 0)
    {
        $hash = array_merge(
            array(
                'title' => null,
                'description' => null,
                'tags' => array(),
                'keyword' => 'Feature',
                'language' => 'en',
                'line' => $line,
                'scenarios' => array(),
            ),
            $hash
        );
        $background = isset($hash['background']) ? $this->loadBackgroundHash($hash['background']) : null;

        $scenarios = array();
        foreach ((array) $hash['scenarios'] as $scenarioIterator => $scenarioHash) {
            if (isset($scenarioHash['type']) && 'outline' === $scenarioHash['type']) {
                $scenarios[] = $this->loadOutlineHash($scenarioHash, $scenarioIterator);
            } else {
                $scenarios[] = $this->loadScenarioHash($scenarioHash, $scenarioIterator);
            }
        }

        return new FeatureNode($hash['title'], $hash['description'], $hash['tags'], $background, $scenarios, $hash['keyword'], $hash['language'], null, $hash['line']);
    }

    /**
     * Loads background from provided hash.
     *
     * @param array $hash Background hash
     *
     * @return BackgroundNode
     */
    protected function loadBackgroundHash(array $hash)
    {
        $hash = array_merge(
            array(
                'title' => null,
                'keyword' => 'Background',
                'line' => 0,
                'steps' => array(),
            ),
            $hash
        );

        $steps = $this->loadStepsHash($hash['steps']);

        return new BackgroundNode($hash['title'], $steps, $hash['keyword'], $hash['line']);
    }

    /**
     * Loads scenario from provided scenario hash.
     *
     * @param array   $hash Scenario hash
     * @param integer $line Scenario definition line
     *
     * @return ScenarioNode
     */
    protected function loadScenarioHash(array $hash, $line = 0)
    {
        $hash = array_merge(
            array(
                'title' => null,
                'tags' => array(),
                'keyword' => 'Scenario',
                'line' => $line,
                'steps' => array(),
            ),
            $hash
        );

        $steps = $this->loadStepsHash($hash['steps']);

        return new ScenarioNode($hash['title'], $hash['tags'], $steps, $hash['keyword'], $hash['line']);
    }

    /**
     * Loads outline from provided outline hash.
     *
     * @param array   $hash Outline hash
     * @param integer $line Outline definition line
     *
     * @return OutlineNode
     */
    protected function loadOutlineHash(array $hash, $line = 0)
    {
        $hash = array_merge(
            array(
                'title' => null,
                'tags' => array(),
                'keyword' => 'Scenario Outline',
                'line' => $line,
                'steps' => array(),
                'examples' => array(),
            ),
            $hash
        );

        $steps = $this->loadStepsHash($hash['steps']);

        if (isset($hash['examples']['keyword'])) {
            $examplesKeyword = $hash['examples']['keyword'];
            unset($hash['examples']['keyword']);
        } else {
            $examplesKeyword = 'Examples';
        }

        $examples = new ExampleTableNode($hash['examples'], $examplesKeyword);

        return new OutlineNode($hash['title'], $hash['tags'], $steps, $examples, $hash['keyword'], $hash['line']);
    }

    /**
     * Loads steps from provided hash.
     *
     * @param array $hash
     *
     * @return StepNode[]
     */
    private function loadStepsHash(array $hash)
    {
        $steps = array();
        foreach ($hash as $stepIterator => $stepHash) {
            $steps[] = $this->loadStepHash($stepHash, $stepIterator);
        }

        return $steps;
    }

    /**
     * Loads step from provided hash.
     *
     * @param array   $hash Step hash
     * @param integer $line Step definition line
     *
     * @return StepNode
     */
    protected function loadStepHash(array $hash, $line = 0)
    {
        $hash = array_merge(
            array(
                'keyword_type' => 'Given',
                'type' => 'Given',
                'text' => null,
                'keyword' => 'Scenario',
                'line' => $line,
                'arguments' => array(),
            ),
            $hash
        );

        $arguments = array();
        foreach ($hash['arguments'] as $argumentHash) {
            if ('table' === $argumentHash['type']) {
                $arguments[] = $this->loadTableHash($argumentHash['rows']);
            } elseif ('pystring' === $argumentHash['type']) {
                $arguments[] = $this->loadPyStringHash($argumentHash, $hash['line'] + 1);
            }
        }

        return new StepNode($hash['type'], $hash['text'], $arguments, $hash['line'], $hash['keyword_type']);
    }

    /**
     * Loads table from provided hash.
     *
     * @param array $hash Table hash
     *
     * @return TableNode
     */
    protected function loadTableHash(array $hash)
    {
        return new TableNode($hash);
    }

    /**
     * Loads PyString from provided hash.
     *
     * @param array   $hash PyString hash
     * @param integer $line
     *
     * @return PyStringNode
     */
    protected function loadPyStringHash(array $hash, $line = 0)
    {
        $line = isset($hash['line']) ? $hash['line'] : $line;

        $strings = array();
        foreach (explode("\n", $hash['text']) as $string) {
            $strings[] = $string;
        }

        return new PyStringNode($strings, $line);
    }
}
