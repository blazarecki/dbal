<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager\SQLCollector;

use Fridge\DBAL\Schema\Check;
use Fridge\DBAL\Schema\Column;
use Fridge\DBAL\Schema\Diff\ColumnDiff;
use Fridge\DBAL\Schema\Diff\TableDiff;
use Fridge\DBAL\Schema\ForeignKey;
use Fridge\DBAL\Schema\Index;
use Fridge\DBAL\Schema\PrimaryKey;
use Fridge\DBAL\Schema\Table;
use Fridge\DBAL\SchemaManager\SQLCollector\AlterTableSQLCollector;
use Fridge\DBAL\Type\Type;

/**
 * Alter table SQL collector test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class AlterTableSQLCollectorTest extends AbstractSQLCollectorTestCase
{
    /** @var \Fridge\DBAL\Schema\Diff\TableDiff */
    private $tableDiff;

    /**
     * {@inheritdoc}
     */
    protected function setUpSQLCollector()
    {
        return new AlterTableSQLCollector($this->getPlatform());
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tableDiff = new TableDiff(
            new Table('foo'),
            new Table('bar'),
            array(new Column('created', Type::getType(Type::INTEGER))),
            array(
                new ColumnDiff(
                    new Column('altered', Type::getType(Type::INTEGER)),
                    new Column('altered', Type::getType(Type::SMALLINTEGER)),
                    array()
                ),
            ),
            array(new Column('dropped', Type::getType(Type::INTEGER))),
            new PrimaryKey('created', array('bar')),
            new PrimaryKey('dropped', array('foo')),
            array(new ForeignKey('created', array('bar'), 'bar', array('bar'))),
            array(new ForeignKey('dropped', array('foo'), 'bar', array('bar'))),
            array(new Index('created', array('baz'))),
            array(new Index('dropped', array('baz'), true)),
            array(new Check('created', 'foo')),
            array(new Check('dropped', 'bar'))
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->tableDiff);
    }

    /**
     * Asserts the intial state.
     */
    private function assertInitialState()
    {
        $this->assertEmpty($this->getSQLCollector()->getRenameTableQueries());
        $this->assertEmpty($this->getSQLCollector()->getDropCheckQueries());
        $this->assertEmpty($this->getSQLCollector()->getDropForeignKeyQueries());
        $this->assertEmpty($this->getSQLCollector()->getDropIndexQueries());
        $this->assertEmpty($this->getSQLCollector()->getDropPrimaryKeyQueries());
        $this->assertEmpty($this->getSQLCollector()->getDropColumnQueries());
        $this->assertEmpty($this->getSQLCollector()->getAlterColumnQueries());
        $this->assertEmpty($this->getSQLCollector()->getCreateColumnQueries());
        $this->assertEmpty($this->getSQLCollector()->getCreatePrimaryKeyQueries());
        $this->assertEmpty($this->getSQLCollector()->getCreateIndexQueries());
        $this->assertEmpty($this->getSQLCollector()->getCreateForeignKeyQueries());
        $this->assertEmpty($this->getSQLCollector()->getCreateCheckQueries());
        $this->assertEmpty($this->getSQLCollector()->getQueries());
    }

    public function testInitialState()
    {
        $this->assertInitialState();
    }

    public function testPlatform()
    {
        $this->assertSame($this->getPlatform(), $this->getSQLCollector()->getPlatform());

        $platformMock = $this->getMock('Fridge\DBAL\Platform\PlatformInterface');
        $this->getSQLCollector()->setPlatform($platformMock);

        $this->assertSame($platformMock, $this->getSQLCollector()->getPlatform());
    }

    public function testCollect()
    {
        $createdColumns = $this->tableDiff->getCreatedColumns();
        $alteredColumns = $this->tableDiff->getAlteredColumns();
        $droppedColumns = $this->tableDiff->getDroppedColumns();

        $droppedForeignKeys = $this->tableDiff->getDroppedForeignKeys();
        $createdForeignKeys = $this->tableDiff->getCreatedForeignKeys();

        $droppedIndexes = $this->tableDiff->getDroppedIndexes();
        $createdIndexes = $this->tableDiff->getCreatedIndexes();

        $droppedChecks = $this->tableDiff->getDroppedChecks();
        $createdChecks = $this->tableDiff->getCreatedChecks();

        $this->getPlatform()
            ->expects($this->once())
            ->method('getRenameTableSQLQueries')
            ->with($this->equalTo($this->tableDiff))
            ->will($this->returnValue(array('RENAME TABLE')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropCheckSQLQueries')
            ->with($this->equalTo($droppedChecks[0]), $this->equalTo($this->tableDiff->getNewAsset()->getName()))
            ->will($this->returnValue(array('DROP CHECK')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropForeignKeySQLQueries')
            ->with($this->equalTo($droppedForeignKeys[0]), $this->equalTo($this->tableDiff->getNewAsset()->getName()))
            ->will($this->returnValue(array('DROP FOREIGN KEY')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropIndexSQLQueries')
            ->with($this->equalTo($droppedIndexes[0]), $this->equalTo($this->tableDiff->getNewAsset()->getName()))
            ->will($this->returnValue(array('DROP INDEX')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropPrimaryKeySQLQueries')
            ->with(
                $this->equalTo($this->tableDiff->getDroppedPrimaryKey()),
                $this->equalTo($this->tableDiff->getNewAsset()->getName())
            )
            ->will($this->returnValue(array('DROP PRIMARY KEY')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropColumnSQLQueries')
            ->with($this->equalTo($droppedColumns[0]), $this->equalTo($this->tableDiff->getNewAsset()->getName()))
            ->will($this->returnValue(array('DROP COLUMN')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getAlterColumnSQLQueries')
            ->with($this->equalTo($alteredColumns[0]), $this->equalTo($this->tableDiff->getNewAsset()->getName()))
            ->will($this->returnValue(array('ALTER COLUMN')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateColumnSQLQueries')
            ->with($this->equalTo($createdColumns[0]), $this->equalTo($this->tableDiff->getNewAsset()->getName()))
            ->will($this->returnValue(array('CREATE COLUMN')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreatePrimaryKeySQLQueries')
            ->with(
                $this->equalTo($this->tableDiff->getCreatedPrimaryKey()),
                $this->equalTo($this->tableDiff->getNewAsset()->getName())
            )
            ->will($this->returnValue(array('CREATE PRIMARY KEY')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateIndexSQLQueries')
            ->with($this->equalTo($createdIndexes[0]), $this->equalTo($this->tableDiff->getNewAsset()->getName()))
            ->will($this->returnValue(array('CREATE INDEX')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateForeignKeySQLQueries')
            ->with($this->equalTo($createdForeignKeys[0]), $this->equalTo($this->tableDiff->getNewAsset()->getName()))
            ->will($this->returnValue(array('CREATE FOREIGN KEY')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateCheckSQLQueries')
            ->with($this->equalTo($createdChecks[0]), $this->equalTo($this->tableDiff->getNewAsset()->getName()))
            ->will($this->returnValue(array('CREATE CHECK')));

        $this->getSQLCollector()->collect($this->tableDiff);

        $this->assertSame(array('RENAME TABLE'), $this->getSQLCollector()->getRenameTableQueries());
        $this->assertSame(array('DROP CHECK'), $this->getSQLCollector()->getDropCheckQueries());
        $this->assertSame(array('DROP FOREIGN KEY'), $this->getSQLCollector()->getDropForeignKeyQueries());
        $this->assertSame(array('DROP INDEX'), $this->getSQLCollector()->getDropIndexQueries());
        $this->assertSame(array('DROP PRIMARY KEY'), $this->getSQLCollector()->getDropPrimaryKeyQueries());
        $this->assertSame(array('DROP COLUMN'), $this->getSQLCollector()->getDropColumnQueries());
        $this->assertSame(array('ALTER COLUMN'), $this->getSQLCollector()->getAlterColumnQueries());
        $this->assertSame(array('CREATE COLUMN'), $this->getSQLCollector()->getCreateColumnQueries());
        $this->assertSame(array('CREATE PRIMARY KEY'), $this->getSQLCollector()->getCreatePrimaryKeyQueries());
        $this->assertSame(array('CREATE INDEX'), $this->getSQLCollector()->getCreateIndexQueries());
        $this->assertSame(array('CREATE FOREIGN KEY'), $this->getSQLCollector()->getCreateForeignKeyQueries());
        $this->assertSame(array('CREATE CHECK'), $this->getSQLCollector()->getCreateCheckQueries());
        $this->assertSame(
            array(
                'RENAME TABLE',
                'DROP CHECK',
                'DROP FOREIGN KEY',
                'DROP INDEX',
                'DROP PRIMARY KEY',
                'DROP COLUMN',
                'ALTER COLUMN',
                'CREATE COLUMN',
                'CREATE PRIMARY KEY',
                'CREATE INDEX',
                'CREATE FOREIGN KEY',
                'CREATE CHECK',
            ),
            $this->getSQLCollector()->getQueries()
        );
    }

    public function testInit()
    {
        $this->getPlatform()
            ->expects($this->once())
            ->method('getRenameTableSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropCheckSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropForeignKeySQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropIndexSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropPrimaryKeySQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropColumnSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getAlterColumnSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateColumnSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreatePrimaryKeySQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateIndexSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateForeignKeySQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateCheckSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getSQLCollector()->collect($this->tableDiff);
        $this->getSQLCollector()->init();

        $this->assertInitialState();
    }
}
