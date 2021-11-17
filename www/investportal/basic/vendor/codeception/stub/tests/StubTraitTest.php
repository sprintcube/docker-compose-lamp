<?php
require_once __DIR__ .'/ResetMocks.php';

class StubTraitTest extends \PHPUnit\Framework\TestCase
{
    use ResetMocks;
    use \Codeception\Test\Feature\Stub;
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

    public function testMakeStubs()
    {
        $this->dummy = $this->make('DummyClass', ['helloWorld' => 'bye']);
        $this->assertEquals('bye', $this->dummy->helloWorld());
        $this->assertEquals('good bye', $this->dummy->goodByeWorld());

        $this->dummy = $this->makeEmpty('DummyClass', ['helloWorld' => 'bye']);
        $this->assertEquals('bye', $this->dummy->helloWorld());
        $this->assertNull($this->dummy->goodByeWorld());

        $this->dummy = $this->makeEmptyExcept('DummyClass', 'goodByeWorld', ['helloWorld' => 'bye']);
        $this->assertEquals('bye', $this->dummy->helloWorld());
        $this->assertEquals('good bye', $this->dummy->goodByeWorld());
        $this->assertNull($this->dummy->exceptionalMethod());
    }

    public function testConstructStubs()
    {
        $this->dummy = $this->construct('DummyClass', ['!'], ['helloWorld' => 'bye']);
        $this->assertEquals('constructed: !', $this->dummy->getCheckMe());
        $this->assertEquals('bye', $this->dummy->helloWorld());
        $this->assertEquals('good bye', $this->dummy->goodByeWorld());

        $this->dummy = $this->constructEmpty('DummyClass', ['!'], ['helloWorld' => 'bye']);
        $this->assertNull($this->dummy->getCheckMe());
        $this->assertEquals('bye', $this->dummy->helloWorld());
        $this->assertNull($this->dummy->goodByeWorld());

        $this->dummy = $this->constructEmptyExcept('DummyClass', 'getCheckMe', ['!'], ['helloWorld' => 'bye']);
        $this->assertEquals('constructed: !', $this->dummy->getCheckMe());
        $this->assertEquals('bye', $this->dummy->helloWorld());
        $this->assertNull($this->dummy->goodByeWorld());
        $this->assertNull($this->dummy->exceptionalMethod());
    }

    public function testMakeMocks()
    {
        $this->dummy = $this->make('DummyClass', [
            'helloWorld' => \Codeception\Stub\Expected::once()
        ]);
        $this->dummy->helloWorld();
        try {
            $this->dummy->helloWorld();
        } catch (Exception $e) {
            $this->assertTrue(strpos('was not expected to be called more than once', $e->getMessage()) >= 0, 'String contains');
            $this->resetMockObjects();
            return;
        }
        $this->fail('No exception thrown');
    }
}
