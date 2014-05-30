<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Statement;

use Fridge\DBAL\Driver\Statement\DriverStatementInterface;

/**
 * Statement.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface StatementInterface extends DriverStatementInterface, \IteratorAggregate
{
    /**
     * Gets the driver statement.
     *
     * @return \Fridge\DBAL\Driver\Statement\DriverStatementInterface The driver statement.
     */
    public function getDriverStatement();

    /**
     * Gets the connection.
     *
     * @return \Fridge\DBAL\Connection\ConnectionInterface The connection.
     */
    public function getConnection();

    /**
     * Gets the SQL statement.
     *
     * @return string The SQL statement.
     */
    public function getSQL();
}
