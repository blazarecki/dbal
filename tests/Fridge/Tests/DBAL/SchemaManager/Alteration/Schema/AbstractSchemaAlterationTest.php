<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager\Alteration\Schema;

use Fridge\DBAL\Schema\ForeignKey;
use Fridge\DBAL\Type\Type;

/**
 * Abstract schema alteration test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractSchemaAlterationTest extends AbstractSchemaAlterationTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        if ($this->getSchemaManager() !== null) {
            $this->getSchemaManager()->dropSchema($this->getNewSchema());
        }

        parent::tearDown();
    }

    /**
     * Creates the old schema.
     */
    protected function createOldSchema()
    {
        $this->setUpNewSchema();
        $this->getSchemaManager()->createSchema($this->getOldSchema());
    }

    /**
     * Asserts the old schema is altered.
     */
    protected function assertAlteration()
    {
        $schemaDiff = $this->getComparator()->compare($this->getOldSchema(), $this->getNewSchema());
        $this->getSchemaManager()->alterSchema($schemaDiff);

        $this->assertEquals(
            $this->getNewSchema(),
            $this->getSchemaManager()->getSchema($this->getNewSchema()->getName())
        );
    }

    /**
     * Asserts the old schema is altered without database.
     */
    protected function assertAlterationWithoutDatabase()
    {
        $schemaDiff = $this->getComparator()->compare($this->getOldSchema(), $this->getNewSchema());
        $this->getSchemaManager()->alterSchema($schemaDiff);

        $this->getSchemaManager()->getConnection()->setDatabase(null);

        $this->assertEquals(
            $this->getNewSchema(),
            $this->getSchemaManager()->getSchema($this->getNewSchema()->getName())
        );
    }

    public function testCreateSequence()
    {
        $this->createOldSchema();
        $this->getNewSchema()->createSequence('foo');

        $this->assertAlteration();
    }

    public function testRenameSequence()
    {
        $this->getOldSchema()->createSequence('foo');
        $this->createOldSchema();

        $this->getNewSchema()->renameSequence('foo', 'bar');

        $this->assertAlteration();
    }

    public function testAlterSequence()
    {
        $sequence = $this->getOldSchema()->createSequence('foo');
        $this->createOldSchema();

        $this->getNewSchema()->getSequence($sequence->getName())->setInitialValue(10);

        $this->assertAlteration();
    }

    public function testDropSequence()
    {
        $sequence = $this->getOldSchema()->createSequence('foo');
        $this->createOldSchema();

        $this->getNewSchema()->dropSequence($sequence->getName());

        $this->assertAlteration();
    }

    public function testCreateView()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $this->createOldSchema();

        $this->getNewSchema()->createView('vfoo', 'SELECT foo.foo FROM foo;');

        $this->assertAlteration();
    }

    public function testRenameView()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $this->getOldSchema()->createView('vfoo', 'SELECT foo.foo FROM foo;');
        $this->createOldSchema();

        $this->getNewSchema()->renameView('vfoo', 'vbar');

        $this->assertAlteration();
    }

    public function testAlterView()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $table->createColumn('bar', Type::STRING, array('length' => 50));
        $view = $this->getOldSchema()->createView('vfoo', 'SELECT foo.foo FROM foo;');
        $this->createOldSchema();

        $this->getNewSchema()->getView($view->getName())->setSQL('SELECT foo.bar FROM foo;');

        $this->assertAlteration();
    }

    public function testDropView()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $this->getOldSchema()->createView('vfoo', 'SELECT foo FROM foo');
        $this->createOldSchema();

        $this->getNewSchema()->dropView('vfoo');

        $this->assertAlteration();
    }

    public function testCreateTable()
    {
        $this->createOldSchema();

        $table = $this->getNewSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));

        $this->assertAlteration();
    }

    public function testRenameTable()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $this->createOldSchema();

        $this->getNewSchema()->renameTable('foo', 'bar');

        $this->assertAlteration();
    }

    public function testAlterTable()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $this->createOldSchema();

        $this->getNewSchema()->getTable('foo')->getColumn('foo')->setNotNull(true);

        $this->assertAlteration();
    }

    public function testDropTable()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $this->createOldSchema();

        $this->getNewSchema()->dropTable($table->getName());

        $this->assertAlteration();
    }

    public function testCreateColumn()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->createColumn('bar', Type::STRING, array('length' => 50));

        $this->assertAlteration();
    }

    public function testRenameColumn()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->renameColumn('foo', 'bar');

        $this->assertAlteration();
    }

    public function testAlterColumn()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $column = $table->createColumn('foo', Type::STRING, array('length' => 50));
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->getColumn($column->getName())->setNotNull(true);

        $this->assertAlteration();
    }

    public function testDropColumn()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $table->createColumn('bar', Type::STRING, array('length' => 50));
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->dropColumn('bar');

        $this->assertAlteration();
    }

    public function testCreatePrimaryKey()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->createPrimaryKey(array('foo'), 'pk_foo');

        $this->assertAlteration();
    }

    public function testAlterPrimaryKey()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $table->createColumn('bar', Type::STRING, array('length' => 50));
        $table->createPrimaryKey(array('foo'), 'pk_foo');
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->dropPrimaryKey();
        $this->getNewSchema()->getTable($table->getName())->createPrimaryKey(array('bar'), 'pk_foo');

        $this->assertAlteration();
    }

    public function testDropPrimaryKey()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $table->createPrimaryKey(array('foo'), 'pk_foo');
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->dropPrimaryKey();

        $this->assertAlteration();
    }

    public function testCreateIndex()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->createIndex(array('foo'), false, 'idx_foo');

        $this->assertAlteration();
    }

    public function testRenameIndex()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $table->createIndex(array('foo'), false, 'idx_foo');
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->renameIndex('idx_foo', 'idx_bar');

        $this->assertAlteration();
    }

    public function testAlterIndex()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $index = $table->createIndex(array('foo'), false, 'idx_foo');
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->getIndex($index->getName())->setUnique(true);

        $this->assertAlteration();
    }

    public function testDropIndex()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $index = $table->createIndex(array('foo'), false, 'idx_foo');
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->dropIndex($index->getName());

        $this->assertAlteration();
    }

    public function testCreateForeignKey()
    {
        $table1 = $this->getOldSchema()->createTable('foo');
        $table1->createColumn('foo', Type::STRING, array('length' => 50));
        $table1->createPrimaryKey(array('foo'), 'pk_foo');

        $table2 = $this->getOldSchema()->createTable('bar');
        $table2->createColumn('foo', Type::STRING, array('length' => 50));

        $this->createOldSchema();

        $this->getNewSchema()->getTable($table2->getName())->createForeignKey(
            array('foo'),
            'foo',
            array('foo'),
            ForeignKey::RESTRICT,
            ForeignKey::RESTRICT,
            'fk_foo'
        );

        $this->assertAlteration();
    }

    public function testDropForeignKey()
    {
        $table1 = $this->getOldSchema()->createTable('foo');
        $table1->createColumn('foo', Type::STRING, array('length' => 50));
        $table1->createPrimaryKey(array('foo'), 'pk_foo');

        $table2 = $this->getOldSchema()->createTable('bar');
        $table2->createColumn('foo', Type::STRING, array('length' => 50));
        $foreignKey = $table2->createForeignKey(
            array('foo'),
            'foo',
            array('foo'),
            ForeignKey::RESTRICT,
            ForeignKey::RESTRICT,
            'fk_foo'
        );

        $this->createOldSchema();

        $this->getNewSchema()->getTable($table2->getName())->dropForeignKey($foreignKey->getName());

        $this->assertAlteration();
    }

    public function testCreateCheck()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::INTEGER);
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->createCheck('foo > 0', 'ck_foo');

        $this->assertAlteration();
    }

    public function testRenameCheck()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::INTEGER);
        $table->createCheck('foo > 0', 'ck_foo');
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->renameCheck('ck_foo', 'ck_bar');

        $this->assertAlteration();
    }

    public function testAlterCheck()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::INTEGER);
        $table->createColumn('bar', Type::INTEGER);
        $check = $table->createCheck('foo > 0', 'ck_foo');
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->getCheck($check->getName())->setDefinition('bar > 0');

        $this->assertAlteration();
    }

    public function testDropCheck()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::INTEGER);
        $check = $table->createCheck('foo > 0', 'ck_foo');
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->dropCheck($check->getName());

        $this->assertAlteration();
    }

    public function testRenameDatabase()
    {
        $this->createOldSchema();
        $this->getNewSchema()->setName('bar');

        $this->assertAlterationWithoutDatabase();
    }

    public function testRenameCurrentDatabase()
    {
        $this->getSchemaManager()->getConnection()->setDatabase('foo');

        $this->getOldSchema()->setName('foo');
        $this->createOldSchema();
        $this->getNewSchema()->setName('bar');

        $this->assertAlterationWithoutDatabase();
    }
}
