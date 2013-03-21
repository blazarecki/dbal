<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Driver;

use Fridge\DBAL\Connection\ConnectionInterface;
use Fridge\DBAL\Driver\Connection\MysqliConnection;
use Fridge\DBAL\Platform\MySQLPlatform;
use Fridge\DBAL\SchemaManager\MySQLSchemaManager;

/**
 * The Mysqli driver.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class MysqliDriver extends AbstractDriver
{
    /**
     * {@inheritdoc}
     */
    public function connect(array $parameters, $username = null, $password = null, array $driverOptions = array())
    {
        return new MysqliConnection($parameters, $username, $password);
    }

    /**
     * {@inheritdoc}
     */
    protected function createPlatform()
    {
        return new MySQLPlatform();
    }

    /**
     * {@inheritdoc}
     */
    protected function createSchemaManager(ConnectionInterface $connection)
    {
        return new MySQLSchemaManager($connection);
    }
}
