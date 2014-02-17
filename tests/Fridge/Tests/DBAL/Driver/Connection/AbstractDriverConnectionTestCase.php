<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Driver\Connection;

use Fridge\DBAL\Driver\Connection\DriverConnectionInterface;
use Fridge\Tests\Fixture\AbstractFixtureTestCase;

/**
 * Abstract driver connection test case.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractDriverConnectionTestCase extends AbstractFixtureTestCase
{
    /** @var \Fridge\DBAL\Driver\Connection\DriverConnectionInterface */
    private $connection;

    /**
     * Sets up the connection.
     *
     * @return \Fridge\DBAL\Driver\Connection\DriverConnectionInterface The connection.
     */
    abstract protected function setUpConnection();

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setConnection($this->setUpConnection());
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->connection);
    }

    /**
     * Gets the connection.
     *
     * @return \Fridge\DBAL\Driver\Connection\DriverConnectionInterface The connection.
     */
    protected function getConnection()
    {
        return $this->connection;
    }

    /**
     * Sets the connection.
     *
     * @param \Fridge\Tests\DBAL\Driver\Connection\DriverConnectionInterface $connection The connection.
     */
    protected function setConnection(DriverConnectionInterface $connection)
    {
        $this->connection = $connection;
    }
}
