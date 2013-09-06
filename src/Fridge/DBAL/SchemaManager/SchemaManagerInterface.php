<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\SchemaManager;

use Fridge\DBAL\Schema\Check;
use Fridge\DBAL\Schema\Column;
use Fridge\DBAL\Schema\ConstraintInterface;
use Fridge\DBAL\Schema\Diff\ColumnDiff;
use Fridge\DBAL\Schema\Diff\SchemaDiff;
use Fridge\DBAL\Schema\Diff\TableDiff;
use Fridge\DBAL\Schema\Index;
use Fridge\DBAL\Schema\ForeignKey;
use Fridge\DBAL\Schema\PrimaryKey;
use Fridge\DBAL\Schema\Schema;
use Fridge\DBAL\Schema\Sequence;
use Fridge\DBAL\Schema\Table;
use Fridge\DBAL\Schema\View;

/**
 * A schema manager allows to fetch / create / drop schema entities.
 *
 * All schema managers must implement this interface.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface SchemaManagerInterface
{
    /**
     * Gets the schema manager connection.
     *
     * @return \Fridge\DBAL\Connection\ConnectionInterface The schema manager connection.
     */
    public function getConnection();

    /**
     * Gets the databases.
     *
     * @return array The databases
     */
    public function getDatabases();

    /**
     * Gets the database name.
     *
     * @return string The database name.
     */
    public function getDatabase();

    /**
     * Gets the schema.
     *
     * @param string $database The database name.
     *
     * @return \Fridge\DBAL\Schema\Schema The schema.
     */
    public function getSchema($database = null);

    /**
     * Gets the sequences.
     *
     * @param string $database The database name.
     *
     * @return array The sequences.
     */
    public function getSequences($database = null);

    /**
     * Gets the views.
     *
     * @param string $database The database name.
     *
     * @return array The views.
     */
    public function getViews($database = null);

    /**
     * Gets the table names.
     *
     * @param string $database The database name.
     *
     * @return array The table names.
     */
    public function getTableNames($database = null);

    /**
     * Gets the tables.
     *
     * @param string $database The database name.
     *
     * @return array The tables.
     */
    public function getTables($database = null);

    /**
     * Gets a table.
     *
     * @param string $table    The table name.
     * @param string $database The database name.
     *
     * @return \Fridge\DBAL\Schema\Table The table.
     */
    public function getTable($table, $database = null);

    /**
     * Gets the columns of a table.
     *
     * @param string $table    The table name.
     * @param string $database The database name.
     *
     * @return array The table columns.
     */
    public function getColumns($table, $database = null);

    /**
     * Gets the primary key of a table.
     *
     * @param string $table    The table name.
     * @param string $database The database name.
     *
     * @return \Fridge\DBAL\Schema\PrimaryKey|null The table primary key.
     */
    public function getTablePrimaryKey($table, $database = null);

    /**
     * Gets the foreign keys of a table.
     *
     * @param string $table    The table name.
     * @param string $database The database name.
     *
     * @return array The table foreign keys.
     */
    public function getTableForeignKeys($table, $database = null);

    /**
     * Gets the indexes of a table.
     *
     * @param string $table    The table name.
     * @param string $database The database name.
     *
     * @return array The table indexes.
     */
    public function getTableIndexes($table, $database = null);

    /**
     * Gets the check constraints of a table.
     *
     * @param string $table    The table name.
     * @param string $database The database name.
     *
     * @return array The table indexes.
     */
    public function getTableChecks($table, $database = null);

    /**
     * Creates a database.
     *
     * @param string $database The database name.
     */
    public function createDatabase($database);

    /**
     * Creates a schema.
     *
     * @param \Fridge\DBAL\Schema\Schema $schema The schema.
     */
    public function createSchema(Schema $schema);

    /**
     * Creates a sequence.
     *
     * @param \Fridge\DBAL\Schema\Sequence $sequence The sequence.
     */
    public function createSequence(Sequence $sequence);

    /**
     * Creates a view.
     *
     * @param \Fridge\DBAL\Schema\View $view The view.
     */
    public function createView(View $view);

    /**
     * Creates tables.
     *
     * @param array $tables The tables.
     */
    public function createTables(array $tables);

    /**
     * Creates a table.
     *
     * @param \Fridge\DBAL\Schema\Table $table The table.
     */
    public function createTable(Table $table);

    /**
     * Creates a table column.
     *
     * @param \Fridge\DBAL\Schema\Column $column The column.
     * @param string                     $table  The table name.
     */
    public function createColumn(Column $column, $table);

    /**
     * Creates a constraint.
     *
     * @param \Fridge\DBAL\Schema\ConstraintInterface $constraint The constraint.
     * @param string                                  $table      The table name of the constraint.
     */
    public function createConstraint(ConstraintInterface $constraint, $table);

    /**
     * Creates a primary key.
     *
     * @param \Fridge\DBAL\Schema\PrimaryKey $primaryKey The primary key.
     * @param string                         $table      The table name of the primary key.
     */
    public function createPrimaryKey(PrimaryKey $primaryKey, $table);

    /**
     * Creates a foreign key.
     *
     * @param \Fridge\DBAL\Schema\ForeignKey $foreignKey The foreign key.
     * @param string                         $table      The table name of the foreign key.
     */
    public function createForeignKey(ForeignKey $foreignKey, $table);

    /**
     * Creates an index.
     *
     * @param \Fridge\DBAL\Schema\Index $index The index.
     * @param string                    $table The table name of the index.
     */
    public function createIndex(Index $index, $table);

    /**
     * Creates a check constraint.
     *
     * @param \Fridge\DBAL\Schema\Check $check The check constraint.
     * @param string                    $table The table name of the check constraint.
     */
    public function createCheck(Check $check, $table);

    /**
     * Alters a schema.
     *
     * @param \Fridge\DBAL\Schema\Diff\SchemaDiff $schemaDiff The schema diff.
     */
    public function alterSchema(SchemaDiff $schemaDiff);

    /**
     * Alter tables.
     *
     * @param array $tableDiffs The table diffs.
     */
    public function alterTables(array $tableDiffs);

    /**
     * Alters a table.
     *
     * @param \Fridge\DBAL\Schema\Diff\TableDiff $tableDiff The table diff.
     */
    public function alterTable(TableDiff $tableDiff);

    /**
     * Alters a table column.
     *
     * @param \Fridge\DBAL\Schema\Diff\ColumnDiff $columnDiff The column diff.
     * @param string                              $table      The table name.
     */
    public function alterColumn(ColumnDiff $columnDiff, $table);

    /**
     * Drops a database.
     *
     * @param string $database The database name.
     */
    public function dropDatabase($database);

    /**
     * Drops a schema.
     *
     * @param \Fridge\DBAL\Schema\Schema $schema The schema.
     */
    public function dropSchema(Schema $schema);

    /**
     * Drops a sequence.
     *
     * @param \Fridge\DBAL\Schema\Sequence $sequence The sequence.
     */
    public function dropSequence(Sequence $sequence);

    /**
     * Drops a view.
     *
     * @param \Fridge\DBAL\Schema\View $view The view.
     */
    public function dropView(View $view);

    /**
     * Drops tables.
     *
     * @param array $tables The tables.
     */
    public function dropTables(array $tables);

    /**
     * Drops a table.
     *
     * @param \Fridge\DBAL\Schema\Table $table The table.
     */
    public function dropTable(Table $table);

    /**
     * Drops a table column.
     *
     * @param \Fridge\DBAL\Schema\Column $column The column.
     * @param string                     $table  The table name.
     */
    public function dropColumn(Column $column, $table);

    /**
     * Drops a constraint.
     *
     * @param \Fridge\DBAL\Schema\ConstraintInterface $constraint The constraint.
     * @param string                                  $table      The table name of the constraint.
     */
    public function dropConstraint(ConstraintInterface $constraint, $table);

    /**
     * Drops a primary key.
     *
     * @param \Fridge\DBAL\Schema\PrimaryKey $primaryKey The primary key.
     * @param string                         $table      The table name of the primary key.
     */
    public function dropPrimaryKey(PrimaryKey $primaryKey, $table);

    /**
     * Drops a foreign key.
     *
     * @param \Fridge\DBAL\Schema\ForeignKey $foreignKey The foreign key.
     * @param string                         $table      The table name of the foreign key.
     */
    public function dropForeignKey(ForeignKey $foreignKey, $table);

    /**
     * Drops an index.
     *
     * @param \Fridge\DBAL\Schema\Index $index The index.
     * @param string                    $table The table name of the index.
     */
    public function dropIndex(Index $index, $table);

    /**
     * Drops a ceck constraint.
     *
     * @param \Fridge\DBAL\Schema\Check $check The check constraint.
     * @param string                    $table The table name of the check constraint.
     */
    public function dropCheck(Check $check, $table);

    /**
     * Drops and creates a database.
     *
     * @param string $database The database name.
     */
    public function dropAndCreateDatabase($database);

    /**
     * Drops and creates a schema.
     *
     * @param \Fridge\DBAL\Schema\Schema $schema The schema.
     */
    public function dropAndCreateSchema(Schema $schema);

    /**
     * Drops and creates a sequence.
     *
     * @param \Fridge\DBAL\Schema\Sequence $sequence The sequence.
     */
    public function dropAndCreateSequence(Sequence $sequence);

    /**
     * Drops and creates a view.
     *
     * @param \Fridge\DBAL\Schema\View $view The view.
     */
    public function dropAndCreateView(View $view);

    /**
     * Drops and creates tables.
     *
     * @param array $tables The tables.
     */
    public function dropAndCreateTables(array $tables);

    /**
     * Drops and creates a table.
     *
     * @param \Fridge\DBAL\Schema\Table $table The table.
     */
    public function dropAndCreateTable(Table $table);

    /**
     * Drops and creates a table column.
     *
     * @param \Fridge\DBAL\Schema\Column $column The column.
     * @param string                     $table  The table name.
     */
    public function dropAndCreateColumn(Column $column, $table);

    /**
     * Drops and creates a constraint.
     *
     * @param \Fridge\DBAL\Schema\ConstraintInterface $constraint The constraint.
     * @param string                                  $table      The table name of the constraint.
     */
    public function dropAndCreateConstraint(ConstraintInterface $constraint, $table);

    /**
     * Drops and creates a primary key.
     *
     * @param \Fridge\DBAL\Schema\PrimaryKey $primaryKey The primary key.
     * @param string                         $table      The table name of the primary key.
     */
    public function dropAndCreatePrimaryKey(PrimaryKey $primaryKey, $table);

    /**
     * Drops and creates a foreign key.
     *
     * @param \Fridge\DBAL\Schema\ForeignKey $foreignKey The foreign key.
     * @param string                         $table      The table name of the foreign key.
     */
    public function dropAndCreateForeignKey(ForeignKey $foreignKey, $table);

    /**
     * Drops and creates an index.
     *
     * @param \Fridge\DBAL\Schema\Index $index The index.
     * @param string                    $table The table name of the index.
     */
    public function dropAndCreateIndex(Index $index, $table);

    /**
     * Drops and creates an check constraint.
     *
     * @param \Fridge\DBAL\Schema\Check $check The check constraint.
     * @param string                    $table The table name of the check constraint.
     */
    public function dropAndCreateCheck(Check $check, $table);
}
