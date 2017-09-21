<?php

declare(strict_types=1);

namespace Eloquent\Phony\Benchmarks;

use Eloquent\Phony;
use Icecave\Isolator\Isolator;
use Mockery;
use Phake;
use Prophecy\Prophet;
use stdClass;

/**
 * @BeforeMethods({"init"})
 * @Revs(100)
 * @Iterations(10)
 */
class LargeClassBench
{
    public function init()
    {
        $this->className = Isolator::class;
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
        $mock->shouldReceive('implode')->with('a', 'b')->andReturn('c');
        $mock->implode('a', 'b');
    }

    public function benchPhake()
    {
        $mock = Phake::mock($this->className);
        Phake::when($mock)->implode('a', 'b')->thenReturn('c');
        $mock->implode('a', 'b');
        Phake::verify($mock)->implode('a', 'b');
    }

    public function benchPhony()
    {
        $handle = Phony\mock($this->className);
        $handle->implode->with('a', 'b')->returns('c');
        $handle->get()->implode('a', 'b');
        $handle->implode->calledWith('a', 'b');
    }

    /**
     * @Skip()
     */
    public function benchProphecy()
    {
        $handle = $this->prophecy->prophesize($this->className);
        $handle->implode('a', 'b')->willReturn('c');
        $handle->reveal()->implode('a', 'b');
        $handle->implode('a', 'b')->shouldBeCalled();
    }
}
