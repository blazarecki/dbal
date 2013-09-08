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
use Fridge\DBAL\Schema\Diff\SchemaDiff;
use Fridge\DBAL\Schema\Diff\TableDiff;
use Fridge\DBAL\Schema\ForeignKey;
use Fridge\DBAL\Schema\Index;
use Fridge\DBAL\Schema\PrimaryKey;
use Fridge\DBAL\Schema\Sequence;
use Fridge\DBAL\Schema\Schema;
use Fridge\DBAL\Schema\Table;
use Fridge\DBAL\Schema\View;
use Fridge\DBAL\SchemaManager\SQLCollector\AlterSchemaSQLCollector;
use Fridge\DBAL\Type\Type;

/**
 * Alter schema SQL collector test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class AlterSchemaSQLCollectorTest extends AbstractSQLCollectorTestCase
{
    /** @var \Fridge\DBAL\Schema\Diff\SchemaDiff */
    private $schemaDiff;

    /**
     * {@inheritdoc}
     */
    protected function setUpSQLCollector()
    {
        return new AlterSchemaSQLCollector($this->getPlatform());
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->schemaDiff = new SchemaDiff(
            new Schema('foo'),
            new Schema('bar'),
            array(
                new Table(
                    'created',
                    array(new Column('foo', Type::getType(Type::INTEGER))),
                    null,
                    array(new ForeignKey('foo', array('foo'), 'bar', array('bar')))
                ),
            ),
            array(
                new TableDiff(
                    new Table('foo'),
                    new Table('bar'),
                    array(new Column('created', Type::getType(Type::INTEGER))),
                    array(
                        new ColumnDiff(
                            new Column('foo', Type::getType(Type::INTEGER)),
                            new Column('altered', Type::getType(Type::INTEGER)),
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
                ),
            ),
            array(
                new Table(
                    'dropped',
                    array(new Column('bar', Type::getType(Type::INTEGER))),
                    null,
                    array(new ForeignKey('bar', array('bar'), 'bar', array('bar')))
                ),
            ),
            array(new Sequence('foo', 1, 1)),
            array(new Sequence('foo', 1, 2)),
            array(new View('foo', 'SELECT * FROM foo')),
            array(new View('foo', 'SELECT foo FROM foo'))
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->schemaDiff);
    }

    /**
     * Asserts the initial state.
     */
    private function assertInitialState()
    {
        $this->assertEmpty($this->getSQLCollector()->getDropSequenceQueries());
        $this->assertEmpty($this->getSQLCollector()->getDropViewQueries());
        $this->assertEmpty($this->getSQLCollector()->getRenameTableQueries());
        $this->assertEmpty($this->getSQLCollector()->getDropIndexQueries());
        $this->assertEmpty($this->getSQLCollector()->getDropForeignKeyQueries());
        $this->assertEmpty($this->getSQLCollector()->getDropTableQueries());
        $this->assertEmpty($this->getSQLCollector()->getDropPrimaryKeyQueries());
        $this->assertEmpty($this->getSQLCollector()->getDropColumnQueries());
        $this->assertEmpty($this->getSQLCollector()->getAlterColumnQueries());
        $this->assertEmpty($this->getSQLCollector()->getCreateColumnQueries());
        $this->assertEmpty($this->getSQLCollector()->getCreatePrimaryKeyQueries());
        $this->assertEmpty($this->getSQLCollector()->getCreateIndexQueries());
        $this->assertEmpty($this->getSQLCollector()->getCreateTableQueries());
        $this->assertEmpty($this->getSQLCollector()->getCreateForeignKeyQueries());
        $this->assertEmpty($this->getSQLCollector()->getCreateViewQueries());
        $this->assertEmpty($this->getSQLCollector()->getCreateSequenceQueries());
        $this->assertEmpty($this->getSQLCollector()->getRenameSchemaQueries());
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
        $createdSequences = $this->schemaDiff->getCreatedSequences();
        $droppedSequences = $this->schemaDiff->getDroppedSequences();

        $createdViews = $this->schemaDiff->getCreatedViews();
        $droppedViews = $this->schemaDiff->getDroppedViews();

        $createdTables = $this->schemaDiff->getCreatedTables();
        $alteredTables = $this->schemaDiff->getAlteredTables();
        $droppedTables = $this->schemaDiff->getDroppedTables();

        $createdColumns = $alteredTables[0]->getCreatedColumns();
        $alteredColumns = $alteredTables[0]->getAlteredColumns();
        $droppedColumns = $alteredTables[0]->getDroppedColumns();

        // FIXME
        $droppedForeignKeys = $alteredTables[0]->getDroppedForeignKeys();
        $createdForeignKeys = $alteredTables[0]->getCreatedForeignKeys();

        $droppedIndexes = $alteredTables[0]->getDroppedIndexes();
        $createdIndexes = $alteredTables[0]->getCreatedIndexes();

        $droppedChecks = $alteredTables[0]->getDroppedChecks();
        $createdChecks = $alteredTables[0]->getCreatedChecks();

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropSequenceSQLQueries')
            ->with($this->equalTo($droppedSequences[0]))
            ->will($this->returnValue(array('DROP SEQUENCE')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropViewSQLQueries')
            ->with($this->equalTo($droppedViews[0]))
            ->will($this->returnValue(array('DROP VIEW')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getRenameTableSQLQueries')
            ->with($this->equalTo($alteredTables[0]))
            ->will($this->returnValue(array('RENAME TABLE')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropCheckSQLQueries')
            ->with($this->equalTo($droppedChecks[0]), $this->equalTo($alteredTables[0]->getNewAsset()->getName()))
            ->will($this->returnValue(array('DROP CHECK')));

        $this->getPlatform()
            ->expects($this->any())
            ->method('getDropForeignKeySQLQueries')
            ->will($this->returnValue(array('DROP FOREIGN KEY')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropIndexSQLQueries')
            ->with($this->equalTo($droppedIndexes[0]), $this->equalTo($alteredTables[0]->getNewAsset()->getName()))
            ->will($this->returnValue(array('DROP INDEX')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropPrimaryKeySQLQueries')
            ->with(
                $this->equalTo($alteredTables[0]->getDroppedPrimaryKey()),
                $this->equalTo($alteredTables[0]->getNewAsset()->getName())
            )
            ->will($this->returnValue(array('DROP PRIMARY KEY')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropTableSQLQueries')
            ->with($this->equalTo($droppedTables[0]))
            ->will($this->returnValue(array('DROP TABLE')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropColumnSQLQueries')
            ->with($this->equalTo($droppedColumns[0]), $this->equalTo($alteredTables[0]->getNewAsset()->getName()))
            ->will($this->returnValue(array('DROP COLUMN')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getAlterColumnSQLQueries')
            ->with($this->equalTo($alteredColumns[0]), $this->equalTo($alteredTables[0]->getNewAsset()->getName()))
            ->will($this->returnValue(array('ALTER COLUMN')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateColumnSQLQueries')
            ->with($this->equalTo($createdColumns[0]), $this->equalTo($alteredTables[0]->getNewAsset()->getName()))
            ->will($this->returnValue(array('CREATE COLUMN')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateTableSQLQueries')
            ->with($this->equalTo($createdTables[0]), array('foreign_key' => false))
            ->will($this->returnValue(array('CREATE TABLE')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreatePrimaryKeySQLQueries')
            ->with(
                $this->equalTo($alteredTables[0]->getCreatedPrimaryKey()),
                $this->equalTo($alteredTables[0]->getNewAsset()->getName())
            )
            ->will($this->returnValue(array('CREATE PRIMARY KEY')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateIndexSQLQueries')
            ->with($this->equalTo($createdIndexes[0]), $this->equalTo($alteredTables[0]->getNewAsset()->getName()))
            ->will($this->returnValue(array('CREATE INDEX')));

        $this->getPlatform()
            ->expects($this->any())
            ->method('getCreateForeignKeySQLQueries')
            ->will($this->returnValue(array('CREATE FOREIGN KEY')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateCheckSQLQueries')
            ->with($this->equalTo($createdChecks[0]), $this->equalTo($alteredTables[0]->getNewAsset()->getName()))
            ->will($this->returnValue(array('CREATE CHECK')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateViewSQLQueries')
            ->with($this->equalTo($createdViews[0]))
            ->will($this->returnValue(array('CREATE VIEW')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateSequenceSQLQueries')
            ->with($this->equalTo($createdSequences[0]))
            ->will($this->returnValue(array('CREATE SEQUENCE')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getRenameDatabaseSQLQueries')
            ->with($this->equalTo($this->schemaDiff))
            ->will($this->returnValue(array('RENAME SCHEMA')));

        $this->getSQLCollector()->collect($this->schemaDiff);

        $this->assertSame(array('DROP SEQUENCE'), $this->getSQLCollector()->getDropSequenceQueries());
        $this->assertSame(array('DROP VIEW'), $this->getSQLCollector()->getDropViewQueries());
        $this->assertSame(array('RENAME TABLE'), $this->getSQLCollector()->getRenameTableQueries());
        $this->assertSame(array('DROP CHECK'), $this->getSQLCollector()->getDropCheckQueries());
        $this->assertSame(
            array('DROP FOREIGN KEY', 'DROP FOREIGN KEY'),
            $this->getSQLCollector()->getDropForeignKeyQueries()
        );
        $this->assertSame(array('DROP INDEX'), $this->getSQLCollector()->getDropIndexQueries());
        $this->assertSame(array('DROP PRIMARY KEY'), $this->getSQLCollector()->getDropPrimaryKeyQueries());
        $this->assertSame(array('DROP TABLE'), $this->getSQLCollector()->getDropTableQueries());
        $this->assertSame(array('DROP COLUMN'), $this->getSQLCollector()->getDropColumnQueries());
        $this->assertSame(array('ALTER COLUMN'), $this->getSQLCollector()->getAlterColumnQueries());
        $this->assertSame(array('CREATE COLUMN'), $this->getSQLCollector()->getCreateColumnQueries());
        $this->assertSame(array('CREATE TABLE'), $this->getSQLCollector()->getCreateTableQueries());
        $this->assertSame(array('CREATE PRIMARY KEY'), $this->getSQLCollector()->getCreatePrimaryKeyQueries());
        $this->assertSame(array('CREATE INDEX'), $this->getSQLCollector()->getCreateIndexQueries());
        $this->assertSame(
            array('CREATE FOREIGN KEY', 'CREATE FOREIGN KEY'),
            $this->getSQLCollector()->getCreateForeignKeyQueries()
        );
        $this->assertSame(array('CREATE CHECK'), $this->getSQLCollector()->getCreateCheckQueries());
        $this->assertSame(array('CREATE VIEW'), $this->getSQLCollector()->getCreateViewQueries());
        $this->assertSame(array('CREATE SEQUENCE'), $this->getSQLCollector()->getCreateSequenceQueries());
        $this->assertSame(array('RENAME SCHEMA'), $this->getSQLCollector()->getRenameSchemaQueries());
        $this->assertSame(
            array(
                'DROP SEQUENCE',
                'DROP VIEW',
                'RENAME TABLE',
                'DROP CHECK',
                'DROP FOREIGN KEY',
                'DROP FOREIGN KEY',
                'DROP INDEX',
                'DROP PRIMARY KEY',
                'DROP TABLE',
                'DROP COLUMN',
                'ALTER COLUMN',
                'CREATE COLUMN',
                'CREATE TABLE',
                'CREATE PRIMARY KEY',
                'CREATE INDEX',
                'CREATE FOREIGN KEY',
                'CREATE FOREIGN KEY',
                'CREATE CHECK',
                'CREATE VIEW',
                'CREATE SEQUENCE',
                'RENAME SCHEMA',
            ),
            $this->getSQLCollector()->getQueries()
        );
    }

    public function testInit()
    {
        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropSequenceSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropViewSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getRenameTableSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropCheckSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->any())
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
            ->method('getDropTableSQLQueries')
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
            ->method('getCreateTableSQLQueries')
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
            ->expects($this->any())
            ->method('getCreateForeignKeySQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateCheckSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateViewSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getCreateSequenceSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getRenameDatabaseSQLQueries')
            ->with($this->equalTo($this->schemaDiff))
            ->will($this->returnValue(array('foo')));

        $this->getSQLCollector()->collect($this->schemaDiff);
        $this->getSQLCollector()->init();

        $this->assertInitialState();
    }
}
