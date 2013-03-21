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
use Fridge\DBAL\Platform\MySQLPlatform;
use Fridge\DBAL\SchemaManager\MySQLSchemaManager;

/**
 * PDO MySQL driver.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PDOMySQLDriver extends AbstractPDODriver
{
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

    /**
     * {@inheritdoc}
     */
    protected function generateDSN(array $parameters)
    {
        $dsnOptions = array();

        if (isset($parameters['dbname']) && !empty($parameters['dbname'])) {
            $dsnOptions[] = 'dbname='.$parameters['dbname'];
        }

        if (isset($parameters['host']) && !empty($parameters['host'])) {
            $dsnOptions[] = 'host='.$parameters['host'];
        }

        if (isset($parameters['port']) && !empty($parameters['port'])) {
            $dsnOptions[] = 'port='.$parameters['port'];
        }

        if (isset($parameters['unix_socket']) && !empty($parameters['unix_socket'])) {
            $dsnOptions[] = 'unix_socket='.$parameters['unix_socket'];
        }

        if (isset($parameters['charset']) && !empty($parameters['charset'])) {
            $dsnOptions[] = 'charset='.$parameters['charset'];
        }

        return 'mysql:'.implode(';', $dsnOptions);
    }
}
