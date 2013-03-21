<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Driver\Statement;

use PDO;

/**
 * Low-level class representing a prepared SQL statement.
 *
 * All low-level statements must implement this interface.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface NativeStatementInterface
{
    /**
     * Binds a parameter to a PHP variable.
     *
     * @param integer|string $parameter The parameter index or name.
     * @param mixed          &$variable The PHP variable.
     * @param integer        $type      The parameter type.
     *
     * @return boolean TRUE if the parameter has been binded to the PHP variable else FALSE.
     */
    public function bindParam($parameter, &$variable, $type = PDO::PARAM_STR);

    /**
     * Binds a value to a parameter.
     *
     * @param integer|string $parameter The parameter index or name.
     * @param mixed          $value     The value to bind.
     * @param integer        $type      The parameter type.
     *
     * @return boolean TRUE if the value has been binded to the parameter else FALSE.
     */
    public function bindValue($parameter, $value, $type = PDO::PARAM_STR);

    /**
     * Executes the statement.
     *
     * @param array $parameters The statement parameters.
     *
     * @return boolean TRUE if the statement has been excuted else FALSE.
     */
    public function execute($parameters = array());

    /**
     * Gets the number of rows affected by the last SQL statement.
     *
     * @return integer The number of rows affected by the last SQL statement.
     */
    public function rowCount();

    /**
     * Fetches all rows from the result set.
     *
     * @param integer $fetchMode Controls how the next row will be returned.
     *
     * @return array All rows from the result set.
     */
    public function fetchAll($fetchMode = PDO::FETCH_BOTH);

    /**
     * Fetches the next row from the result set.
     *
     * @param integer $fetchMode Controls how rows will be returned.
     *
     * @return mixed The next row from the result set.
     */
    public function fetch($fetchMode = PDO::FETCH_BOTH);

    /**
     * Fetches the value of a single column from the next row of the result set.
     *
     * @param integer $columnIndex The column index.
     *
     * @return mixed The value of the single column from the next row of the result set.
     */
    public function fetchColumn($columnIndex = 0);

    /**
     * Sets the default fetch mode.
     *
     * @param integer $fetchMode The default fetch mode.
     *
     * @return boolean TRUE if the default fetch mode has been setted else FALSE.
     */
    public function setFetchMode($fetchMode);

    /**
     * Returns the number of columns in the result set.
     *
     * @return integer The number of columns in the result set.
     */
    public function columnCount();

    /**
     * Closes the cursor in order to be able to execute the statement again.
     *
     * @return boolean TRUE if the cursor has been closed else FALSE.
     */
    public function closeCursor();

    /**
     * Gets the last error code associated with the last operation on the statement.
     *
     * @return string The last error code associated with the last operation on the statement.
     */
    public function errorCode();

    /**
     * Gets the last error info associated with the last operation on the statement.
     *
     * @return string The last error info associated with the last operation on the statement.
     */
    public function errorInfo();
}
