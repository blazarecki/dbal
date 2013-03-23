<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Platform;

use Fridge\DBAL\Schema\Check;
use Fridge\DBAL\Schema\Column;
use Fridge\DBAL\Schema\ConstraintInterface;
use Fridge\DBAL\Schema\Diff\ColumnDiff;
use Fridge\DBAL\Schema\Diff\SchemaDiff;
use Fridge\DBAL\Schema\Diff\TableDiff;
use Fridge\DBAL\Schema\ForeignKey;
use Fridge\DBAL\Schema\Index;
use Fridge\DBAL\Schema\PrimaryKey;
use Fridge\DBAL\Schema\Sequence;
use Fridge\DBAL\Schema\Table;
use Fridge\DBAL\Schema\View;

/**
 * A platform allows to know each specific database behaviors.
 *
 * All platforms must implement this interface.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface PlatformInterface
{
    /**
     * Checks if a mapped type exists.
     *
     * @param string $type The type.
     *
     * @return boolean TRUE if the mapped type exists else FALSE.
     */
    public function hasMappedType($type);

    /**
     * Gets a mapped type.
     *
     * @param string $type The type.
     *
     * @return string The mapped type.
     */
    public function getMappedType($type);

    /**
     * Adds a mapped type.
     *
     * @param string $databaseType The database type.
     * @param string $fridgeType   The fridge type.
     */
    public function addMappedType($databaseType, $fridgeType);

    /**
     * Overrides a mapped type.
     *
     * @param string $databaseType The database type.
     * @param string $fridgeType   The fridge type.
     */
    public function overrideMappedType($databaseType, $fridgeType);

    /**
     * Removes a mapped type.
     *
     * @param string $type The mapped type to remove.
     */
    public function removeMappedType($type);

    /**
     * Checks/sets if the platform uses a strict type mapping strategy.
     *
     * @param boolean $useStrictTypeMapping TRUE if the platform uses stric type mapping strategy else FALSE.
     *
     * @return boolean TRUE if the platform uses stric type mapping strategy else FALSE.
     */
    public function useStrictTypeMapping($useStrictTypeMapping = null);

    /**
     * Gets the fallback mapped type.
     *
     * @return string The fallback mapped type.
     */
    public function getFallbackMappedType();

    /**
     * Sets the fallback mapped type.
     *
     * @param string $fallbackMappedType The fallback mapped type.
     */
    public function setFallbackMappedType($fallbackMappedType);

    /**
     * Checks if a custom type exists.
     *
     * @param string $type The custom type.
     *
     * @return boolean TRUE if the custom type exists else FALSE.
     */
    public function hasCustomType($type);

    /**
     * Adds a custom type.
     *
     * @param string $type The custom type.
     */
    public function addCustomType($type);

    /**
     * Removes a custom type.
     *
     * @param string $type The custom type.
     */
    public function removeCustomType($type);

    /**
     * Gets the big integer SQL declaration.
     *
     * @param array $options The big integer options.
     *
     * @return string The big integer SQL declaration.
     */
    public function getBigIntegerSQLDeclaration(array $options = array());

    /**
     * Gets the blob SQL declaration.
     *
     * @param array $options The blob options.
     *
     * @return string The blob SQL declaration.
     */
    public function getBlobSQLDeclaration(array $options = array());

    /**
     * Gets the boolean SQL declaration.
     *
     * @param array $options The boolean options.
     *
     * @return string The boolean SQL declaration.
     */
    public function getBooleanSQLDeclaration(array $options = array());

    /**
     * Gets the clob SQL declaration.
     *
     * @param array $options The clob options.
     *
     * @return string The clob SQL declaration.
     */
    public function getClobSQLDeclaration(array $options = array());

    /**
     * Gets the date SQL declaration.
     *
     * @param array $options The date options.
     *
     * @return string The date SQL declaration.
     */
    public function getDateSQLDeclaration(array $options = array());

    /**
     * Gets the date time SQL declaration.
     *
     * @param array $options The date time options.
     *
     * @return string The date time SQL declaration.
     */
    public function getDateTimeSQLDeclaration(array $options = array());

    /**
     * Gets the decimal SQL declaration.
     *
     * @param array $options The decimal options.
     *
     * @return string The decimal SQL declaration.
     */
    public function getDecimalSQLDeclaration(array $options = array());

    /**
     * Gets the float SQL declaration.
     *
     * @param array $options The float options.
     *
     * @return string The float SQL declaration.
     */
    public function getFloatSQLDeclaration(array $options = array());

    /**
     * Gets the integer SQL declaration.
     *
     * @param array $options The integer options.
     *
     * @return string The integer SQL declaration.
     */
    public function getIntegerSQLDeclaration(array $options = array());

    /**
     * Gets the small integer SQL declaration.
     *
     * @param array $options The small integer options.
     *
     * @return string The small integer SQL declaration.
     */
    public function getSmallIntegerSQLDeclaration(array $options = array());

    /**
     * Gets the time SQL declaration.
     *
     * @param array $options The time options.
     *
     * @return string The time SQL declaration.
     */
    public function getTimeSQLDeclaration(array $options = array());

    /**
     * Gets the varchar SQL declaration.
     *
     * @param array $options The varchar options.
     *
     * @return string The varchar SQL declaration.
     */
    public function getVarcharSQLDeclaration(array $options = array());

    /**
     * Gets the default decimal precision.
     *
     * @return integer The default decimal precision.
     */
    public function getDefaultDecimalPrecision();

    /**
     * Gets the default decimal scale.
     *
     * @return integer The default decimal scale.
     */
    public function getDefaultDecimalScale();

    /**
     * Gets the default varchar length.
     *
     * @return integer The default varchar length.
     */
    public function getDefaultVarcharLength();

    /**
     * Gets the default platform transaction isolation.
     *
     * @return string The default platform transaction isolation.
     */
    public function getDefaultTransactionIsolation();

    /**
     * Gets the max varchar length.
     *
     * @return integer The max varchar length.
     */
    public function getMaxVarcharLength();

    /**
     * Gets the date format.
     *
     * @return string The date format.
     */
    public function getDateFormat();

    /**
     * Gets the time format.
     *
     * @return string The time format.
     */
    public function getTimeFormat();

    /**
     * Gets the date time format.
     *
     * @return string The date time format.
     */
    public function getDateTimeFormat();

    /**
     * Checks if the platform supports savepoints.
     *
     * @return boolean TRUE if the platform supports savepoints else FALSE.
     */
    public function supportSavepoints();

    /**
     * Checks if the platform supports transaction isolations.
     *
     * @return boolean TRUE if the platform supports transaction isolations else FALSE.
     */
    public function supportTransactionIsolations();

    /**
     * Checks if the platform supports sequences.
     *
     * @return boolean TRUE if the platform supports sequences else FALSE.
     */
    public function supportSequences();

    /**
     * Checks if the platform supports views.
     *
     * @return boolean TRUE if the platform supports views else FALSE.
     */
    public function supportViews();

    /**
     * Checks if the platform supports primary keys.
     *
     * @return boolean TRUE if the platform supports primary keys else FALSE.
     */
    public function supportPrimaryKeys();

    /**
     * Checks if the platform supports foreign keys.
     *
     * @return boolean TRUE if the platform supports foreign keys else FALSE.
     */
    public function supportForeignKeys();

    /**
     * Checks if the platform supports indexes.
     *
     * @return boolean TRUE if the platform supports indexes else FALSE.
     */
    public function supportIndexes();

    /**
     * Checks if the platform support checks.
     *
     * @return boolean TRUE if the platform supports checks else FALSE.
     */
    public function supportChecks();

    /**
     * Checks if the platform supports inline column comments.
     *
     * @return boolean TRUE if the platform supports inline column comments else FALSE.
     */
    public function supportInlineColumnComments();

    /**
     * Gets the set charset SQL query.
     *
     * @param string $charset The charset.
     *
     * @return string The set charset SQL query.
     */
    public function getSetCharsetSQLQuery($charset);

    /**
     * Gets the create savepoint SQL query.
     *
     * @param string $savepoint The savepoint name.
     *
     * @return string The create savepoint SQL query.
     */
    public function getCreateSavepointSQLQuery($savepoint);

    /**
     * Gets the release savepoint SQL query.
     *
     * @param string $savepoint The savepoint name.
     *
     * @return string The release savepoint SQL query.
     */
    public function getReleaseSavepointSQLQuery($savepoint);

    /**
     * Gets the rollback savepoint SQL query.
     *
     * @param string $savepoint The savepoint name.
     *
     * @return string The rollback savepoint SQL query.
     */
    public function getRollbackSavepointSQLQuery($savepoint);

    /**
     * Gets the set transaction isolation SQL query.
     *
     * @param string $isolation The transaction isolation.
     *
     * @return string The set transaction isolation SQL query.
     */
    public function getSetTransactionIsolationSQLQuery($isolation);

    /**
     * Gets the select query to fetch the current database.
     *
     * @return string The select query to fetch the current database.
     */
    public function getSelectDatabaseSQLQuery();

    /**
     * Gets the select query to fetch databases.
     *
     * @return string The select query to fetch databases.
     */
    public function getSelectDatabasesSQLQuery();

    /**
     * Gets the select query to fetch sequences.
     *
     * @param string $database The database name.
     *
     * @return string The select query to fetch sequences.
     */
    public function getSelectSequencesSQLQuery($database);

    /**
     * Gets the select views to fetch views.
     *
     * @param string $database The database name.
     *
     * @return string The select query to fetch views.
     */
    public function getSelectViewsSQLQuery($database);

    /**
     * Gets the select query to fetch table names.
     *
     * @param string $database The database name.
     *
     * @return string The select query to fetch table names.
     */
    public function getSelectTableNamesSQLQuery($database);

    /**
     * Gets the select query to fetch table columns.
     *
     * @param string $table    The table name.
     * @param string $database The database name.
     *
     * @return string The select query to fetch table columns.
     */
    public function getSelectTableColumnsSQLQuery($table, $database);

    /**
     * Gets the select query to fetch table primary key.
     *
     * @param string $table    The table name.
     * @param string $database The database name.
     *
     * @return string The select query to fetch table primary key.
     */
    public function getSelectTablePrimaryKeySQLQuery($table, $database);

    /**
     * Gets the select query to fetch table foreign keys.
     *
     * @param string $table    The table name.
     * @param string $database The database name.
     *
     * @return string The select query to fetch table foreign keys.
     */
    public function getSelectTableForeignKeysSQLQuery($table, $database);

    /**
     * Gets the select query to fetch table indexes.
     *
     * @param string $table    The table name.
     * @param string $database The database name.
     *
     * @return string The select query to fetch table indexes.
     */
    public function getSelectTableIndexesSQLQuery($table, $database);

    /**
     * Gets the select query to fetch table check constraints.
     *
     * @param string $table    The table name.
     * @param string $database The database name.
     *
     * @return string The select query to fetch table check constraints.
     */
    public function getSelectTableChecksSQLQuery($table, $database);

    /**
     * Gets the create database SQL queries.
     *
     * @param string $database The database name.
     *
     * @return array The create database SQL queries.
     */
    public function getCreateDatabaseSQLQueries($database);

    /**
     * Gets the create sequence SQL queries.
     *
     * @param \Fridge\DBAL\Schema\Sequence $sequence The sequence.
     *
     * @return array The create sequence SQL queries.
     */
    public function getCreateSequenceSQLQueries(Sequence $sequence);

    /**
     * Gets the create view SQL queries.
     *
     * @param \Fridge\DBAL\Schema\View $view The view.
     *
     * @return array The create view SQL queries.
     */
    public function getCreateViewSQLQueries(View $view);

    /**
     * Gets the create table SQL queries.
     *
     * The $flags parameters can contain:
     *  - primary_key: TRUE if queries include primary key else FALSE (default: TRUE).
     *  - index: TRUE if queries include indexes else FALSE (default: TRUE).
     *  - foreign_key: TRUE if queries include foreingn keys else FALSE (default: TRUE).
     *  - check: TRUE if queries include checks else FALSE (default: TRUE).
     *
     * @param \Fridge\DBAL\Schema\Table $table The table.
     * @param array                     $flags The create table flags.
     *
     * @return array The create table SQL queries.
     */
    public function getCreateTableSQLQueries(Table $table, array $flags = array());

    /**
     * Gets the create table column SQL queries.
     *
     * @param \Fridge\DBAL\Schema\Column $column The column.
     * @param string                     $table  The table name.
     *
     * @return array The create table column SQL queries.
     */
    public function getCreateColumnSQLQueries(Column $column, $table);

    /**
     * Gets the create constraint SQL queries.
     *
     * @param \Fridge\DBAL\Schema\ConstraintInterface $constraint The constraint.
     * @param string                                  $table      The table name of the constraint.
     *
     * @return array The create constraint SQL queries.
     */
    public function getCreateConstraintSQLQueries(ConstraintInterface $constraint, $table);

    /**
     * Gets the create primary key SQL queries.
     *
     * @param \Fridge\DBAL\Schema\PrimaryKey $primaryKey The primary key.
     * @param string                         $table      The table name of the primary key.
     *
     * @return array The create primary key SQL queries.
     */
    public function getCreatePrimaryKeySQLQueries(PrimaryKey $primaryKey, $table);

    /**
     * Gets the create foreign key SQL queries.
     *
     * @param \Fridge\DBAL\Schema\ForeignKey $foreignKey The foreign key.
     * @param string                         $table      The table name of the foreign key.
     *
     * @return array The create foreign key SQL queries.
     */
    public function getCreateForeignKeySQLQueries(ForeignKey $foreignKey, $table);

    /**
     * Gets the create index SQL queries.
     *
     * @param \Fridge\DBAL\Schema\Index $index The index.
     * @param string                    $table The table name of the index.
     *
     * @return array The create index SQL queries.
     */
    public function getCreateIndexSQLQueries(Index $index, $table);

    /**
     * Gets the create check constraint SQL queries.
     *
     * @param \Fridge\DBAL\Schema\Check $check The check constraint.
     * @param string                    $table The table name of the check constraint.
     *
     * @return array The create check constraint SQL queries.
     */
    public function getCreateCheckSQLQueries(Check $check, $table);

    /**
     * Gets the rename database SQL queries.
     *
     * @param \Fridge\DBAL\Schema\Diff\SchemaDiff $schemaDiff The schema diff.
     *
     * @return array The rename database SQL queries.
     */
    public function getRenameDatabaseSQLQueries(SchemaDiff $schemaDiff);

    /**
     * Gets the rename table SQL queries.
     *
     * @param \Fridge\DBAL\Schema\Diff\TableDiff $tableDiff The table diff.
     *
     * @return array The rename table SQL quueries.
     */
    public function getRenameTableSQLQueries(TableDiff $tableDiff);

    /**
     * Gets the alter table column SQL queries.
     *
     * @param \Fridge\DBAL\Schema\Diff\ColumnDiff $columnDiff The column diff.
     * @param string                              $table      The table name.
     *
     * @return array The alter table column SQL queries.
     */
    public function getAlterColumnSQLQueries(ColumnDiff $columnDiff, $table);

    /**
     * Gets the drop database SQL queries.
     *
     * @param string $database The database name.
     *
     * @return array The drop database SQL queries.
     */
    public function getDropDatabaseSQLQueries($database);

    /**
     * Gets the drop sequence SQL queries.
     *
     * @param \Fridge\DBAL\Schema\Sequence $sequence The sequence.
     *
     * @return array The drop sequence SQL queries.
     */
    public function getDropSequenceSQLQueries(Sequence $sequence);

    /**
     * Gets the drop view SQL queries.
     *
     * @param \Fridge\DBAL\Schema\View $view The view.
     *
     * @return array The drop view SQL queries.
     */
    public function getDropViewSQLQueries(View $view);

    /**
     * Gets the drop table SQL queries.
     *
     * @param \Fridge\DBAL\Schema\Table $table The table.
     *
     * @return array The drop table SQL queries.
     */
    public function getDropTableSQLQueries(Table $table);

    /**
     * Gets the drop table column SQL queries.
     *
     * @param \Fridge\DBAL\Schema\Column $column The column.
     * @param string                     $table  The table name.
     *
     * @return array The drop table column SQL queries.
     */
    public function getDropColumnSQLQueries(Column $column, $table);

    /**
     * Gets the drop constraint SQL queries.
     *
     * @param \Fridge\DBAL\Schema\ConstraintInterface $constraint The constraint.
     * @param string                                  $table      The table name of the constraint.
     *
     * @return array The drop constraint SQL queries.
     */
    public function getDropConstraintSQLQueries(ConstraintInterface $constraint, $table);

    /**
     * Gets the drop primary key SQL queries.
     *
     * @param \Fridge\DBAL\Schema\PrimaryKey $primaryKey The primary key.
     * @param string                         $table      The table name of the primary key.
     *
     * @return array The drop primary key SQL queries.
     */
    public function getDropPrimaryKeySQLQueries(PrimaryKey $primaryKey, $table);

    /**
     * Gets the drop foreign key SQL queries.
     *
     * @param \Fridge\DBAL\Schema\ForeignKey $foreignKey The foreign key.
     * @param string                         $table      The table name of the foreign key.
     *
     * @return array The drop foreign key SQL queries.
     */
    public function getDropForeignKeySQLQueries(ForeignKey $foreignKey, $table);

    /**
     * Gets the drop index SQL queries.
     *
     * @param \Fridge\DBAL\Schema\Index $index The index.
     * @param string                    $table The table name of the index.
     *
     * @return array The drop index SQL queries.
     */
    public function getDropIndexSQLQueries(Index $index, $table);

    /**
     * Gets the drop check constraint SQL queries.
     *
     * @param \Fridge\DBAL\Schema\Check $check The check.
     * @param string                    $table The table name of the check constraint.
     *
     * @return array The drop index SQL queries.
     */
    public function getDropCheckSQLQueries(Check $check, $table);

    /**
     * Gets the quote identifier.
     *
     * @return string The quote identifier
     */
    public function getQuoteIdentifier();

    /**
     * Quotes identifiers.
     *
     * @param array $identifiers The identifiers.
     *
     * @return array The quoted identifiers.
     */
    public function quoteIdentifiers(array $identifiers);

    /**
     * Quotes an identifier.
     *
     * @param string $identifier The identifier.
     *
     * @return string The quoted identifier.
     */
    public function quoteIdentifier($identifier);
}
