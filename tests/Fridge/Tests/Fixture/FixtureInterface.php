<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\Fixture;

/**
 * A fixture describes a database schema representing with a DBAL schema objects graph and an SQL script.
 * This script is used to build the schema on your database and the objects graph is used by some test cases
 * in order to compare the builded database schema with the expected database schema.
 *
 * All fixtures must implement this interface.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface FixtureInterface
{
    /**
     * Creates the fixture.
     */
    public function create();

    /**
     * Drops the fixture.
     */
    public function drop();

    /**
     * Creates the fixture database.
     */
    public function createDatabase();

    /**
     * Drops the fixture database.
     */
    public function dropDatabase();

    /**
     * Creates the fixture schema.
     */
    public function createSchema();

    /**
     * Drops the fixture schema.
     */
    public function dropSchema();

    /**
     * Creates the fixture datas.
     */
    public function createDatas();

    /**
     * Drops the fixture datas.
     */
    public function dropDatas();

    /**
     * Gets the PHPUnit settings.
     *
     * @return array The PHPUnit settings.
     */
    public function getSettings();

    /**
     * Gets the database.
     *
     * @return string The database.
     */
    public function getDatabase();

    /**
     * Gets the schema.
     *
     * @return Fridge\DBAL\Schema\Schema The schema.
     */
    public function getSchema();

    /**
     * Gets the sequences.
     *
     * @return array The sequences.
     */
    public function getSequences();

    /**
     * Gets the views.
     *
     * @return array The views.
     */
    public function getViews();

    /**
     * Gets the table names.
     *
     * @return array The table names.
     */
    public function getTableNames();

    /**
     * Gets the tables.
     *
     * @return array The tables.
     */
    public function getTables();

    /**
     * Gets a table.
     *
     * @param string $name The table name.
     *
     * @return \Fridge\DBAL\Schema\Table The table.
     */
    public function getTable($name);

    /**
     * Gets the table columns.
     *
     * @param string $table The table name.
     *
     * @return array The table columns.
     */
    public function getColumns($table);

    /**
     * Gets the table primary key.
     *
     * @param string $table The table name.
     *
     * @return \Fridge\DBAL\Schema\PrimaryKey|null The table primary key.
     */
    public function getPrimaryKey($table);

    /**
     * Gets the table foreign keys.
     *
     * @param string $table The table name.
     *
     * @return array The table foreign keys.
     */
    public function getForeignKeys($table);

    /**
     * Gets the table indexes.
     *
     * @param string $table The table name.
     *
     * @return array The table indexes.
     */
    public function getIndexes($table);

    /**
     * Gets the table checks.
     *
     * @param string $table The table name.
     *
     * @return array The table checks.
     */
    public function getTableChecks($table);

    /**
     * Gets a query that can be executed on the database.
     *
     * @return string The query.
     */
    public function getQuery();

    /**
     * Gets a query with named parameters.
     *
     * @return string The query with named parameters.
     */
    public function getQueryWithNamedParameters();

    /**
     * Gets a query with positional parameters.
     *
     * @return string The query with positional parameters.
     */
    public function getQueryWithPositionalParameters();

    /**
     * Gets an update query.
     *
     * @return string The update query.
     */
    public function getUpdateQuery();

    /**
     * Gets an update query with named parameters.
     *
     * @return string The update query with named parameters.
     */
    public function getUpdateQueryWithNamedParameters();

    /**
     * Gets an update query with positional parameters.
     *
     * @return string The update query with positional parameters.
     */
    public function getUpdateQueryWithPositionalParameters();

    /**
     * Gets the named query parameters.
     *
     * @return array The named query parameters.
     */
    public function getNamedQueryParameters();

    /**
     * Gets the positional query parameters.
     *
     * @return array The positional query parameters.
     */
    public function getPositionalQueryParameters();

    /**
     * Gets the named typed query parameters.
     *
     * @return array The named typed query parameters.
     */
    public function getNamedTypedQueryParameters();

    /**
     * Gets the positional typed query parameters.
     *
     * @return array The positional typed query parameters.
     */
    public function getPositionalTypedQueryParameters();

    /**
     * Gets the named query types.
     *
     * @return array The named query types.
     */
    public function getNamedQueryTypes();

    /**
     * Gets the positional query types.
     *
     * @return array The positional query types.
     */
    public function getPositionalQueryTypes();

    /**
     * Gets the partial named query types.
     *
     * @return array The partial named query types.
     */
    public function getPartialNamedQueryTypes();

    /**
     * Gets the partial positional query types.
     *
     * @return array The partial positional query types.
     */
    public function getPartialPositionalQueryTypes();

    /**
     * Gets the query result.
     *
     * @return array The query result.
     */
    public function getQueryResult();
}
