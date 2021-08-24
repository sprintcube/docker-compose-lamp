<?php

namespace Tests\Behat\Gherkin\Node;

use Behat\Gherkin\Node\ExampleTableNode;
use Behat\Gherkin\Node\OutlineNode;
use Behat\Gherkin\Node\StepNode;

class OutlineNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatesExamplesForExampleTable()
    {
        $steps = array(
            new StepNode('Gangway!', 'I am <name>', array(), null, 'Given'),
            new StepNode('Aye!', 'my email is <email>', array(), null, 'And'),
            new StepNode('Blimey!', 'I open homepage', array(), null, 'When'),
            new StepNode('Let go and haul',  'website should recognise me', array(), null, 'Then'),
        );

        $table = new ExampleTableNode(array(
            array('name', 'email'),
            array('everzet', 'ever.zet@gmail.com'),
            array('example', 'example@example.com')
        ), 'Examples');

        $outline = new OutlineNode(null, array(), $steps, $table, null, null);

        $this->assertCount(2, $examples = $outline->getExamples());
        $this->assertEquals(1, $examples[0]->getLine());
        $this->assertEquals(2, $examples[1]->getLine());
        $this->assertEquals(array('name' => 'everzet', 'email' => 'ever.zet@gmail.com'), $examples[0]->getTokens());
        $this->assertEquals(array('name'  => 'example', 'email' => 'example@example.com'), $examples[1]->getTokens());
    }

    public function testCreatesEmptyExamplesForEmptyExampleTable()
    {
        $steps = array(
            new StepNode('Gangway!', 'I am <name>', array(), null, 'Given'),
            new StepNode('Aye!', 'my email is <email>', array(), null, 'And'),
            new StepNode('Blimey!', 'I open homepage', array(), null, 'When'),
            new StepNode('Let go and haul',  'website should recognise me', array(), null, 'Then'),
        );

        $table = new ExampleTableNode(array(
            array('name', 'email')
        ), 'Examples');

        $outline = new OutlineNode(null, array(), $steps, $table, null, null);

        $this->assertCount(0, $examples = $outline->getExamples());
    }

    public function testCreatesEmptyExamplesForNoExampleTable()
    {
        $steps = array(
            new StepNode('Gangway!', 'I am <name>', array(), null, 'Given'),
            new StepNode('Aye!', 'my email is <email>', array(), null, 'And'),
            new StepNode('Blimey!', 'I open homepage', array(), null, 'When'),
            new StepNode('Let go and haul',  'website should recognise me', array(), null, 'Then'),
        );

        $table = new ExampleTableNode(array(), 'Examples');

        $outline = new OutlineNode(null, array(), $steps, $table, null, null);

        $this->assertCount(0, $examples = $outline->getExamples());
    }
}
