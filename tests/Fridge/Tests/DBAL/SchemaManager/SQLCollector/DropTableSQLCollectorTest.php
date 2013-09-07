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

use Fridge\DBAL\Schema\Column;
use Fridge\DBAL\Schema\ForeignKey;
use Fridge\DBAL\Schema\Table;
use Fridge\DBAL\SchemaManager\SQLCollector\DropTableSQLCollector;
use Fridge\DBAL\Type\Type;

/**
 * Drop table SQL collector test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class DropTableSQLCollectorTest extends AbstractSQLCollectorTestCase
{
    /** @var \Fridge\DBAL\Schema\Table */
    private $table;

    /**
     * {@inheritdoc}
     */
    protected function setUpSQLCollector()
    {
        return new DropTableSQLCollector($this->getPlatform());
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->table = new Table(
            'foo',
            array(new Column('foo', Type::getType(Type::INTEGER))),
            null,
            array(new ForeignKey('foo', array('foo'), 'bar', array('bar')))
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->table);
    }

    /**
     * Asserts the initial state.
     */
    private function assertInitialState()
    {
        $this->assertEmpty($this->getSQLCollector()->getDropForeignKeyQueries());
        $this->assertEmpty($this->getSQLCollector()->getDropTableQueries());
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
        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropTableSQLQueries')
            ->with($this->equalTo($this->table))
            ->will($this->returnValue(array('DROP TABLE')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropForeignKeySQLQueries')
            ->with($this->equalTo($this->table->getForeignKey('foo')), $this->equalTo($this->table->getName()))
            ->will($this->returnValue(array('DROP FOREIGN KEY')));

        $this->getSQLCollector()->collect($this->table);

        $this->assertSame(array('DROP FOREIGN KEY'), $this->getSQLCollector()->getDropForeignKeyQueries());
        $this->assertSame(array('DROP TABLE'), $this->getSQLCollector()->getDropTableQueries());
        $this->assertSame(array('DROP FOREIGN KEY', 'DROP TABLE'), $this->getSQLCollector()->getQueries());
    }

    public function testPlatformWithCollectedQueries()
    {
        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropTableSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropForeignKeySQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getSQLCollector()->collect($this->table);
        $this->getSQLCollector()->setPlatform($this->getPlatform());

        $this->assertInitialState();
    }

    public function testInit()
    {
        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropTableSQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getPlatform()
            ->expects($this->once())
            ->method('getDropForeignKeySQLQueries')
            ->will($this->returnValue(array('foo')));

        $this->getSQLCollector()->collect($this->table);
        $this->getSQLCollector()->init();

        $this->assertInitialState();
    }
}
