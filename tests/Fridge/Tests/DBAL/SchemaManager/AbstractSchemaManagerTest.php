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
abstract class AbstractSchemaManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\Tests\Fixture\FixtureInterface */
    protected static $fixture;

    /** @var \Fridge\DBAL\SchemaManager\SchemaManagerInterface */
    protected $schemaManager;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        if (self::$fixture !== null) {
            self::$fixture->create();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        if (self::$fixture !== null) {
            self::$fixture->drop();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        if (self::$fixture === null) {
            $this->markTestSkipped();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        if ($this->schemaManager !== null) {
            $this->schemaManager->getConnection()->close();

            unset($this->schemaManager);
        }
    }

    public function testConnection()
    {
        $this->assertInstanceOf('Fridge\DBAL\Connection\ConnectionInterface', $this->schemaManager->getConnection());
    }

    public function testGetDatabases()
    {
        $this->assertTrue(in_array(self::$fixture->getDatabase(), $this->schemaManager->getDatabases()));
    }

    public function testGetDatabaseWithConfiguredDatabase()
    {
        $this->assertSame(self::$fixture->getDatabase(), $this->schemaManager->getDatabase());
    }

    public function testGetDatabaseWithoutConfiguredDatabase()
    {
        $this->schemaManager->getConnection()->setDatabase(null);

        $this->assertNull($this->schemaManager->getDatabase());
    }

    public function testGetSequences()
    {
        $this->assertEquals(self::$fixture->getSequences(), $this->schemaManager->getSequences());
    }

    public function testGetViews()
    {
        $this->assertEquals(self::$fixture->getViews(), $this->schemaManager->getViews());
    }

    public function testGetColumns()
    {
        foreach (self::$fixture->getTableNames() as $tableName) {
            $this->assertEquals(
                self::$fixture->getColumns($tableName),
                $this->schemaManager->getColumns($tableName)
            );
        }
    }

    public function testGetPrimaryKey()
    {
        foreach (self::$fixture->getTableNames() as $tableName) {
            $this->assertEquals(
                self::$fixture->getPrimaryKey($tableName),
                $this->schemaManager->getPrimaryKey($tableName)
            );
        }
    }

    public function testGetForeignKeys()
    {
        foreach (self::$fixture->getTableNames() as $tableName) {
            $this->assertEquals(
                self::$fixture->getForeignKeys($tableName),
                $this->schemaManager->getForeignKeys($tableName)
            );
        }
    }

    public function testGetIndexes()
    {
        foreach (self::$fixture->getTableNames() as $tableName) {
            $this->assertEquals(
                self::$fixture->getIndexes($tableName),
                $this->schemaManager->getIndexes($tableName)
            );
        }
    }

    public function testGetChecks()
    {
        foreach (self::$fixture->getTableNames() as $tableName) {
            $this->assertEquals(
                self::$fixture->getChecks($tableName),
                $this->schemaManager->getChecks($tableName)
            );
        }
    }

    public function testGetTable()
    {
        foreach (self::$fixture->getTableNames() as $table) {
            $this->assertEquals(self::$fixture->getTable($table), $this->schemaManager->getTable($table));
        }
    }

    public function testGetTableNames()
    {
        $tableNames = $this->schemaManager->getTableNames();

        foreach (self::$fixture->getTableNames() as $tableName) {
            $this->assertTrue(in_array($tableName, $tableNames));
        }

        $this->assertSame(count(self::$fixture->getTableNames()), count($tableNames));
    }

    public function testGetTables()
    {
        $tables = $this->schemaManager->getTables();

        $tableNames = self::$fixture->getTableNames();
        sort($tableNames);

        foreach ($tableNames as $index => $tableName) {
            $this->assertEquals(self::$fixture->getTable($tableName), $tables[$index]);
        }

        $this->assertSame(count($tableNames), count($tables));
    }

    public function testGetSchema()
    {
        $this->assertEquals(self::$fixture->getSchema(), $this->schemaManager->getSchema());
    }

    public function testDropSequence()
    {
        foreach (self::$fixture->getSequences() as $sequence) {
            $this->schemaManager->dropSequence($sequence);
        }

        $this->assertFalse($this->schemaManager->getSchema()->hasSequences());
    }

    /**
     * @depends testDropSequence
     */
    public function testCreateSequence()
    {
        foreach (self::$fixture->getSequences() as $sequence) {
            $this->schemaManager->createSequence($sequence);
        }

        $this->assertEquals(self::$fixture->getSequences(), $this->schemaManager->getSequences());
    }

    public function testDropAndCreateSequence()
    {
        foreach (self::$fixture->getSequences() as $sequence) {
            $this->schemaManager->dropAndCreateSequence($sequence);
        }

        $this->assertEquals(self::$fixture->getSequences(), $this->schemaManager->getSequences());
    }

    public function testDropView()
    {
        foreach (self::$fixture->getViews() as $view) {
            $this->schemaManager->dropView($view);
        }

        $this->assertFalse($this->schemaManager->getSchema()->hasViews());
    }

    /**
     * @depends testDropView
     */
    public function testCreateView()
    {
        foreach (self::$fixture->getViews() as $view) {
            $this->schemaManager->createView($view);
        }

        $this->assertEquals(self::$fixture->getViews(), $this->schemaManager->getViews());
    }

    public function testDropAndCreateView()
    {
        foreach (self::$fixture->getViews() as $view) {
            $this->schemaManager->dropAndCreateView($view);
        }

        $this->assertEquals(self::$fixture->getViews(), $this->schemaManager->getViews());
    }

    public function testDropPrimaryKey()
    {
        $table = 'tprimarykeyunlock';

        $primaryKey = self::$fixture->getPrimaryKey($table);
        $this->schemaManager->dropPrimaryKey($primaryKey, $table);

        $this->assertFalse($this->schemaManager->getTable($table)->hasPrimaryKey());
    }

    /**
     * @depends testDropPrimaryKey
     */
    public function testCreatePrimaryKey()
    {
        $table = 'tprimarykeyunlock';

        $primaryKey = self::$fixture->getPrimaryKey($table);
        $this->schemaManager->createPrimaryKey($primaryKey, $table);

        $this->assertEquals($primaryKey, $this->schemaManager->getPrimaryKey($table));
    }

    public function testDropAndCreatePrimaryKey()
    {
        $primaryKeyTable = 'tprimarykeyunlock';

        $primaryKey = self::$fixture->getPrimaryKey($primaryKeyTable);
        $this->schemaManager->dropAndCreatePrimaryKey($primaryKey, $primaryKeyTable);

        $this->assertEquals(
            self::$fixture->getPrimaryKey($primaryKeyTable),
            $this->schemaManager->getPrimaryKey($primaryKeyTable)
        );
    }

    public function testDropForeignKey()
    {
        $table = 'tforeignkey';

        foreach (self::$fixture->getForeignKeys($table) as $foreignKey) {
            $this->schemaManager->dropForeignKey($foreignKey, $table);
        }

        $this->assertFalse($this->schemaManager->getTable($table)->hasForeignKeys());
    }

    /**
     * @depends testDropForeignKey
     */
    public function testCreateForeignKey()
    {
        $table = 'tforeignkey';

        $foreignKeys = self::$fixture->getForeignKeys($table);

        foreach ($foreignKeys as $foreignKey) {
            $this->schemaManager->createForeignKey($foreignKey, $table);
        }

        $this->assertEquals($foreignKeys, $this->schemaManager->getForeignKeys($table));
    }

    public function testDropAndCreateForeignKey()
    {
        $table = 'tforeignkey';

        foreach (self::$fixture->getForeignKeys($table) as $foreignKey) {
            $this->schemaManager->dropAndCreateForeignKey($foreignKey, $table);
        }

        $this->assertEquals(
            self::$fixture->getForeignKeys($table),
            $this->schemaManager->getForeignKeys($table)
        );
    }

    public function testDropIndex()
    {
        $table = 'tindex';

        foreach (self::$fixture->getIndexes($table) as $index) {
            $this->schemaManager->dropIndex($index, $table);
        }

        $this->assertFalse($this->schemaManager->getTable($table)->hasIndexes());
    }

    /**
     * @depends testDropIndex
     */
    public function testCreateIndex()
    {
        $table = 'tindex';

        $indexes = self::$fixture->getIndexes($table);

        foreach ($indexes as $index) {
            $this->schemaManager->createIndex($index, $table);
        }

        $this->assertEquals($indexes, $this->schemaManager->getIndexes($table));
    }

    public function testDropAndCreateIndex()
    {
        $table = 'tindex';

        $indexes = self::$fixture->getIndexes($table);

        foreach ($indexes as $index) {
            $this->schemaManager->dropAndCreateIndex($index, $table);
        }

        $this->assertEquals($indexes, $this->schemaManager->getIndexes($table));
    }

    public function testDropCheck()
    {
        $table = 'tcheck';

        $checks = self::$fixture->getChecks($table);

        foreach ($checks as $check) {
            $this->schemaManager->dropCheck($check, $table);
        }

        $this->assertFalse($this->schemaManager->getTable($table)->hasChecks());
    }

    public function testCreateCheck()
    {
        $table = 'tcheck';

        $checks = self::$fixture->getChecks($table);

        foreach ($checks as $check) {
            $this->schemaManager->createCheck($check, $table);
        }

        $this->assertEquals($checks, $this->schemaManager->getChecks($table));
    }

    public function testDropAndCreateCheck()
    {
        $table = 'tcheck';

        $checks = self::$fixture->getChecks($table);

        foreach ($checks as $check) {
            $this->schemaManager->dropAndCreateCheck($check, $table);
        }

        $this->assertEquals($checks, $this->schemaManager->getChecks($table));
    }

    public function testDropConstraintWithPrimaryKey()
    {
        $table = 'tprimarykeyunlock';

        $this->schemaManager->dropConstraint(self::$fixture->getPrimaryKey($table), $table);

        $this->assertFalse($this->schemaManager->getTable($table)->hasPrimaryKey());
    }

    /**
     * @depends testDropConstraintWithPrimaryKey
     */
    public function testCreateConstraintWithPrimaryKey()
    {
        $table = 'tprimarykeyunlock';
        $primaryKey = self::$fixture->getPrimaryKey($table);

        $this->schemaManager->createConstraint($primaryKey, $table);

        $this->assertEquals(
            $primaryKey,
            $this->schemaManager->getPrimaryKey($table)
        );
    }

    public function testDropAndCreateConstraintWithPrimaryKey()
    {
        $tableName = 'tprimarykeyunlock';
        $primaryKey = self::$fixture->getPrimaryKey($tableName);

        $this->schemaManager->dropAndCreateConstraint($primaryKey, $tableName);

        $this->assertEquals($primaryKey, $this->schemaManager->getTable($tableName)->getPrimaryKey());
    }

    public function testDropConstraintWithForeignKey()
    {
        $table = 'tforeignkey';

        foreach (self::$fixture->getForeignKeys($table) as $foreignKey) {
            $this->schemaManager->dropConstraint($foreignKey, $table);
        }

        $this->assertFalse($this->schemaManager->getTable($table)->hasForeignKeys());
    }

    /**
     * @depends testDropConstraintWithForeignKey
     */
    public function testCreateConstraintWithForeignKey()
    {
        $table = 'tforeignkey';
        $foreignKeys = self::$fixture->getForeignKeys($table);

        foreach ($foreignKeys as $foreignKey) {
            $this->schemaManager->createConstraint($foreignKey, $table);
        }

        $this->assertEquals(
            $foreignKeys,
            $this->schemaManager->getForeignKeys($table)
        );
    }

    public function testDropAndCreateConstraintWithForeignKey()
    {
        $tableName = 'tforeignkey';
        $foreignKeys = self::$fixture->getForeignKeys($tableName);

        foreach ($foreignKeys as $foreignKey) {
            $this->schemaManager->dropAndCreateConstraint($foreignKey, $tableName);
        }

        $table = $this->schemaManager->getTable($tableName);

        foreach ($foreignKeys as $foreignKey) {
            $this->assertEquals($foreignKey, $table->getForeignKey($foreignKey->getName()));
        }
    }

    public function testDropConstraintWithIndex()
    {
        $table = 'tindex';

        foreach (self::$fixture->getIndexes($table) as $index) {
            $this->schemaManager->dropConstraint($index, $table);
        }

        $this->assertFalse($this->schemaManager->getTable($table)->hasIndexes());
    }

    /**
     * @depends testDropConstraintWithIndex
     */
    public function testCreateConstraintWithIndex()
    {
        $table = 'tindex';
        $indexes = self::$fixture->getIndexes($table);

        foreach ($indexes as $index) {
            $this->schemaManager->createConstraint($index, $table);
        }

        $this->assertEquals(
            $indexes,
            $this->schemaManager->getIndexes($table)
        );
    }

    public function testDropAndCreateConstraintWithIndex()
    {
        $tableName = 'tindex';
        $indexes = self::$fixture->getIndexes($tableName);

        foreach ($indexes as $index) {
            $this->schemaManager->dropAndCreateConstraint($index, $tableName);
        }

        $table = $this->schemaManager->getTable($tableName);

        foreach ($indexes as $index) {
            $this->assertEquals($index, $table->getIndex($index->getName()));
        }
    }

    public function testDropConstraintWithCheck()
    {
        $table = 'tcheck';

        $checks = self::$fixture->getChecks($table);

        foreach ($checks as $check) {
            $this->schemaManager->dropConstraint($check, $table);
        }

        $this->assertFalse($this->schemaManager->getTable($table)->hasChecks());
    }

    public function testCreateConstraintWithCheck()
    {
        $table = 'tcheck';

        $checks = self::$fixture->getChecks($table);

        foreach ($checks as $check) {
            $this->schemaManager->createConstraint($check, $table);
        }

        $this->assertEquals($checks, $this->schemaManager->getChecks($table));
    }

    public function testDropAndCreateConstraintWithCheck()
    {
        $table = 'tcheck';

        $checks = self::$fixture->getChecks($table);

        foreach ($checks as $check) {
            $this->schemaManager->dropAndCreateConstraint($check, $table);
        }

        $this->assertEquals($checks, $this->schemaManager->getChecks($table));
    }

    public function testDropTable()
    {
        foreach (self::$fixture->getViews() as $view) {
            $this->schemaManager->dropView($view);
        }

        $schema = self::$fixture->getSchema();

        $tableNames = self::$fixture->getTableNames();
        sort($tableNames);

        foreach ($tableNames as $tableName) {
            $this->schemaManager->dropTable($schema->getTable($tableName));
        }

        $this->assertFalse($this->schemaManager->getSchema()->hasTables());
    }

    /**
     * @depends testDropTable
     */
    public function testCreateTable()
    {
        $tableNames = self::$fixture->getTableNames();

        foreach ($tableNames as $tableName) {
            $this->schemaManager->createTable(self::$fixture->getTable($tableName));
        }

        $schema = $this->schemaManager->getSchema();

        foreach ($tableNames as $tableName) {
            $table = self::$fixture->getTable($tableName);
            $table->setSchema($schema);

            $this->assertEquals($table, $schema->getTable($tableName));
        }
    }

    public function testDropAndCreateTable()
    {
        $tableNames = self::$fixture->getTableNames();

        $this->schemaManager->dropTable(self::$fixture->getTable('tforeignkey'));

        foreach ($tableNames as $tableName) {
            $this->schemaManager->dropAndCreateTable(self::$fixture->getTable($tableName));
        }

        $tables = $this->schemaManager->getTables();
        sort($tableNames);

        foreach ($tableNames as $index => $tableName) {
            $this->assertEquals(self::$fixture->getTable($tableName), $tables[$index]);
        }
    }

    public function testDropTables()
    {
        $this->schemaManager->dropTables(self::$fixture->getTables());

        $this->assertEmpty($this->schemaManager->getTables());
    }

    public function testCreateTables()
    {
        $this->schemaManager->createTables(self::$fixture->getTables());

        $this->assertEquals(self::$fixture->getTables(), $this->schemaManager->getTables());
    }

    public function testDropAndCreateTables()
    {
        $this->schemaManager->dropAndCreateTables(self::$fixture->getTables());

        $this->assertEquals(self::$fixture->getTables(), $this->schemaManager->getTables());
    }

    public function testDropColumn()
    {
        $table = 'tcolumns';
        $column = 'cinteger';

        $this->schemaManager->dropColumn(self::$fixture->getTable($table)->getColumn($column), $table);

        $this->assertFalse($this->schemaManager->getTable($table)->hasColumn($column));
    }

    public function testCreateColumn()
    {
        $table = 'tcolumns';
        $column = 'cinteger';

        $this->schemaManager->createColumn(self::$fixture->getTable($table)->getColumn($column), $table);

        $this->assertEquals(
            self::$fixture->getTable($table)->getColumn($column),
            $this->schemaManager->getTable($table)->getColumn($column)
        );
    }

    public function testDropAndCreateColumn()
    {
        $table = 'tcolumns';
        $column = 'cinteger';

        $this->schemaManager->dropAndCreateColumn(self::$fixture->getTable($table)->getColumn($column), $table);

        $this->assertEquals(
            self::$fixture->getTable($table)->getColumn($column),
            $this->schemaManager->getTable($table)->getColumn($column)
        );
    }

    public function testCreateDatabase()
    {
        self::$fixture->drop();

        $this->schemaManager->createDatabase(self::$fixture->getDatabase());
        $this->assertTrue(in_array(self::$fixture->getDatabase(), $this->schemaManager->getDatabases()));
    }

    /**
     * @depends testCreateDatabase
     */
    public function testDropDatabase()
    {
        $this->schemaManager->dropDatabase(self::$fixture->getDatabase());

        $this->schemaManager->getConnection()->setDatabase(null);
        $this->assertFalse(in_array(self::$fixture->getDatabase(), $this->schemaManager->getDatabases()));
    }

    public function testDropAndCreateDatabase()
    {
        $this->schemaManager->dropAndCreateDatabase(self::$fixture->getDatabase());

        $this->assertTrue(in_array(self::$fixture->getDatabase(), $this->schemaManager->getDatabases()));
    }

    public function testCreateSchema()
    {
        self::$fixture->drop();

        $this->schemaManager->createSchema(self::$fixture->getSchema());
        $this->assertEquals(self::$fixture->getSchema(), $this->schemaManager->getSchema());
    }

    public function testDropAndCreateSchema()
    {
        $this->schemaManager->dropAndCreateSchema(self::$fixture->getSchema());

        $this->assertEquals(self::$fixture->getSchema(), $this->schemaManager->getSchema());
    }

    public function testDropSchema()
    {
        $this->schemaManager->dropSchema(self::$fixture->getSchema());

        $this->schemaManager->getConnection()->setDatabase(null);
        $this->assertFalse(in_array(self::$fixture->getDatabase(), $this->schemaManager->getDatabases()));
    }
}
