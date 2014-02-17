<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Connection;

use Fridge\DBAL\Driver\Connection\NativeConnectionInterface;

/**
 * Adds some incredible features to a low-level connection like asynchronous connection, nested transactions,
 * transaction isolation, advanced types support and more.
 *
 * All connections must implement this interface.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface ConnectionInterface extends NativeConnectionInterface
{
    /** @const string Transaction read commited constant. */
    const TRANSACTION_READ_COMMITTED = 'READ COMMITTED';

    /** @const string Transaction read uncommited constant. */
    const TRANSACTION_READ_UNCOMMITTED = 'READ UNCOMMITTED';

    /** @const string Transaction repeatable read constant. */
    const TRANSACTION_REPEATABLE_READ = 'REPEATABLE READ';

    /** @const string Transaction read commited constant. */
    const TRANSACTION_SERIALIZABLE = 'SERIALIZABLE';

    /** @const string Array parameter constant which enables query rewritting. */
    const PARAM_ARRAY = '[]';

    /**
     * Gets the low-level connection used by the connection.
     *
     * @return \Fridge\DBAL\Driver\Connection\NativeConnectionInterface The low-level connection.
     */
    public function getNativeConnection();

    /**
     * Gets the driver used by the connection.
     *
     * @return \Fridge\DBAL\Driver\DriverInterface The connection driver.
     */
    public function getDriver();

    /**
     * Convenient method allowing to retrieve the driver platform.
     *
     * @return \Fridge\DBAL\Platform\PlatformInterface The driver platform.
     */
    public function getPlatform();

    /**
     * Convenient method allowing to retrieve the driver schema manager using this connection.
     *
     * @return \Fridge\DBAL\SchemaManager\SchemaManagerInterface The driver schema manager using the connection.
     */
    public function getSchemaManager();

    /**
     * Creates a query builder using this connection.
     *
     * @return \Fridge\DBAL\Query\QueryBuilder The query builder using the connection.
     */
    public function createQueryBuilder();

    /**
     * Gets the expression builder.
     *
     * @return \Fridge\DBAL\Query\Expression\ExpressionBuilder The expression builder.
     */
    public function getExpressionBuilder();

    /**
     * Gets the connection configuration.
     *
     * @return \Fridge\DBAL\Configuration The connection configuration.
     */
    public function getConfiguration();

    /**
     * Gets the connection parameters like it was passed to the constructor.
     *
     * @return array The connection parameters.
     */
    public function getParameters();

    /**
     * Sets the connection parameters.
     *
     * @param array $parameters The connection parameters.
     */
    public function setParameters(array $parameters);

    /**
     * Gets a connection parameter.
     *
     * @param string $parameter The connection parameter name.
     *
     * @return mixed The connection parameter value.
     */
    public function getParameter($parameter);

    /**
     * Sets a connection parameter.
     *
     * @param string $parameter The connection parameter name.
     * @param mixed  $value     The connection parameter value.
     */
    public function setParameter($parameter, $value);

    /**
     * Gets the database username.
     *
     * @return string The connection username if it is defined else NULL.
     */
    public function getUsername();

    /**
     * Sets the database username.
     *
     * @param string $username The database username.
     */
    public function setUsername($username);

    /**
     * Gets the database password.
     *
     * @return string The connection password if it is defined else NULL.
     */
    public function getPassword();

    /**
     * Sets the database password.
     *
     * @param string $password The database password.
     */
    public function setPassword($password);

    /**
     * Gets the database name. If it is not defined in the parameters, a request to the database will be done
     * in order to determine the current database name.
     *
     * @return string The database name.
     */
    public function getDatabase();

    /**
     * Sets the database name.
     *
     * @param string $database The database name.
     */
    public function setDatabase($database);

    /**
     * Gets the connection host.
     *
     * @return string The connection host if it is defined else NULL.
     */
    public function getHost();

    /**
     * Sets the connection host.
     *
     * @param string $host The connection host.
     */
    public function setHost($host);

    /**
     * Gets the connection port.
     *
     * @return integer The connection port if it is defined else NULL.
     */
    public function getPort();

    /**
     * Sets the connection port
     *
     * @param integer $port The connection port.
     */
    public function setPort($port);

    /**
     * Gets the connection driver options.
     *
     * @return array The connection driver options.
     */
    public function getDriverOptions();

    /**
     * Sets the connection driver options.
     *
     * @param array $options The connection driver options.
     */
    public function setDriverOptions(array $options);

    /**
     * Gets the transaction level.
     *
     * @return integer The transaction level.
     */
    public function getTransactionLevel();

    /**
     * Gets the transaction isolation.
     *
     * @return integer The transaction isolation.
     */
    public function getTransactionIsolation();

    /**
     * Sets the transaction isolation.
     *
     * @param integer $isolation The transaction isolation.
     */
    public function setTransactionIsolation($isolation);

    /**
     * Sets the connection charset.
     *
     * @param string $charset The connection charset.
     */
    public function setCharset($charset);

    /**
     * Checks if the connection with the database has been established.
     *
     * @return boolean TRUE if the connection has been established else FALSE.
     */
    public function isConnected();

    /**
     * Establishes the connection with the database.
     *
     * @return boolean TRUE if the connection is established else FALSE.
     */
    public function connect();

    /**
     * Closes the connection with the database.
     */
    public function close();

    /**
     * Prepares/executes an SQL query and returns the result as an associative array.
     *
     * @param string $query      The query to execute.
     * @param array  $parameters Associative array that describes column name => value pairs.
     * @param array  $types      Associative array that describes column name => type pairs (PDO or DBAL).
     *
     * @return array The result as an associative array.
     */
    public function fetchAll($query, array $parameters = array(), array $types = array());

    /**
     * Prepares/executes an SQL query and returns the first row as a numeric indexed array.
     *
     * @param string $query      The query to execute.
     * @param array  $parameters Associative array that describes column name => value pairs.
     * @param array  $types      Associative array that describes column name => type pairs (PDO or DBAL).
     *
     * @return array The first row of the result as a numerically indexed array.
     */
    public function fetchArray($query, array $parameters = array(), array $types = array());

    /**
     * Prepares/executes an SQL query and returns the first row as an associative array.
     *
     * @param string $query      The query to execute.
     * @param array  $parameters Associative array that describes column name => value pairs.
     * @param array  $types      Associative array that describes column name => type pairs (PDO or DBAL).
     *
     * @return array The first row of the query as an associative array.
     */
    public function fetchAssoc($query, array $parameters = array(), array $types = array());

    /**
     * Prepares/executes an SQL query and returns the value of a single column of the first row.
     *
     * @param string $query      The query to execute.
     * @param array  $parameters Associative array that describes column name => value pairs.
     * @param array  $types      Associative array that describes column name => type pairs (PDO or DBAL).
     * @param int    $column     The index of the column to retrieve.
     *
     * @return mixed The value of a single column of the first row of the result.
     */
    public function fetchColumn($query, array $parameters = array(), array $types = array(), $column = 0);

    /**
     * Executes a SELECT query with the given parameters and types.
     *
     * @param string $query      The query to execute.
     * @param array  $parameters Associative array that describes placeholder name => value pairs.
     * @param array  $types      Associative array that describes placeholder name => type pairs (PDO or DBAL).
     *
     * @return \Fridge\DBAL\Driver\Statement\NativeStatementInterface The statement.
     */
    public function executeQuery($query, array $parameters = array(), array $types = array());

    /**
     * Inserts a table row.
     *
     * @param string $tableName The table name to insert into.
     * @param array  $datas     Associative array that describes column name => value pairs.
     * @param array  $types     Associative array that describes column name => type pairs (PDO or DBAL).
     *
     * @return integer The number of affected rows.
     */
    public function insert($tableName, array $datas, array $types = array());

    /**
     * Updates table rows.
     *
     * @param string $tableName                The table name to update on.
     * @param array  $datas                    Associative array that describes column name => value pairs.
     * @param array  $dataTypes                Associative array that describes column name => type pairs
     *                                         (PDO or DBAL).
     * @param string $expression               The update where expression.
     * @param array  $expressionParameters     Associative array that describes expression parameter name => value
     *                                         pairs.
     * @param array  $expressionParameterTypes Associative array that describes expression parameter name => type
     *                                         pairs (PDO or DBAL).
     *
     * @return integer The number of affected rows.
     */
    public function update(
        $tableName,
        array $datas,
        array $dataTypes = array(),
        $expression = null,
        array $expressionParameters = array(),
        array $expressionParameterTypes = array()
    );

    /**
     * Deletes table rows.
     *
     * @param string $tableName                The table name to delete on.
     * @param string $expression               The delete where expression.
     * @param array  $expressionParameters     Associative array that describes expression parameter name => value
     *                                         pairs.
     * @param array  $expressionParameterTypes Associative array that describes expression parameter name => type
     *                                         pairs (PDO or DBAL).
     *
     * @return integer The number of affected rows.
     */
    public function delete(
        $tableName,
        $expression = null,
        array $expressionParameters = array(),
        array $expressionParameterTypes = array()
    );

    /**
     * Executes an INSERT/UPDATE/DELETE query with the given parameters & types.
     *
     * @param string $query      The query to execute.
     * @param array  $parameters Associative array that describes placeholder name => value pairs.
     * @param array  $types      Associative array that describes placeholder name => type pairs (PDO or DBAL).
     *
     * @return integer The number of affected rows.
     */
    public function executeUpdate($query, array $parameters = array(), array $types = array());
}
