<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Statement;

use Fridge\DBAL\Statement\Statement;
use Fridge\DBAL\Type\Type;

/**
 * Statement test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class StatementTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Statement\Statement */
    private $statement;

    /** @var \Fridge\DBAL\Driver\Statement\DriverStatementInterface */
    private $driverStatementMock;

    /** @var \Fridge\DBAL\Connection\ConnectionInterface */
    private $connectionMock;

    /** @var \Fridge\DBAL\Driver\Connection\DriverConnectionInterface */
    private $driverConnectionMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->driverStatementMock = $this->getMock('\Fridge\DBAL\Driver\Statement\DriverStatementInterface');

        $this->driverConnectionMock = $this->getMock('\Fridge\DBAL\Driver\Connection\DriverConnectionInterface');
        $this->driverConnectionMock
            ->expects($this->any())
            ->method('prepare')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue($this->driverStatementMock));

        $this->connectionMock = $this->getMock('\Fridge\DBAL\Connection\ConnectionInterface');
        $this->connectionMock
            ->expects($this->any())
            ->method('getDriverConnection')
            ->will($this->returnValue($this->driverConnectionMock));

        $this->statement = new Statement('foo', $this->connectionMock);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->driverConnectionMock);
        unset($this->connectionMock);
        unset($this->driverStatementMock);
        unset($this->statement);
    }

    public function testInitialState()
    {
        $this->assertSame($this->driverStatementMock, $this->statement->getDriverStatement());
        $this->assertSame($this->connectionMock, $this->statement->getConnection());
        $this->assertSame('foo', $this->statement->getSQL());
    }

    public function testIterator()
    {
        $this->assertSame($this->driverStatementMock, $this->statement->getIterator());
    }

    public function testBindParam()
    {
        $parameter = 'foo';
        $variable = 'bar';
        $type = 'foobar';

        $this->driverStatementMock
            ->expects($this->once())
            ->method('bindParam')
            ->with(
                $this->equalTo($parameter),
                $this->equalTo($variable),
                $this->equalTo($type)
            )
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->bindParam($parameter, $variable, $type));
    }

    public function testBindValueWithoutType()
    {
        $platformMock = $this->getMock('\Fridge\DBAL\Platform\PlatformInterface');

        $this->connectionMock
            ->expects($this->once())
            ->method('getPlatform')
            ->will($this->returnValue($platformMock));

        $this->driverStatementMock
            ->expects($this->once())
            ->method('bindValue')
            ->with(
                $this->equalTo('foo'),
                $this->equalTo('bar'),
                $this->equalTo(\PDO::PARAM_STR)
            )
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->bindValue('foo', 'bar'));
    }

    public function testBindValueWithPDOType()
    {
        $platformMock = $this->getMock('\Fridge\DBAL\Platform\PlatformInterface');

        $this->connectionMock
            ->expects($this->once())
            ->method('getPlatform')
            ->will($this->returnValue($platformMock));

        $this->driverStatementMock
            ->expects($this->once())
            ->method('bindValue')
            ->with(
                $this->equalTo('foo'),
                $this->equalTo('bar'),
                $this->equalTo(\PDO::PARAM_INT)
            )
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->bindValue('foo', 'bar', \PDO::PARAM_INT));
    }

    public function testBindValueWithFridgeType()
    {
        $platformMock = $this->getMock('\Fridge\DBAL\Platform\PlatformInterface');

        $this->connectionMock
            ->expects($this->once())
            ->method('getPlatform')
            ->will($this->returnValue($platformMock));

        $this->driverStatementMock
            ->expects($this->once())
            ->method('bindValue')
            ->with(
                $this->equalTo('foo'),
                $this->equalTo(true),
                $this->equalTo(\PDO::PARAM_BOOL)
            )
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->bindValue('foo', true, Type::BOOLEAN));
    }

    public function testCloseCursor()
    {
        $this->driverStatementMock
            ->expects($this->once())
            ->method('closeCursor')
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->closeCursor());
    }

    public function testColumnCount()
    {
        $this->driverStatementMock
            ->expects($this->once())
            ->method('columnCount')
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->columnCount());
    }

    public function testErrorCode()
    {
        $this->driverStatementMock
            ->expects($this->once())
            ->method('errorCode')
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->errorCode());
    }

    public function testErrorInfo()
    {
        $this->driverStatementMock
            ->expects($this->once())
            ->method('errorInfo')
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->errorInfo());
    }

    public function testExecute()
    {
        $this->driverStatementMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(array('foo')))
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->execute(array('foo')));
    }

    public function testFetch()
    {
        $this->driverStatementMock
            ->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo(1))
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->fetch(1));
    }

    public function testFetchAll()
    {
        $this->driverStatementMock
            ->expects($this->once())
            ->method('fetchAll')
            ->with($this->equalTo(1))
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->fetchAll(1, 'foo', array('bar')));
    }

    public function testFetchColumn()
    {
        $this->driverStatementMock
            ->expects($this->once())
            ->method('fetchColumn')
            ->with($this->equalTo(1))
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->fetchColumn(1));
    }

    public function testRowCount()
    {
        $this->driverStatementMock
            ->expects($this->once())
            ->method('rowCount')
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->rowCount());
    }

    public function testSetFetchMode()
    {
        $this->driverStatementMock
            ->expects($this->once())
            ->method('setFetchMode')
            ->with($this->equalTo(1))
            ->will($this->returnValue('bar'));

        $this->assertSame('bar', $this->statement->setFetchMode(1));
    }
}
