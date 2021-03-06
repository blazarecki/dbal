<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Exception;

/**
 * Factory exception.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class FactoryException extends Exception
{
    /**
     * Gets the "CONNECTION MUST IMPLEMENT ICONNECTION" exception.
     *
     * @param string $connection The connection class.
     *
     * @return \Fridge\DBAL\Exception\DBALException The "CONNECTION MUST IMPLEMENT ICONNECTION" exception.
     */
    public static function connectionMustImplementConnectionInterface($connection)
    {
        return new self(sprintf(
            'The connection "%s" must implement the Fridge\DBAL\Connection\ConnectionInterface.',
            $connection
        ));
    }

    /**
     * Gets the "DRIVER DOES NOT EXIST" exception.
     *
     * @param string $driver           The driver name.
     * @param array  $availableDrivers The available drivers.
     *
     * @return \Fridge\DBAL\Exception\DBALException The "DRIVER DOES NOT EXIST" exception.
     */
    public static function driverDoesNotExist($driver, array $availableDrivers)
    {
        return new self(sprintf(
            'The driver "%s" does not exist (Available drivers: %s).',
            $driver,
            implode(', ', $availableDrivers)
        ));
    }

    /**
     * Gets the "DRIVER MUST IMPLEMENT IDRIVER" exception.
     *
     * @param string $driver The driver class.
     *
     * @return \Fridge\DBAL\Exception\DBALException The "DRIVER MUST IMPLEMENT IDRIVER" exception.
     */
    public static function driverMustImplementDriverInterface($driver)
    {
        return new self(sprintf('The driver "%s" must implement the Fridge\DBAL\Driver\DriverInterface.', $driver));
    }

    /**
     * Gets the "DRIVER REQUIRED" exception.
     *
     * @param array $availableDrivers The available drivers.
     *
     * @return \Fridge\DBAL\Exception\DBALException The "DRIVER REQUIRED" exception.
     */
    public static function driverRequired($availableDrivers)
    {
        return new self(sprintf(
            'A connection needs at least a driver or a driver class (Available drivers: %s).',
            implode(', ', $availableDrivers)
        ));
    }
}
