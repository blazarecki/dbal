<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager\Alteration\Table;

use Fridge\DBAL\Schema\ForeignKey;
use Fridge\DBAL\Type\Type;

/**
 * Abstract table alteration test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractTableAlterationTest extends AbstractTableAlterationTestCase
{
    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::getFixture()->createDatabase();
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        if (self::hasFixture()) {
            self::getFixture()->dropDatabase();
        }
    }

    /**
     * Sets up columns.
     */
    protected function setUpColumns()
    {
        $this->getOldTable()->createColumn('foo', Type::STRING, array('length' => 50));
        $this->getOldTable()->createColumn('bar', Type::STRING, array('length' => 50));

        $this->createOldTable();
    }

    /**
     * Sets up a primary key.
     */
    protected function setUpPrimaryKey()
    {
        $this->getOldTable()->createColumn('foo', Type::STRING, array('length' => 50));
        $this->getOldTable()->createColumn('bar', Type::STRING, array('length' => 50));

        $this->getOldTable()->createPrimaryKey(array('foo'), 'pk');

        $this->createOldTable();
    }

    /**
     * Sets up a foreign key.
     */
    protected function setUpForeignKey()
    {
        $this->getOldTable()->createColumn('foo', Type::STRING, array('length' => 50));
        $this->getOldTable()->createColumn('bar', Type::STRING, array('length' => 50));
        $this->getOldTable()->createPrimaryKey(array('foo'), 'pk');

        $this->getOldForeignKeyTable()->createColumn('foo', Type::STRING, array('length' => 50));
        $this->getOldForeignKeyTable()->createColumn('bar', Type::STRING, array('length' => 50));
        $this->getOldForeignKeyTable()->createForeignKey(
            array('foo'),
            'foo',
            array('foo'),
            ForeignKey::RESTRICT,
            ForeignKey::RESTRICT,
            'fk_foo'
        );

        $this->createOldForeignKeyTable();
    }

    /**
     * Sets up an index.
     */
    protected function setUpIndex()
    {
        $this->getOldTable()->createColumn('foo', Type::STRING, array('length' => 50));
        $this->getOldTable()->createColumn('bar', Type::STRING, array('length' => 50));

        $this->getOldTable()->createIndex(array('foo'), false, 'idx_foo');

        $this->createOldTable();
    }

    /**
     * Sets up a check.
     */
    protected function setUpCheck()
    {
        $this->getOldTable()->createColumn('foo', Type::INTEGER);
        $this->getOldTable()->createColumn('bar', Type::INTEGER);
        $this->getOldTable()->createCheck('foo > 0', 'ck_foo');

        $this->createOldTable();
    }

    /**
     * Creates the old table.
     */
    protected function createOldTable()
    {
        $this->setUpNewTable();
        $this->getSchemaManager()->createTable($this->getOldTable());
    }

    /**
     * Creates the old foreign key table.
     */
    protected function createOldForeignKeyTable()
    {
        $this->createOldTable();

        $this->setUpNewForeignKeyTable();
        $this->getSchemaManager()->createTable($this->getOldForeignKeyTable());
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        if (($this->getSchemaManager() !== null) && ($this->getNewTable() !== null)) {
            if ($this->getNewForeignKeyTable() !== null) {
                $this->getSchemaManager()->dropTable($this->getNewForeignKeyTable());
            }

            $this->getSchemaManager()->dropTable($this->getNewTable());
        }

        parent::tearDown();
    }

    /**
     * Asserts the old table is altered.
     */
    protected function assertAlteration()
    {
        $tableDiff = $this->getComparator()->compare($this->getOldTable(), $this->getNewTable());
        $this->getSchemaManager()->alterTable($tableDiff);

        $this->assertEquals(
            $this->getNewTable(),
            $this->getSchemaManager()->getTable($this->getNewTable()->getName())
        );
    }

    /**
     * Asserts the old foreign key table is altered.
     */
    protected function assertForeignKeyAlteration()
    {
        $tableDiff = $this->getComparator()->compare($this->getOldForeignKeyTable(), $this->getNewForeignKeyTable());
        $this->getSchemaManager()->alterTable($tableDiff);

        $this->assertEquals(
            $this->getNewForeignKeyTable(),
            $this->getSchemaManager()->getTable($this->getNewForeignKeyTable()->getName())
        );
    }

    public function testRename()
    {
        $this->setUpColumns();
        $this->getNewTable()->setName('baz');

        $this->assertAlteration();
    }

    public function testCreateColumn()
    {
        $this->setUpColumns();
        $this->getNewTable()->createColumn('baz', Type::getType(Type::TEXT));

        $this->assertAlteration();
    }

    public function testAlterColumn()
    {
        $this->setUpColumns();
        $this->getNewTable()->getColumn('foo')->setNotNull(true);

        $this->assertAlteration();
    }

    public function testDropColumn()
    {
        $this->setUpColumns();
        $this->getNewTable()->dropColumn('bar');

        $this->assertAlteration();
    }

    public function testCreatePrimaryKey()
    {
        $this->setUpColumns();
        $this->getNewTable()->createPrimaryKey(array('foo'), 'pk');

        $this->assertAlteration();
    }

    public function testAlterPrimaryKey()
    {
        $this->setUpPrimaryKey();

        $this->getNewTable()->dropPrimaryKey();
        $this->getNewTable()->createPrimaryKey(array('bar'), 'pk');

        $this->assertAlteration();
    }

    public function testDropPrimaryKey()
    {
        $this->setUpPrimaryKey();
        $this->getNewTable()->dropPrimaryKey();

        $this->assertAlteration();
    }

    public function testCreateForeignKey()
    {
        $this->setUpForeignKey();
        $this->getNewForeignKeyTable()->createForeignKey(
            array('bar'),
            'foo',
            array('foo'),
            ForeignKey::RESTRICT,
            ForeignKey::RESTRICT,
            'fk_bar'
        );

        $this->assertForeignKeyAlteration();
    }

    public function testAlterForeignKey()
    {
        $this->setUpForeignKey();

        $this->getNewForeignKeyTable()->dropForeignKey('fk_foo');
        $this->getNewForeignKeyTable()->dropIndex('idx_fk_foo');

        $this->getNewForeignKeyTable()->createForeignKey(
            array('bar'),
            'foo',
            array('foo'),
            ForeignKey::RESTRICT,
            ForeignKey::RESTRICT,
            'fk_foo'
        );

        $this->assertForeignKeyAlteration();
    }

    public function testDropForeignKey()
    {
        $this->setUpForeignKey();
        $this->getNewForeignKeyTable()->dropForeignKey('fk_foo');

        $this->assertForeignKeyAlteration();
    }

    public function testCreateIndex()
    {
        $this->setUpIndex();
        $this->getNewTable()->createIndex(array('bar'), false, 'idx_bar');

        $this->assertAlteration();
    }

    public function testAlterIndex()
    {
        $this->setUpIndex();
        $this->getNewTable()->getIndex('idx_foo')->setUnique(true);

        $this->assertAlteration();
    }

    public function testDropIndex()
    {
        $this->setUpIndex();
        $this->getNewTable()->dropIndex('idx_foo');

        $this->assertAlteration();
    }

    public function testCreateCheck()
    {
        $this->setUpCheck();
        $this->getNewTable()->createCheck('bar > 0', 'ck_bar');

        $this->assertAlteration();
    }

    public function testAlterCheck()
    {
        $this->setUpCheck();
        $this->getNewTable()->getCheck('ck_foo')->setDefinition('foo > 10');

        $this->assertAlteration();
    }

    public function testDropCheck()
    {
        $this->setUpCheck();
        $this->getNewTable()->dropCheck('ck_foo');

        $this->assertAlteration();
    }
}
