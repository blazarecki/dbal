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
    public function testDebugger()
    {
        $queryDebuggerMock = $this->getMockBuilder('Fridge\DBAL\Debug\QueryDebugger')
            ->disableOriginalConstructor()
            ->getMock();

        $event = new DebugQueryEvent($queryDebuggerMock);

        $this->assertSame($queryDebuggerMock, $event->getQueryDebugger());
    }
}
