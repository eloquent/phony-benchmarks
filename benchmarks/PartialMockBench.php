<?php

declare(strict_types=1);

namespace Eloquent\Phony\Benchmarks;

use Eloquent\Phony;
use Mockery;
use Phake;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_Generator;
use stdClass;

/**
 * @BeforeMethods({"init"})
 * @Revs(1000)
 * @Iterations(10)
 */
class PartialMockBench
{
    public function init()
    {
        $this->className = TypicalClass::class;
        class_exists($this->className);

        $this->phpunit = new PHPUnit_Framework_MockObject_Generator();

        Mockery::mock(stdClass::class);
        Phake::mock(stdClass::class);
        Phony\mock(stdClass::class);
        $mock = $this->phpunit->getMock(stdClass::class);
    }

    public function benchMockery()
    {
        $mock = Mockery::mock($this->className)->makePartial();
        $mock->shouldReceive('testClassAMethodA')->with('a', 'b')->andReturn('c');
        $mock->testClassAMethodA('a', 'b');
    }

    public function benchPhake()
    {
        $mock = Phake::partialMock($this->className);
        Phake::when($mock)->testClassAMethodA('a', 'b')->thenReturn('c');
        $mock->testClassAMethodA('a', 'b');
        Phake::verify($mock)->testClassAMethodA('a', 'b');
    }

    public function benchPhony()
    {
        $handle = Phony\partialMock($this->className);
        $handle->testClassAMethodA->with('a', 'b')->returns('c');
        $handle->get()->testClassAMethodA('a', 'b');
        $handle->testClassAMethodA->calledWith('a', 'b');
    }

    public function benchPhpunit()
    {
        $mock = $this->phpunit->getMock($this->className, ['testClassAMethodA']);
        $mock->expects(TestCase::once())->method('testClassAMethodA')->with('a', 'b')->willReturn('c');
        $mock->testClassAMethodA('a', 'b');
    }
}
