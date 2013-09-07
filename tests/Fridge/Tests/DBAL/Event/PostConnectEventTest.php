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

use Fridge\DBAL\Event\PostConnectEvent;

/**
 * Post connect event test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PostConnectEventTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Event\PostConnectEvent */
    private $postConnectEvent;

    /** @var \Fridge\DBAL\Connection\ConnectionInterface */
    private $connection;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->connection = $this->getMock('Fridge\DBAL\Connection\ConnectionInterface');
        $this->postConnectEvent = new PostConnectEvent($this->connection);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->postConnectEvent);
        unset($this->connection);
    }

    public function testConnection()
    {
        $this->assertSame($this->connection, $this->postConnectEvent->getConnection());
    }
}
