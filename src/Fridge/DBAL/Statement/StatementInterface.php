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
 * All statements must implement this interface.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface StatementInterface extends DriverStatementInterface
{
    /**
     * Gets the driver statement.
     *
     * @return \Fridge\DBAL\Driver\Statement\DriverStatementInterface The driver statement.
     */
    public function getDriverStatement();

    /**
     * Gets the connection linked to the statement.
     *
     * @return \Fridge\DBAL\Connection\ConnectionInterface The connection linked to the statement.
     */
    public function getConnection();

    /**
     * Gets the SQL statement.
     *
     * @return string The SQL statement.
     */
    public function getSQL();
}
