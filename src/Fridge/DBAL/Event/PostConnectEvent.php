<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Event;

use Fridge\DBAL\Connection\ConnectionInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event dispatched just after a connection has been established.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PostConnectEvent extends Event
{
    /** @var \Fridge\DBAL\Connection\ConnectionInterface */
    private $connection;

    /**
     * Creates a post connect event.
     *
     * @param \Fridge\DBAL\Connection\ConnectionInterface $connection The connection.
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Gets the connection
     *
     * @return \Fridge\DBAL\Connection\ConnectionInterface The connection.
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
