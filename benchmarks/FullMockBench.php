<?php

declare(strict_types=1);

namespace Eloquent\Phony\Benchmarks;

use Eloquent\Phony;
use Mockery;
use Phake;
use Prophecy\Prophet;
use stdClass;

/**
 * @BeforeMethods({"init"})
 * @Revs(1000)
 * @Iterations(10)
 */
class FullMockBench
{
    public function init()
    {
        $this->className = TypicalClass::class;
        class_exists($this->className);

        $this->prophecy = new Prophet();

        Mockery::mock(stdClass::class);
        Phake::mock(stdClass::class);
        Phony\mock(stdClass::class);
        $this->prophecy->prophesize(stdClass::class);
    }

    public function benchMockery()
    {
        $mock = Mockery::mock($this->className);
        $mock->shouldReceive('testClassAMethodA')->with('a', 'b')->andReturn('c');
        $mock->testClassAMethodA('a', 'b');
    }

    public function benchPhake()
    {
        $mock = Phake::mock($this->className);
        Phake::when($mock)->testClassAMethodA('a', 'b')->thenReturn('c');
        $mock->testClassAMethodA('a', 'b');
        Phake::verify($mock)->testClassAMethodA('a', 'b');
    }

    public function benchPhony()
    {
        $handle = Phony\mock($this->className);
        $handle->testClassAMethodA->with('a', 'b')->returns('c');
        $handle->get()->testClassAMethodA('a', 'b');
        $handle->testClassAMethodA->calledWith('a', 'b');
    }

    public function benchProphecy()
    {
        $handle = $this->prophecy->prophesize($this->className);
        $handle->testClassAMethodA('a', 'b')->willReturn('c');
        $handle->reveal()->testClassAMethodA('a', 'b');
        $handle->testClassAMethodA('a', 'b')->shouldBeCalled();
    }
}
