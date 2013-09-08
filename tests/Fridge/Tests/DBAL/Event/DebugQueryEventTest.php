<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Event;

use Fridge\DBAL\Event\DebugQueryEvent;

/**
 * Debug query event test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class DebugQueryEventTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Event\DebugQueryEvent */
    private $debugQueryEvent;

    /** @var \Fridge\DBAL\Debug\QueryDebugger */
    private $queryDebugger;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->queryDebugger = $this->getMockBuilder('Fridge\DBAL\Debug\QueryDebugger')
            ->disableOriginalConstructor()
            ->getMock();

        $this->debugQueryEvent = new DebugQueryEvent($this->queryDebugger);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->debugQueryEvent);
        unset($this->queryDebugger);
    }

    public function testDebugger()
    {
        $this->assertSame($this->queryDebugger, $this->debugQueryEvent->getQueryDebugger());
    }
}
