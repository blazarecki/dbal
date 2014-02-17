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

/**
 * A driver allows to connect to a database by instantiating a low-level connection object.
 * Additionally, it retrieves the associated platform and schema manager.
 *
 * All drivers must implement this interface.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface DriverInterface
{
    /**
     * Connects to the database by instanciating a low-level connection object.
     *
     * @param array  $parameters    The database parameters.
     * @param string $username      The database username.
     * @param string $password      The database password.
     * @param array  $driverOptions The database driver options.
     *
     * @return \Fridge\DBAL\Driver\Connection\DriverConnectionInterface The low-level connection.
     */
    public function connect(array $parameters, $username = null, $password = null, array $driverOptions = array());

    /**
     * Gets the driver platform.
     *
     * @return \Fridge\DBAL\Platform\PlatformInterface The driver platform.
     */
    public function getPlatform();

    /**
     * Gets the driver schema manager.
     *
     * @param \Fridge\DBAL\Connection\ConnectionInterface $connection The connection used by the schema manager.
     *
     * @return \Fridge\DBAL\SchemaManager\SchemaManagerInterface The driver schema manager.
     */
    public function getSchemaManager(ConnectionInterface $connection);
}
