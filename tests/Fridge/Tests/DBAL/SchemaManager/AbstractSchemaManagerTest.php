<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager;

/**
 * Executes the functional schema manager test suite on a specific database.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractSchemaManagerTest extends AbstractSchemaManagerTestCase
{
    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::getFixture()->create();
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        if (self::hasFixture()) {
            self::getFixture()->drop();
        }
    }

    public function testConnection()
    {
        $this->assertInstanceOf(
            'Fridge\DBAL\Connection\ConnectionInterface',
            $this->getSchemaManager()->getConnection()
        );
    }

    public function testGetDatabases()
    {
        $this->assertTrue(in_array(self::getFixture()->getDatabase(), $this->getSchemaManager()->getDatabases()));
    }

    public function testGetDatabaseWithConfiguredDatabase()
    {
        $this->assertSame(self::getFixture()->getDatabase(), $this->getSchemaManager()->getDatabase());
    }

    public function testGetDatabaseWithoutConfiguredDatabase()
    {
        $this->getSchemaManager()->getConnection()->setDatabase(null);

        $this->assertNull($this->getSchemaManager()->getDatabase());
    }

    public function testGetSequences()
    {
        $this->assertEquals(self::getFixture()->getSequences(), $this->getSchemaManager()->getSequences());
    }

    public function testGetViews()
    {
        $this->assertEquals(self::getFixture()->getViews(), $this->getSchemaManager()->getViews());
    }

    public function testGetColumns()
    {
        foreach (self::getFixture()->getTableNames() as $tableName) {
            $this->assertEquals(
                self::getFixture()->getColumns($tableName),
                $this->getSchemaManager()->getColumns($tableName)
            );
        }
    }

    public function testGetPrimaryKey()
    {
        foreach (self::getFixture()->getTableNames() as $tableName) {
            $this->assertEquals(
                self::getFixture()->getPrimaryKey($tableName),
                $this->getSchemaManager()->getPrimaryKey($tableName)
            );
        }
    }

    public function testGetForeignKeys()
    {
        foreach (self::getFixture()->getTableNames() as $tableName) {
            $this->assertEquals(
                self::getFixture()->getForeignKeys($tableName),
                $this->getSchemaManager()->getForeignKeys($tableName)
            );
        }
    }

    public function testGetIndexes()
    {
        foreach (self::getFixture()->getTableNames() as $tableName) {
            $this->assertEquals(
                self::getFixture()->getIndexes($tableName),
                $this->getSchemaManager()->getIndexes($tableName)
            );
        }
    }

    public function testGetChecks()
    {
        foreach (self::getFixture()->getTableNames() as $tableName) {
            $this->assertEquals(
                self::getFixture()->getChecks($tableName),
                $this->getSchemaManager()->getChecks($tableName)
            );
        }
    }

    public function testGetTable()
    {
        foreach (self::getFixture()->getTableNames() as $table) {
            $this->assertEquals(self::getFixture()->getTable($table), $this->getSchemaManager()->getTable($table));
        }
    }

    public function testGetTableNames()
    {
        $tableNames = $this->getSchemaManager()->getTableNames();

        foreach (self::getFixture()->getTableNames() as $tableName) {
            $this->assertTrue(in_array($tableName, $tableNames));
        }

        $this->assertSame(count(self::getFixture()->getTableNames()), count($tableNames));
    }

    public function testGetTables()
    {
        $tables = $this->getSchemaManager()->getTables();

        $tableNames = self::getFixture()->getTableNames();
        sort($tableNames);

        foreach ($tableNames as $index => $tableName) {
            $this->assertEquals(self::getFixture()->getTable($tableName), $tables[$index]);
        }

        $this->assertSame(count($tableNames), count($tables));
    }

    public function testGetSchema()
    {
        $this->assertEquals(self::getFixture()->getSchema(), $this->getSchemaManager()->getSchema());
    }

    public function testDropSequence()
    {
        foreach (self::getFixture()->getSequences() as $sequence) {
            $this->getSchemaManager()->dropSequence($sequence);
        }

        $this->assertFalse($this->getSchemaManager()->getSchema()->hasSequences());
    }

    /**
     * @depends testDropSequence
     */
    public function testCreateSequence()
    {
        foreach (self::getFixture()->getSequences() as $sequence) {
            $this->getSchemaManager()->createSequence($sequence);
        }

        $this->assertEquals(self::getFixture()->getSequences(), $this->getSchemaManager()->getSequences());
    }

    public function testDropAndCreateSequence()
    {
        foreach (self::getFixture()->getSequences() as $sequence) {
            $this->getSchemaManager()->dropAndCreateSequence($sequence);
        }

        $this->assertEquals(self::getFixture()->getSequences(), $this->getSchemaManager()->getSequences());
    }

    public function testDropView()
    {
        foreach (self::getFixture()->getViews() as $view) {
            $this->getSchemaManager()->dropView($view);
        }

        $this->assertFalse($this->getSchemaManager()->getSchema()->hasViews());
    }

    /**
     * @depends testDropView
     */
    public function testCreateView()
    {
        foreach (self::getFixture()->getViews() as $view) {
            $this->getSchemaManager()->createView($view);
        }

        $this->assertEquals(self::getFixture()->getViews(), $this->getSchemaManager()->getViews());
    }

    public function testDropAndCreateView()
    {
        foreach (self::getFixture()->getViews() as $view) {
            $this->getSchemaManager()->dropAndCreateView($view);
        }

        $this->assertEquals(self::getFixture()->getViews(), $this->getSchemaManager()->getViews());
    }

    public function testDropPrimaryKey()
    {
        $table = 'tprimarykeyunlock';

        $primaryKey = self::getFixture()->getPrimaryKey($table);
        $this->getSchemaManager()->dropPrimaryKey($primaryKey, $table);

        $this->assertFalse($this->getSchemaManager()->getTable($table)->hasPrimaryKey());
    }

    /**
     * @depends testDropPrimaryKey
     */
    public function testCreatePrimaryKey()
    {
        $table = 'tprimarykeyunlock';

        $primaryKey = self::getFixture()->getPrimaryKey($table);
        $this->getSchemaManager()->createPrimaryKey($primaryKey, $table);

        $this->assertEquals($primaryKey, $this->getSchemaManager()->getPrimaryKey($table));
    }

    public function testDropAndCreatePrimaryKey()
    {
        $primaryKeyTable = 'tprimarykeyunlock';

        $primaryKey = self::getFixture()->getPrimaryKey($primaryKeyTable);
        $this->getSchemaManager()->dropAndCreatePrimaryKey($primaryKey, $primaryKeyTable);

        $this->assertEquals(
            self::getFixture()->getPrimaryKey($primaryKeyTable),
            $this->getSchemaManager()->getPrimaryKey($primaryKeyTable)
        );
    }

    public function testDropForeignKey()
    {
        $table = 'tforeignkey';

        foreach (self::getFixture()->getForeignKeys($table) as $foreignKey) {
            $this->getSchemaManager()->dropForeignKey($foreignKey, $table);
        }

        $this->assertFalse($this->getSchemaManager()->getTable($table)->hasForeignKeys());
    }

    /**
     * @depends testDropForeignKey
     */
    public function testCreateForeignKey()
    {
        $table = 'tforeignkey';

        $foreignKeys = self::getFixture()->getForeignKeys($table);

        foreach ($foreignKeys as $foreignKey) {
            $this->getSchemaManager()->createForeignKey($foreignKey, $table);
        }

        $this->assertEquals($foreignKeys, $this->getSchemaManager()->getForeignKeys($table));
    }

    public function testDropAndCreateForeignKey()
    {
        $table = 'tforeignkey';

        foreach (self::getFixture()->getForeignKeys($table) as $foreignKey) {
            $this->getSchemaManager()->dropAndCreateForeignKey($foreignKey, $table);
        }

        $this->assertEquals(
            self::getFixture()->getForeignKeys($table),
            $this->getSchemaManager()->getForeignKeys($table)
        );
    }

    public function testDropIndex()
    {
        $table = 'tindex';

        foreach (self::getFixture()->getIndexes($table) as $index) {
            $this->getSchemaManager()->dropIndex($index, $table);
        }

        $this->assertFalse($this->getSchemaManager()->getTable($table)->hasIndexes());
    }

    /**
     * @depends testDropIndex
     */
    public function testCreateIndex()
    {
        $table = 'tindex';

        $indexes = self::getFixture()->getIndexes($table);

        foreach ($indexes as $index) {
            $this->getSchemaManager()->createIndex($index, $table);
        }

        $this->assertEquals($indexes, $this->getSchemaManager()->getIndexes($table));
    }

    public function testDropAndCreateIndex()
    {
        $table = 'tindex';

        $indexes = self::getFixture()->getIndexes($table);

        foreach ($indexes as $index) {
            $this->getSchemaManager()->dropAndCreateIndex($index, $table);
        }

        $this->assertEquals($indexes, $this->getSchemaManager()->getIndexes($table));
    }

    public function testDropCheck()
    {
        $table = 'tcheck';

        $checks = self::getFixture()->getChecks($table);

        foreach ($checks as $check) {
            $this->getSchemaManager()->dropCheck($check, $table);
        }

        $this->assertFalse($this->getSchemaManager()->getTable($table)->hasChecks());
    }

    public function testCreateCheck()
    {
        $table = 'tcheck';

        $checks = self::getFixture()->getChecks($table);

        foreach ($checks as $check) {
            $this->getSchemaManager()->createCheck($check, $table);
        }

        $this->assertEquals($checks, $this->getSchemaManager()->getChecks($table));
    }

    public function testDropAndCreateCheck()
    {
        $table = 'tcheck';

        $checks = self::getFixture()->getChecks($table);

        foreach ($checks as $check) {
            $this->getSchemaManager()->dropAndCreateCheck($check, $table);
        }

        $this->assertEquals($checks, $this->getSchemaManager()->getChecks($table));
    }

    public function testDropConstraintWithPrimaryKey()
    {
        $table = 'tprimarykeyunlock';

        $this->getSchemaManager()->dropConstraint(self::getFixture()->getPrimaryKey($table), $table);

        $this->assertFalse($this->getSchemaManager()->getTable($table)->hasPrimaryKey());
    }

    /**
     * @depends testDropConstraintWithPrimaryKey
     */
    public function testCreateConstraintWithPrimaryKey()
    {
        $table = 'tprimarykeyunlock';
        $primaryKey = self::getFixture()->getPrimaryKey($table);

        $this->getSchemaManager()->createConstraint($primaryKey, $table);

        $this->assertEquals(
            $primaryKey,
            $this->getSchemaManager()->getPrimaryKey($table)
        );
    }

    public function testDropAndCreateConstraintWithPrimaryKey()
    {
        $tableName = 'tprimarykeyunlock';
        $primaryKey = self::getFixture()->getPrimaryKey($tableName);

        $this->getSchemaManager()->dropAndCreateConstraint($primaryKey, $tableName);

        $this->assertEquals($primaryKey, $this->getSchemaManager()->getTable($tableName)->getPrimaryKey());
    }

    public function testDropConstraintWithForeignKey()
    {
        $table = 'tforeignkey';

        foreach (self::getFixture()->getForeignKeys($table) as $foreignKey) {
            $this->getSchemaManager()->dropConstraint($foreignKey, $table);
        }

        $this->assertFalse($this->getSchemaManager()->getTable($table)->hasForeignKeys());
    }

    /**
     * @depends testDropConstraintWithForeignKey
     */
    public function testCreateConstraintWithForeignKey()
    {
        $table = 'tforeignkey';
        $foreignKeys = self::getFixture()->getForeignKeys($table);

        foreach ($foreignKeys as $foreignKey) {
            $this->getSchemaManager()->createConstraint($foreignKey, $table);
        }

        $this->assertEquals(
            $foreignKeys,
            $this->getSchemaManager()->getForeignKeys($table)
        );
    }

    public function testDropAndCreateConstraintWithForeignKey()
    {
        $tableName = 'tforeignkey';
        $foreignKeys = self::getFixture()->getForeignKeys($tableName);

        foreach ($foreignKeys as $foreignKey) {
            $this->getSchemaManager()->dropAndCreateConstraint($foreignKey, $tableName);
        }

        $table = $this->getSchemaManager()->getTable($tableName);

        foreach ($foreignKeys as $foreignKey) {
            $this->assertEquals($foreignKey, $table->getForeignKey($foreignKey->getName()));
        }
    }

    public function testDropConstraintWithIndex()
    {
        $table = 'tindex';

        foreach (self::getFixture()->getIndexes($table) as $index) {
            $this->getSchemaManager()->dropConstraint($index, $table);
        }

        $this->assertFalse($this->getSchemaManager()->getTable($table)->hasIndexes());
    }

    /**
     * @depends testDropConstraintWithIndex
     */
    public function testCreateConstraintWithIndex()
    {
        $table = 'tindex';
        $indexes = self::getFixture()->getIndexes($table);

        foreach ($indexes as $index) {
            $this->getSchemaManager()->createConstraint($index, $table);
        }

        $this->assertEquals(
            $indexes,
            $this->getSchemaManager()->getIndexes($table)
        );
    }

    public function testDropAndCreateConstraintWithIndex()
    {
        $tableName = 'tindex';
        $indexes = self::getFixture()->getIndexes($tableName);

        foreach ($indexes as $index) {
            $this->getSchemaManager()->dropAndCreateConstraint($index, $tableName);
        }

        $table = $this->getSchemaManager()->getTable($tableName);

        foreach ($indexes as $index) {
            $this->assertEquals($index, $table->getIndex($index->getName()));
        }
    }

    public function testDropConstraintWithCheck()
    {
        $table = 'tcheck';

        $checks = self::getFixture()->getChecks($table);

        foreach ($checks as $check) {
            $this->getSchemaManager()->dropConstraint($check, $table);
        }

        $this->assertFalse($this->getSchemaManager()->getTable($table)->hasChecks());
    }

    public function testCreateConstraintWithCheck()
    {
        $table = 'tcheck';

        $checks = self::getFixture()->getChecks($table);

        foreach ($checks as $check) {
            $this->getSchemaManager()->createConstraint($check, $table);
        }

        $this->assertEquals($checks, $this->getSchemaManager()->getChecks($table));
    }

    public function testDropAndCreateConstraintWithCheck()
    {
        $table = 'tcheck';

        $checks = self::getFixture()->getChecks($table);

        foreach ($checks as $check) {
            $this->getSchemaManager()->dropAndCreateConstraint($check, $table);
        }

        $this->assertEquals($checks, $this->getSchemaManager()->getChecks($table));
    }

    public function testDropTable()
    {
        foreach (self::getFixture()->getViews() as $view) {
            $this->getSchemaManager()->dropView($view);
        }

        $schema = self::getFixture()->getSchema();

        $tableNames = self::getFixture()->getTableNames();
        sort($tableNames);

        foreach ($tableNames as $tableName) {
            $this->getSchemaManager()->dropTable($schema->getTable($tableName));
        }

        $this->assertFalse($this->getSchemaManager()->getSchema()->hasTables());
    }

    /**
     * @depends testDropTable
     */
    public function testCreateTable()
    {
        $tableNames = self::getFixture()->getTableNames();

        foreach ($tableNames as $tableName) {
            $this->getSchemaManager()->createTable(self::getFixture()->getTable($tableName));
        }

        $schema = $this->getSchemaManager()->getSchema();

        foreach ($tableNames as $tableName) {
            $table = self::getFixture()->getTable($tableName);
            $table->setSchema($schema);

            $this->assertEquals($table, $schema->getTable($tableName));
        }
    }

    public function testDropAndCreateTable()
    {
        $tableNames = self::getFixture()->getTableNames();

        $this->getSchemaManager()->dropTable(self::getFixture()->getTable('tforeignkey'));

        foreach ($tableNames as $tableName) {
            $this->getSchemaManager()->dropAndCreateTable(self::getFixture()->getTable($tableName));
        }

        $tables = $this->getSchemaManager()->getTables();
        sort($tableNames);

        foreach ($tableNames as $index => $tableName) {
            $this->assertEquals(self::getFixture()->getTable($tableName), $tables[$index]);
        }
    }

    public function testDropTables()
    {
        $this->getSchemaManager()->dropTables(self::getFixture()->getTables());

        $this->assertEmpty($this->getSchemaManager()->getTables());
    }

    public function testCreateTables()
    {
        $this->getSchemaManager()->createTables(self::getFixture()->getTables());

        $this->assertEquals(self::getFixture()->getTables(), $this->getSchemaManager()->getTables());
    }

    public function testDropAndCreateTables()
    {
        $this->getSchemaManager()->dropAndCreateTables(self::getFixture()->getTables());

        $this->assertEquals(self::getFixture()->getTables(), $this->getSchemaManager()->getTables());
    }

    public function testDropColumn()
    {
        $table = 'tcolumns';
        $column = 'cinteger';

        $this->getSchemaManager()->dropColumn(self::getFixture()->getTable($table)->getColumn($column), $table);

        $this->assertFalse($this->getSchemaManager()->getTable($table)->hasColumn($column));
    }

    public function testCreateColumn()
    {
        $table = 'tcolumns';
        $column = 'cinteger';

        $this->getSchemaManager()->createColumn(self::getFixture()->getTable($table)->getColumn($column), $table);

        $this->assertEquals(
            self::getFixture()->getTable($table)->getColumn($column),
            $this->getSchemaManager()->getTable($table)->getColumn($column)
        );
    }

    public function testDropAndCreateColumn()
    {
        $table = 'tcolumns';
        $column = 'cinteger';

        $this->getSchemaManager()->dropAndCreateColumn(
            self::getFixture()->getTable($table)->getColumn($column),
            $table
        );

        $this->assertEquals(
            self::getFixture()->getTable($table)->getColumn($column),
            $this->getSchemaManager()->getTable($table)->getColumn($column)
        );
    }

    public function testCreateDatabase()
    {
        self::getFixture()->drop();

        $this->getSchemaManager()->createDatabase(self::getFixture()->getDatabase());
        $this->assertTrue(in_array(self::getFixture()->getDatabase(), $this->getSchemaManager()->getDatabases()));
    }

    /**
     * @depends testCreateDatabase
     */
    public function testDropDatabase()
    {
        $this->getSchemaManager()->dropDatabase(self::getFixture()->getDatabase());

        $this->getSchemaManager()->getConnection()->setDatabase(null);
        $this->assertFalse(in_array(self::getFixture()->getDatabase(), $this->getSchemaManager()->getDatabases()));
    }

    public function testDropAndCreateDatabase()
    {
        $this->getSchemaManager()->dropAndCreateDatabase(self::getFixture()->getDatabase());

        $this->assertTrue(in_array(self::getFixture()->getDatabase(), $this->getSchemaManager()->getDatabases()));
    }

    public function testCreateSchema()
    {
        self::getFixture()->drop();

        $this->getSchemaManager()->createSchema(self::getFixture()->getSchema());
        $this->assertEquals(self::getFixture()->getSchema(), $this->getSchemaManager()->getSchema());
    }

    public function testDropAndCreateSchema()
    {
        $this->getSchemaManager()->dropAndCreateSchema(self::getFixture()->getSchema());

        $this->assertEquals(self::getFixture()->getSchema(), $this->getSchemaManager()->getSchema());
    }

    public function testDropSchema()
    {
        $this->getSchemaManager()->dropSchema(self::getFixture()->getSchema());

        $this->getSchemaManager()->getConnection()->setDatabase(null);
        $this->assertFalse(in_array(self::getFixture()->getDatabase(), $this->getSchemaManager()->getDatabases()));
    }
}
