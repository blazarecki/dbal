<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Driver\Connection;

/**
 * Driver connection.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface DriverConnectionInterface
{
    /**
     * Starts a transaction.
     *
     * @return boolean TRUE if the transaction has been started else FALSE.
     */
    public function beginTransaction();

    /**
     * Saves a transaction.
     *
     * @return boolean TRUE if the transaction has been saved else FALSE.
     */
    public function commit();

    /**
     * Cancels a transaction.
     *
     * @return boolean TRUE if the transaction has been canceled else FALSE.
     */
    public function rollBack();

    /**
     * Checks if a transaction has been started.
     *
     * @return boolean TRUE if a transaction has been started else FALSE.
     */
    public function inTransaction();

    /**
     * Quotes a string.
     *
     * @param string  $string The string to quote.
     * @param integer $type   The PDO type.
     *
     * @return string The quoted string.
     */
    public function quote($string, $type = \PDO::PARAM_STR);

    /**
     * Executes an SQL query.
     *
     * @return \Fridge\DBAL\Driver\Statement\DriverStatementInterface The executed query.
     */
    public function query();

    /**
     * Prepares an SQL statement in order to be executed.
     *
     * @param string $statement The statement to prepare.
     *
     * @return \Fridge\DBAL\Driver\Statement\DriverStatementInterface The prepared statement.
     */
    public function prepare($statement);

    /**
     * Executes an SQL statement.
     *
     * @param string $statement The statement to execute.
     *
     * @return integer The number of affected rows.
     */
    public function exec($statement);

    /**
     * Gets the last generated ID or sequence value.
     *
     * @param string $name The name of the sequence object from which the ID should be returned.
     *
     * @return string The last generated ID or sequence value.
     */
    public function lastInsertId($name = null);

    /**
     * Gets the last error code associated with the last operation.
     *
     * @return string The last error code associated with the last operation.
     */
    public function errorCode();

    /**
     * Gets the last error info associated with the last operation.
     *
     * @return string The last error code associated with the last operation.
     */
    public function errorInfo();
}
