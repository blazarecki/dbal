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

use Fridge\DBAL\Driver\Connection\NativeConnectionInterface;
use Fridge\Tests\Fixture\AbstractFixtureTestCase;

/**
 * Abstract native connection test case.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractNativeConnectionTestCase extends AbstractFixtureTestCase
{
    /** @var \Fridge\DBAL\Driver\Connection\NativeConnectionInterface */
    private $connection;

    /**
     * Sets up the connection.
     *
     * @return \Fridge\DBAL\Driver\Connection\NativeConnectionInterface The connection.
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
     * @return \Fridge\DBAL\Driver\Connection\NativeConnectionInterface The connection.
     */
    protected function getConnection()
    {
        return $this->connection;
    }

    /**
     * Sets the connection.
     *
     * @param \Fridge\Tests\DBAL\Driver\Connection\NativeConnectionInterface $connection The connection.
     */
    protected function setConnection(NativeConnectionInterface $connection)
    {
        $this->connection = $connection;
    }
}
