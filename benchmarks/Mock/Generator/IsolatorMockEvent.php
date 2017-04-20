<?php

/*
 * This file is part of the Phony package.
 *
 * Copyright © 2016 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Phony\Mock\Generator;

use Athletic\AthleticEvent;
use Eloquent\Phony\Phpunit\Phony;
use Mock;
use Mockery;
use Phake;
use PHPUnit_Framework_MockObject_Generator;
use Prophecy\Prophet;

class IsolatorMockEvent extends AthleticEvent
{
    protected function setUp()
    {
        $this->className = 'Icecave\Isolator\Isolator';
        class_exists($this->className);

        $this->phpunit = new PHPUnit_Framework_MockObject_Generator();
        $this->prophecy = new Prophet();
    }

    /**
     * @iterations 10
     */
    public function phony()
    {
        Phony::mock($this->className);
    }

    /**
     * @iterations 10
     */
    public function phake()
    {
        Phake::partialMock($this->className);
    }

    /**
     * @iterations 10
     */
    public function phpunit()
    {
        $this->phpunit->getMock($this->className);
    }

    /**
     * @iterations 10
     */
    public function mockery()
    {
        Mockery::mock($this->className);
    }

    /**
     * @iterations 10
     */
    public function prophecy()
    {
        $this->prophecy->prophesize($this->className)->reveal();
    }
}
