<?php
require_once __DIR__ .'/ResetMocks.php';
use Codeception\Stub;

class StubTest extends \PHPUnit\Framework\TestCase
{
    use ResetMocks;
    /**
     * @var DummyClass
     */
    protected $dummy;

    public function setUp(): void
    {
        require_once $file = __DIR__. '/_data/DummyOverloadableClass.php';
        require_once $file = __DIR__. '/_data/DummyClass.php';
        $this->dummy = new DummyClass(true);
    }

    public function testMakeEmpty()
    {
        $dummy = Stub::makeEmpty('DummyClass');
        $this->assertInstanceOf('DummyClass', $dummy);
        $this->assertTrue(method_exists($dummy, 'helloWorld'));
        $this->assertNull($dummy->helloWorld());
    }

    public function testMakeEmptyMethodReplaced()
    {
        $dummy = Stub::makeEmpty('DummyClass', array('helloWorld' => function () {
            return 'good bye world';
        }));
        $this->assertMethodReplaced($dummy);
    }

    public function testMakeEmptyMethodSimplyReplaced()
    {
        $dummy = Stub::makeEmpty('DummyClass', array('helloWorld' => 'good bye world'));
        $this->assertMethodReplaced($dummy);
    }

    public function testMakeEmptyExcept()
    {
        $dummy = Stub::makeEmptyExcept('DummyClass', 'helloWorld');
        $this->assertEquals($this->dummy->helloWorld(), $dummy->helloWorld());
        $this->assertNull($dummy->goodByeWorld());
    }

    public function testMakeEmptyExceptPropertyReplaced()
    {
        $dummy = Stub::makeEmptyExcept('DummyClass', 'getCheckMe', array('checkMe' => 'checked!'));
        $this->assertEquals('checked!', $dummy->getCheckMe());
    }

    public function testMakeEmptyExceptMagicalPropertyReplaced()
    {
        $dummy = Stub::makeEmptyExcept('DummyClass', 'getCheckMeToo', array('checkMeToo' => 'checked!'));
        $this->assertEquals('checked!', $dummy->getCheckMeToo());
    }

    public function testFactory()
    {
        $dummies = Stub::factory('DummyClass', 2);
        $this->assertCount(2, $dummies);
        $this->assertInstanceOf('DummyClass', $dummies[0]);
    }

    public function testMake()
    {
        $dummy = Stub::make('DummyClass', array('goodByeWorld' => function () {
            return 'hello world';
        }));
        $this->assertEquals($this->dummy->helloWorld(), $dummy->helloWorld());
        $this->assertEquals("hello world", $dummy->goodByeWorld());
    }

    public function testMakeMethodReplaced()
    {
        $dummy = Stub::make('DummyClass', array('helloWorld' => function () {
            return 'good bye world';
        }));
        $this->assertMethodReplaced($dummy);
    }

    public function testMakeWithMagicalPropertiesReplaced()
    {
        $dummy = Stub::make('DummyClass', array('checkMeToo' => 'checked!'));
        $this->assertEquals('checked!', $dummy->checkMeToo);
    }

    public function testMakeMethodSimplyReplaced()
    {
        $dummy = Stub::make('DummyClass', array('helloWorld' => 'good bye world'));
        $this->assertMethodReplaced($dummy);
    }

    public function testCopy()
    {
        $dummy = Stub::copy($this->dummy, array('checkMe' => 'checked!'));
        $this->assertEquals('checked!', $dummy->getCheckMe());
        $dummy = Stub::copy($this->dummy, array('checkMeToo' => 'checked!'));
        $this->assertEquals('checked!', $dummy->getCheckMeToo());
    }

    public function testConstruct()
    {
        $dummy = Stub::construct('DummyClass', array('checkMe' => 'checked!'));
        $this->assertEquals('constructed: checked!', $dummy->getCheckMe());

        $dummy = Stub::construct(
            'DummyClass',
            array('checkMe' => 'checked!'),
            array('targetMethod' => function () {
                return false;
            })
        );
        $this->assertEquals('constructed: checked!', $dummy->getCheckMe());
        $this->assertEquals(false, $dummy->targetMethod());
    }

    public function testConstructMethodReplaced()
    {
        $dummy = Stub::construct(
            'DummyClass',
            array(),
            array('helloWorld' => function () {
                return 'good bye world';
            })
        );
        $this->assertMethodReplaced($dummy);
    }

    public function testConstructMethodSimplyReplaced()
    {
        $dummy = Stub::make('DummyClass', array('helloWorld' => 'good bye world'));
        $this->assertMethodReplaced($dummy);
    }

    public function testConstructEmpty()
    {
        $dummy = Stub::constructEmpty('DummyClass', array('checkMe' => 'checked!'));
        $this->assertNull($dummy->getCheckMe());
    }

    public function testConstructEmptyExcept()
    {
        $dummy = Stub::constructEmptyExcept('DummyClass', 'getCheckMe', array('checkMe' => 'checked!'));
        $this->assertNull($dummy->targetMethod());
        $this->assertEquals('constructed: checked!', $dummy->getCheckMe());
    }

    public function testUpdate()
    {
        $dummy = Stub::construct('DummyClass');
        Stub::update($dummy, array('checkMe' => 'done'));
        $this->assertEquals('done', $dummy->getCheckMe());
        Stub::update($dummy, array('checkMeToo' => 'done'));
        $this->assertEquals('done', $dummy->getCheckMeToo());
    }

    public function testStubsFromObject()
    {
        $dummy = Stub::make(new \DummyClass());
        $this->assertInstanceOf(
            '\PHPUnit\Framework\MockObject\MockObject',
            $dummy
        );
        $dummy = Stub::make(new \DummyOverloadableClass());
        $this->assertObjectHasAttribute('__mocked', $dummy);
        $dummy = Stub::makeEmpty(new \DummyClass());
        $this->assertInstanceOf(
            '\PHPUnit\Framework\MockObject\MockObject',
            $dummy
        );
        $dummy = Stub::makeEmpty(new \DummyOverloadableClass());
        $this->assertObjectHasAttribute('__mocked', $dummy);
        $dummy = Stub::makeEmptyExcept(new \DummyClass(), 'helloWorld');
        $this->assertInstanceOf(
            '\PHPUnit\Framework\MockObject\MockObject',
            $dummy
        );
        $dummy = Stub::makeEmptyExcept(new \DummyOverloadableClass(), 'helloWorld');
        $this->assertObjectHasAttribute('__mocked', $dummy);
        $dummy = Stub::construct(new \DummyClass());
        $this->assertInstanceOf(
            '\PHPUnit\Framework\MockObject\MockObject',
            $dummy
        );
        $dummy = Stub::construct(new \DummyOverloadableClass());
        $this->assertObjectHasAttribute('__mocked', $dummy);
        $dummy = Stub::constructEmpty(new \DummyClass());
        $this->assertInstanceOf(
            '\PHPUnit\Framework\MockObject\MockObject',
            $dummy
        );
        $dummy = Stub::constructEmpty(new \DummyOverloadableClass());
        $this->assertObjectHasAttribute('__mocked', $dummy);
        $dummy = Stub::constructEmptyExcept(new \DummyClass(), 'helloWorld');
        $this->assertInstanceOf(
            '\PHPUnit\Framework\MockObject\MockObject',
            $dummy
        );
        $dummy = Stub::constructEmptyExcept(new \DummyOverloadableClass(), 'helloWorld');
        $this->assertObjectHasAttribute('__mocked', $dummy);
    }

    protected function assertMethodReplaced($dummy)
    {
        $this->assertTrue(method_exists($dummy, 'helloWorld'));
        $this->assertNotEquals($this->dummy->helloWorld(), $dummy->helloWorld());
        $this->assertEquals($dummy->helloWorld(), 'good bye world');
    }

    public static function matcherAndFailMessageProvider()
    {
        return array(
            array(Stub\Expected::atLeastOnce(),
                'Expected invocation at least once but it never'
            ),
            array(Stub\Expected::once(),
                'Method was expected to be called 1 times, actually called 0 times.'
            ),
            array(Stub\Expected::exactly(1),
                'Method was expected to be called 1 times, actually called 0 times.'
            ),
            array(Stub\Expected::exactly(3),
              'Method was expected to be called 3 times, actually called 0 times.'
            ),
        );
    }

    /**
     * @dataProvider matcherAndFailMessageProvider
     */
    public function testExpectedMethodIsCalledFail($stubMarshaler, $failMessage)
    {
        $mock = Stub::makeEmptyExcept('DummyClass', 'call', array('targetMethod' => $stubMarshaler), $this);
        $mock->goodByeWorld();

        try {
            $mock->__phpunit_verify();
            $this->fail('Expected exception');
        } catch (\Exception $e) {
            $this->assertTrue(strpos($failMessage, $e->getMessage()) >= 0, 'String contains');

        }

        $this->resetMockObjects();
    }

    public function testNeverExpectedMethodIsCalledFail()
    {
        $mock = Stub::makeEmptyExcept('DummyClass', 'call', array('targetMethod' => Stub\Expected::never()), $this);
        $mock->goodByeWorld();

        try {
            $mock->call();
        } catch (\Exception $e) {
            $this->assertTrue(strpos('was not expected to be called', $e->getMessage()) >= 0, 'String contains');
        }

        $this->resetMockObjects();
    }

    public static function matcherProvider()
    {
        return array(
            array(0, Stub\Expected::never()),
            array(1, Stub\Expected::once()),
            array(2, Stub\Expected::atLeastOnce()),
            array(3, Stub\Expected::exactly(3)),
            array(1, Stub\Expected::once(function () {
                return true;
            }), true),
            array(2, Stub\Expected::atLeastOnce(function () {
                return array();
            }), array()),
            array(1, Stub\Expected::exactly(1, function () {
                return null;
            }), null),
            array(1, Stub\Expected::exactly(1, function () {
                return 'hello world!';
            }), 'hello world!'),
            array(1, Stub\Expected::exactly(1, 'hello world!'), 'hello world!'),
        );
    }

    /**
     * @dataProvider matcherProvider
     */
    public function testMethodMatcherWithMake($count, $matcher, $expected = false)
    {
        $dummy = Stub::make('DummyClass', array('goodByeWorld' => $matcher), $this);

        $this->repeatCall($count, array($dummy, 'goodByeWorld'), $expected);
    }

    /**
     * @dataProvider matcherProvider
     */
    public function testMethodMatcherWithMakeEmpty($count, $matcher)
    {
        $dummy = Stub::makeEmpty('DummyClass', array('goodByeWorld' => $matcher), $this);

        $this->repeatCall($count, array($dummy, 'goodByeWorld'));
    }

    /**
     * @dataProvider matcherProvider
     */
    public function testMethodMatcherWithMakeEmptyExcept($count, $matcher)
    {
        $dummy = Stub::makeEmptyExcept('DummyClass', 'getCheckMe', array('goodByeWorld' => $matcher), $this);

        $this->repeatCall($count, array($dummy, 'goodByeWorld'));
    }

    /**
     * @dataProvider matcherProvider
     */
    public function testMethodMatcherWithConstruct($count, $matcher)
    {
        $dummy = Stub::construct('DummyClass', array(), array('goodByeWorld' => $matcher), $this);

        $this->repeatCall($count, array($dummy, 'goodByeWorld'));
    }

    /**
     * @dataProvider matcherProvider
     */
    public function testMethodMatcherWithConstructEmpty($count, $matcher)
    {
        $dummy = Stub::constructEmpty('DummyClass', array(), array('goodByeWorld' => $matcher), $this);

        $this->repeatCall($count, array($dummy, 'goodByeWorld'));
    }

    /**
     * @dataProvider matcherProvider
     */
    public function testMethodMatcherWithConstructEmptyExcept($count, $matcher)
    {
        $dummy = Stub::constructEmptyExcept(
            'DummyClass',
            'getCheckMe',
            array(),
            array('goodByeWorld' => $matcher),
            $this
        );

        $this->repeatCall($count, array($dummy, 'goodByeWorld'));
    }

    private function repeatCall($count, $callable, $expected = false)
    {
        for ($i = 0; $i < $count; $i++) {
            $actual = call_user_func($callable);
            if ($expected) {
                $this->assertEquals($expected, $actual);
            }
        }
    }

    public function testConsecutive()
    {
        $dummy = Stub::make('DummyClass', array('helloWorld' => Stub::consecutive('david', 'emma', 'sam', 'amy')));

        $this->assertEquals('david', $dummy->helloWorld());
        $this->assertEquals('emma', $dummy->helloWorld());
        $this->assertEquals('sam', $dummy->helloWorld());
        $this->assertEquals('amy', $dummy->helloWorld());

        // Expected null value when no more values
        $this->assertNull($dummy->helloWorld());
    }

    public function testStubPrivateProperties()
    {
        $tester = Stub::construct(
            'MyClassWithPrivateProperties',
            ['name' => 'gamma'],
            [
                 'randomName' => 'chicken',
                 't' => 'ticky2',
                 'getRandomName' => function () {
                     return "randomstuff";
                 }
            ]
        );
        $this->assertEquals('gamma', $tester->getName());
        $this->assertEquals('randomstuff', $tester->getRandomName());
        $this->assertEquals('ticky2', $tester->getT());
    }

    public function testStubMakeEmptyInterface()
    {
        $stub = Stub::makeEmpty('\Countable', ['count' => 5]);
        $this->assertEquals(5, $stub->count());
    }
}

class MyClassWithPrivateProperties
{

    private $name;
    private $randomName = "gaia";
    private $t          = "ticky";

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRandomName()
    {
        return $this->randomName;
    }

    public function getT()
    {
        return $this->t;
    }
}
