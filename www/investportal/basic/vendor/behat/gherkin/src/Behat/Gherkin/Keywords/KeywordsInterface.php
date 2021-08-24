<?php

/*
 * This file is part of the Behat Gherkin.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Gherkin\Keywords;

/**
 * Keywords holder interface.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
interface KeywordsInterface
{
    /**
     * Sets keywords holder language.
     *
     * @param string $language Language name
     */
    public function setLanguage($language);

    /**
     * Returns Feature keywords (splitted by "|").
     *
     * @return string
     */
    public function getFeatureKeywords();

    /**
     * Returns Background keywords (splitted by "|").
     *
     * @return string
     */
    public function getBackgroundKeywords();

    /**
     * Returns Scenario keywords (splitted by "|").
     *
     * @return string
     */
    public function getScenarioKeywords();

    /**
     * Returns Scenario Outline keywords (splitted by "|").
     *
     * @return string
     */
    public function getOutlineKeywords();

    /**
     * Returns Examples keywords (splitted by "|").
     *
     * @return string
     */
    public function getExamplesKeywords();

    /**
     * Returns Given keywords (splitted by "|").
     *
     * @return string
     */
    public function getGivenKeywords();

    /**
     * Returns When keywords (splitted by "|").
     *
     * @return string
     */
    public function getWhenKeywords();

    /**
     * Returns Then keywords (splitted by "|").
     *
     * @return string
     */
    public function getThenKeywords();

    /**
     * Returns And keywords (splitted by "|").
     *
     * @return string
     */
    public function getAndKeywords();

    /**
     * Returns But keywords (splitted by "|").
     *
     * @return string
     */
    public function getButKeywords();

    /**
     * Returns all step keywords (splitted by "|").
     *
     * @return string
     */
    public function getStepKeywords();
}
