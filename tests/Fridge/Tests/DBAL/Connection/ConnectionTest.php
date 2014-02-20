<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Connection;

use Fridge\DBAL\Connection\Connection;

/**
 * Connection tests which does not need a database.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Connection\Connection */
    private $connection;

    /** @var \Fridge\DBAL\Driver\DriverInterface */
    private $driverMock;

    /** @var \Fridge\DBAL\Platform\PlatformInterface */
    private $platformMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->platformMock = $this->getMock('Fridge\DBAL\Platform\PlatformInterface');
        $this->platformMock
            ->expects($this->any())
            ->method('getDefaultTransactionIsolation')
            ->will($this->returnValue('foo'));

        $this->driverMock = $this->getMock('Fridge\DBAL\Driver\DriverInterface');
        $this->driverMock
            ->expects($this->any())
            ->method('getPlatform')
            ->will($this->returnValue($this->platformMock));

        $this->connection = new Connection(array(), $this->driverMock);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->platformMock);
        unset($this->driverMock);
        unset($this->connection);
    }

    public function testDefaultConfiguration()
    {
        $this->assertInstanceOf('Fridge\DBAL\Configuration', $this->connection->getConfiguration());
    }

    public function testInitialConfiguration()
    {
        $configurationMock = $this->getMock('Fridge\DBAL\Configuration');
        $connection = new Connection(array(), $this->driverMock, $configurationMock);

        $this->assertSame($configurationMock, $connection->getConfiguration());
    }

    public function testDriver()
    {
        $this->assertSame($this->driverMock, $this->connection->getDriver());
    }

    public function testPlatform()
    {
        $this->assertSame($this->platformMock, $this->connection->getPlatform());
    }

    public function testSchemaManager()
    {
        $schemaManagerMock = $this->createSchemaManagerMock();

        $this->assertSame($schemaManagerMock, $this->connection->getSchemaManager());
    }

    public function testQueryBuilder()
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $this->assertInstanceOf('Fridge\DBAL\Query\QueryBuilder', $queryBuilder);
        $this->assertSame($this->connection, $queryBuilder->getConnection());
    }

    public function testExpressionBuilder()
    {
        $this->assertInstanceOf(
            'Fridge\DBAL\Query\Expression\ExpressionBuilder',
            $this->connection->getExpressionBuilder()
        );
    }

    public function testParameters()
    {
        $this->assertFalse($this->connection->hasParameters());
        $this->assertEmpty($this->connection->getParameters());
        $this->assertFalse($this->connection->hasParameter('foo'));
        $this->assertNull($this->connection->getParameter('foo'));

        $parameters = array('foo' => 'bar');
        $this->connection = new Connection($parameters, $this->driverMock);

        $this->assertTrue($this->connection->hasParameters());
        $this->assertSame($parameters, $this->connection->getParameters());
        $this->assertTrue($this->connection->hasParameter('foo'));
        $this->assertSame($parameters['foo'], $this->connection->getParameter('foo'));
    }

    public function testSetParameter()
    {
        $this->connection->setParameter('foo', 'bar');

        $this->assertTrue($this->connection->hasParameters());
        $this->assertSame(array('foo' => 'bar'), $this->connection->getParameters());
        $this->assertTrue($this->connection->hasParameter('foo'));
        $this->assertSame('bar', $this->connection->getParameter('foo'));
    }

    public function testSetParameterWithNull()
    {
        $this->connection->setParameter('foo', null);

        $this->assertFalse($this->connection->hasParameters());
        $this->assertEmpty($this->connection->getParameters());
        $this->assertFalse($this->connection->hasParameter('foo'));
        $this->assertNull($this->connection->getParameter('foo'));
    }

    public function testSetParameters()
    {
        $parameters = array(
            'foo' => 'bar',
            'bar' => 'foo',
        );

        $this->connection->setParameters($parameters);

        $this->assertTrue($this->connection->hasParameters());
        $this->assertSame($parameters, $this->connection->getParameters());
    }

    public function testUsername()
    {
        $this->connection = new Connection(array('username' => 'foo'), $this->driverMock);

        $this->assertSame('foo', $this->connection->getUsername());
    }

    public function testSetUsername()
    {
        $this->connection->setUsername('foo');

        $this->assertSame('foo', $this->connection->getUsername());
    }

    public function testPassword()
    {
        $this->connection = new Connection(array('password' => 'foo'), $this->driverMock);

        $this->assertSame('foo', $this->connection->getPassword());
    }

    public function testSetPassword()
    {
        $this->connection->setPassword('foo');

        $this->assertSame('foo', $this->connection->getPassword());
    }

    public function testDatabase()
    {
        $schemaManagerMock = $this->createSchemaManagerMock();
        $schemaManagerMock
            ->expects($this->once())
            ->method('getDatabase')
            ->will($this->returnValue('foo'));

        $this->assertSame('foo', $this->connection->getDatabase());
    }

    public function testSetDatabase()
    {
        $this->connection->setDatabase('foo');

        $this->assertSame('foo', $this->connection->getParameter('dbname'));
    }

    public function testHost()
    {
        $this->connection = new Connection(array('host' => 'foo'), $this->driverMock);

        $this->assertSame('foo', $this->connection->getHost());
    }

    public function testSetHost()
    {
        $this->connection->setHost('foo');

        $this->assertSame('foo', $this->connection->getHost());
    }

    public function testPort()
    {
        $this->connection = new Connection(array('port' => 1000), $this->driverMock);

        $this->assertSame(1000, $this->connection->getPort());
    }

    public function testSetPort()
    {
        $this->connection->setPort(1000);

        $this->assertSame(1000, $this->connection->getPort());
    }

    public function testDriverOptions()
    {
        $this->connection = new Connection(array('driver_options' => array('foo')), $this->driverMock);

        $this->assertSame(array('foo'), $this->connection->getDriverOptions());
    }

    public function testSetDriverOptions()
    {
        $this->connection->setDriverOptions(array('foo'));

        $this->assertSame(array('foo'), $this->connection->getDriverOptions());
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\ConnectionException
     * @expectedExceptionMessage The connection does not support transaction isolation.
     */
    public function testTransationIsolationNotSupported()
    {
        $this->platformMock
            ->expects($this->any())
            ->method('supportTransactionIsolation')
            ->will($this->returnValue(false));

        $this->connection->setTransactionIsolation(Connection::TRANSACTION_READ_COMMITTED);
    }

    /**
     * Creates a schema manager mock and set up it on the driver mock.
     *
     * @return \Fridge\DBAL\SchemaManager\SchemaManagerInterface The created schema manager mock.
     */
    private function createSchemaManagerMock()
    {
        $schemaManagerMock = $this->getMock('Fridge\DBAL\SchemaManager\SchemaManagerInterface');

        $this->driverMock
            ->expects($this->any())
            ->method('getSchemaManager')
            ->with($this->equalTo($this->connection))
            ->will($this->returnValue($schemaManagerMock));

        return $schemaManagerMock;
    }
}
