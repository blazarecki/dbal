<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Driver\Statement;

use Exception;
use Fridge\DBAL\Driver\Connection\MysqliConnection;
use Fridge\DBAL\Driver\Statement\MysqliStatement;
use Fridge\Tests\PHPUnitUtility;
use Fridge\Tests\Fixture\MySQLFixture;
use PDO;

/**
 * Mysqli statement tests.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class MysqliStatementTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\Tests\Fixture\FixtureInterface */
    protected static $fixture;

    /** @var \Fridge\DBAL\Driver\Statement\MysqliStatement */
    protected $statement;

    /** @var \Fridge\DBAL\Driver\Connection\MysqliConnection */
    protected $connection;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        if (PHPUnitUtility::hasSettings(PHPUnitUtility::MYSQLI)) {
            self::$fixture = new MySQLFixture(PHPUnitUtility::MYSQLI);
            self::$fixture->create();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterCLass()
    {
        if (self::$fixture !== null) {
            self::$fixture->drop();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        if (self::$fixture === null) {
            $this->markTestSkipped();
        }

        self::$fixture->createDatas();

        $settings = self::$fixture->getSettings();
        $this->connection = new MysqliConnection($settings, $settings['username'], $settings['password']);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        unset($this->statement);
        unset($this->connection);
    }

    public function testStatementWithValidStatement()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);

        $this->assertInstanceOf('\mysqli_stmt', $this->statement->getMysqliStatement());
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\MysqliException
     */
    public function testStatementWithInvalidStatement()
    {
        $this->statement = new MysqliStatement('foo', $this->connection);
    }

    public function testExecuteWithoutParameters()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);

        $this->assertTrue($this->statement->execute());
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\MysqliException
     */
    public function testFetchWithInvalidStatement()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->fetch();
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\MysqliException
     * @expectedExceptionMessage The fetch mode "6" is not supported.
     */
    public function testFetchWithInvalidHydratation()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->execute();

        $this->statement->fetch(PDO::FETCH_BOUND);
    }

    public function testFetchWithNumHydratation()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->execute();

        $this->assertEquals(array_values(self::$fixture->getQueryResult()), $this->statement->fetch(PDO::FETCH_NUM));
    }

    public function testFetchWithAssocHydratation()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->execute();

        $this->assertEquals(self::$fixture->getQueryResult(), $this->statement->fetch(PDO::FETCH_ASSOC));
    }

    public function testFetchWithBothHydratation()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->execute();

        $expected = self::$fixture->getQueryResult();
        $expected += array_values(self::$fixture->getQueryResult());

        $this->assertEquals($expected, $this->statement->fetch());
    }

    public function testFetchWithoutHydratation()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->execute();

        $expected = self::$fixture->getQueryResult();
        $expected += array_values(self::$fixture->getQueryResult());

        $this->assertEquals($expected, $this->statement->fetch(null));
    }

    public function testFetchColumn()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->execute();

        $queryResult = array_values(self::$fixture->getQueryResult());

        $this->assertSame($queryResult[1], $this->statement->fetchColumn(1));
    }

    public function testFetchColumnWithoutDatas()
    {
        self::$fixture->dropDatas();

        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->execute();

        $this->assertFalse($this->statement->fetchColumn());
    }

    public function testFetchAll()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->execute();

        $this->assertEquals(array(self::$fixture->getQueryResult()), $this->statement->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\MysqliException
     * @expectedExceptionMessage The mapped type "foo" does not exist.
     */
    public function testBindParameterWithInvalidType()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQueryWithPositionalParameters(), $this->connection);
        $parameter = 'foo';

        $this->statement->bindParam(1, $parameter, 'foo');
    }

    public function testBindPositionalParameters()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQueryWithPositionalParameters(), $this->connection);

        $parameters = self::$fixture->getPositionalQueryParameters();

        foreach ($parameters as $parameter => &$value) {
            $this->assertTrue($this->statement->bindParam($parameter + 1, $value));
        }

        $this->assertTrue($this->statement->execute());
        $this->assertEquals(self::$fixture->getQueryResult(), $this->statement->fetch(PDO::FETCH_ASSOC));
    }

    public function testBindNamedParametersWithColon()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQueryWithNamedParameters(), $this->connection);

        $parameters = self::$fixture->getNamedQueryParameters();

        foreach ($parameters as $parameter => &$value) {
            $this->assertTrue($this->statement->bindParam(':'.$parameter, $value));
        }

        $this->assertTrue($this->statement->execute());
        $this->assertEquals(self::$fixture->getQueryResult(), $this->statement->fetch(PDO::FETCH_ASSOC));
    }

    public function testBindNamedParametersWithoutColon()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQueryWithNamedParameters(), $this->connection);

        $parameters = self::$fixture->getNamedQueryParameters();

        foreach ($parameters as $parameter => &$value) {
            $this->assertTrue($this->statement->bindParam($parameter, $value));
        }

        $this->assertTrue($this->statement->execute());
        $this->assertEquals(self::$fixture->getQueryResult(), $this->statement->fetch(PDO::FETCH_ASSOC));
    }

    public function testBindLobParameter()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQueryWithNamedParameters(), $this->connection);

        $parameters = self::$fixture->getNamedQueryParameters();

        foreach ($parameters as $parameter => &$value) {
            if ($parameter === 'cblob') {
                $resource = fopen('data://text/plain;base64,'.base64_encode($value), 'r');

                $this->assertTrue($this->statement->bindParam($parameter, $resource, PDO::PARAM_LOB));
            } else {
                $this->assertTrue($this->statement->bindParam($parameter, $value));
            }
        }

        $this->assertTrue($this->statement->execute());
        $this->assertEquals(self::$fixture->getQueryResult(), $this->statement->fetch(PDO::FETCH_ASSOC));
    }

    public function testBindPositionalValues()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQueryWithPositionalParameters(), $this->connection);

        foreach (self::$fixture->getPositionalQueryParameters() as $parameter => $value) {
            $this->assertTrue($this->statement->bindValue($parameter + 1, $value));
        }

        $this->assertTrue($this->statement->execute());
        $this->assertEquals(self::$fixture->getQueryResult(), $this->statement->fetch(PDO::FETCH_ASSOC));
    }

    public function testBindNamedValues()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQueryWithNamedParameters(), $this->connection);

        foreach (self::$fixture->getNamedQueryParameters() as $parameter => $value) {
            $this->assertTrue($this->statement->bindValue(':'.$parameter, $value));
        }

        $this->assertTrue($this->statement->execute());
        $this->assertEquals(self::$fixture->getQueryResult(), $this->statement->fetch(PDO::FETCH_ASSOC));
    }

    public function testExecuteWithPositionalParameters()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQueryWithPositionalParameters(), $this->connection);

        $this->assertTrue($this->statement->execute(self::$fixture->getPositionalQueryParameters()));
        $this->assertEquals(self::$fixture->getQueryResult(), $this->statement->fetch(PDO::FETCH_ASSOC));
    }

    public function testExecuteWithNamedParameters()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQueryWithNamedParameters(), $this->connection);

        $parameters = array();
        foreach (self::$fixture->getNamedQueryParameters() as $key => $parameter) {
            $parameters[':'.$key] = $parameter;
        }

        $this->assertTrue($this->statement->execute($parameters));
        $this->assertEquals(self::$fixture->getQueryResult(), $this->statement->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\MysqliException
     */
    public function testExecuteWithInvalidParameters()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQueryWithPositionalParameters(), $this->connection);
        $this->statement->execute();
    }

    public function testRownCountWithQueryStatement()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->execute();

        $this->assertSame(1, $this->statement->rowCount());
    }

    public function testRowCountWithQueryUpdate()
    {
        $this->statement = new MysqliStatement(self::$fixture->getUpdateQuery(), $this->connection);
        $this->statement->execute();

        $this->assertSame(1, $this->statement->rowCount());
    }

    public function testSetFetchMode()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->setFetchMode(PDO::FETCH_ASSOC);
        $this->statement->execute();

        $this->assertEquals(self::$fixture->getQueryResult(), $this->statement->fetch(null));
    }

    public function testColumnCount()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->execute();

        $this->assertSame(count(self::$fixture->getQueryResult()), $this->statement->columnCount());
    }

    public function testCloseCursor()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->execute();

        $this->assertTrue($this->statement->closeCursor());
    }

    public function testErrorCode()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);

        try {
            $this->statement->fetch();

            $this->fail();
        } catch (Exception $e) {
            $this->assertSame($e->getCode(), $this->statement->errorCode());
        }
    }

    public function testErrorInfo()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);

        try {
            $this->statement->fetch();

            $this->fail();
        } catch (Exception $e) {
            $errorInfo = $this->statement->errorInfo();

            $this->assertArrayHasKey(0, $errorInfo);
            $this->assertSame($e->getCode(), $errorInfo[0]);

            $this->assertArrayHasKey(1, $errorInfo);
            $this->assertSame($e->getCode(), $errorInfo[1]);

            $this->assertArrayHasKey(2, $errorInfo);
            $this->assertSame($e->getMessage(), $errorInfo[2]);
        }
    }

    public function testIterator()
    {
        $this->statement = new MysqliStatement(self::$fixture->getQuery(), $this->connection);
        $this->statement->execute();

        $this->assertInstanceOf('\ArrayIterator', $this->statement->getIterator());
    }
}
