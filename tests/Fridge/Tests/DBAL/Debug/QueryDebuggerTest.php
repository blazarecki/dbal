<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Debug;

use Fridge\DBAL\Debug\QueryDebugger;

/**
 * Query debugger test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class QueryDebuggerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Debug\QueryDebugger */
    private $queryDebugger;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->queryDebugger = new QueryDebugger('foo', array('bar'), array('baz'));
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->queryDebugger);
    }

    public function testDebug()
    {
        $this->queryDebugger->stop();

        $this->assertSame('foo', $this->queryDebugger->getQuery());
        $this->assertSame(array('bar'), $this->queryDebugger->getParameters());
        $this->assertSame(array('baz'), $this->queryDebugger->getTypes());
        $this->assertInternalType('float', $this->queryDebugger->getTime());
    }
}
