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

use Fridge\DBAL\Driver\Connection\DriverConnectionInterface;

/**
 * Adds some incredible features to a driver connection like asynchronous connection, nested transactions,
 * transaction isolation, query debugging/rewritting, advanced types support and more.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface ConnectionInterface extends DriverConnectionInterface
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
     * Gets the driver connection.
     *
     * @return \Fridge\DBAL\Driver\Connection\DriverConnectionInterface The driver connection.
     */
    public function getDriverConnection();

    /**
     * Gets the driver.
     *
     * @return \Fridge\DBAL\Driver\DriverInterface The connection driver.
     */
    public function getDriver();

    /**
     * Gets the driver platform.
     *
     * @return \Fridge\DBAL\Platform\PlatformInterface The driver platform.
     */
    public function getPlatform();

    /**
     * Gets the driver schema manager.
     *
     * @return \Fridge\DBAL\SchemaManager\SchemaManagerInterface The driver schema manager using the connection.
     */
    public function getSchemaManager();

    /**
     * Creates a query builder.
     *
     * @return \Fridge\DBAL\Query\QueryBuilder The query builder.
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
     * Checks if the connection has parameters.
     *
     * @return boolean TRUE if the connection has parameters else FALSE.
     */
    public function hasParameters();

    /**
     * Gets the connection parameters.
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
     * Checks if the connection has a parameter.
     *
     * @param string $name The connection parameter name.
     *
     * @return boolean TRUE if the connection has the parameter else FALSE.
     */
    public function hasParameter($name);

    /**
     * Gets a connection parameter.
     *
     * @param string $name The connection parameter name.
     *
     * @return mixed The connection parameter value.
     */
    public function getParameter($name);

    /**
     * Sets a connection parameter.
     *
     * @param string $name  The connection parameter name.
     * @param mixed  $value The connection parameter value (NULL to remove it).
     */
    public function setParameter($name, $value);

    /**
     * Gets the username from the parameters.
     *
     * @return string|null The username if it is defined else NULL.
     */
    public function getUsername();

    /**
     * Sets the username as parameter.
     *
     * @param string|null $username The username (NULL to remove it).
     */
    public function setUsername($username);

    /**
     * Gets the password from the parameters.
     *
     * @return string|null The password if it is defined else NULL.
     */
    public function getPassword();

    /**
     * Sets the password as parameter.
     *
     * @param string|null $password The password (NULL to remove it).
     */
    public function setPassword($password);

    /**
     * Gets the database name from the parameters. If it is not defined, it will be fetched by the schema manager.
     *
     * @return string The database name.
     */
    public function getDatabase();

    /**
     * Sets the database name as parameter.
     *
     * @param string|null $database The database name (NULL to remove it).
     */
    public function setDatabase($database);

    /**
     * Gets the host from the parameters.
     *
     * @return string|null The host if it is defined else NULL.
     */
    public function getHost();

    /**
     * Sets the host as parameter.
     *
     * @param string|null $host The host (NULL to remove it).
     */
    public function setHost($host);

    /**
     * Gets the port from the parameters.
     *
     * @return integer|null The port if it is defined else NULL.
     */
    public function getPort();

    /**
     * Sets the port as parameter.
     *
     * @param integer|null $port The port (NULL to remove it).
     */
    public function setPort($port);

    /**
     * Gets the driver options from the parameters.
     *
     * @return array The driver options.
     */
    public function getDriverOptions();

    /**
     * Sets the driver options as parameter.
     *
     * @param array|null $options The driver options (NULL to remove it).
     */
    public function setDriverOptions(array $options = null);

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
     * Sets the charset.
     *
     * @param string $charset The charset.
     */
    public function setCharset($charset);

    /**
     * Checks if the connectionhas been established.
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
     * @return \Fridge\DBAL\Driver\Statement\DriverStatementInterface The statement.
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
     * @param array  $dataTypes                Associative array that describes column name => type pairs (PDO or DBAL).
     * @param string $expression               The update where expression.
     * @param array  $expressionParameters     Associative array that describes expression parameter name => value
     *                                         pairs.
     * @param array  $expressionParameterTypes Associative array that describes expression parameter name => type pairs
     *                                         (PDO or DBAL).
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
     * @param array  $expressionParameterTypes Associative array that describes expression parameter name => type pairs
     *                                         (PDO or DBAL).
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
