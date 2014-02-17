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

use Fridge\DBAL\Driver\Connection\MysqliConnection;
use Fridge\DBAL\Driver\Statement\MysqliStatement;
use Fridge\Tests\PHPUnitUtility;
use Fridge\Tests\Fixture\MySQLFixture;

/**
 * Mysqli driver statement tests.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class MysqliStatementTest extends AbstractDriverStatementTest
{
    /**
     * {@inheritdoc}
     */
    protected static function setUpFixture()
    {
        if (PHPUnitUtility::hasSettings(PHPUnitUtility::MYSQLI)) {
            return new MySQLFixture(PHPUnitUtility::MYSQLI);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpConnection()
    {
        return new MysqliConnection(
            self::getFixture()->getSettings(),
            self::getFixture()->getSetting('username'),
            self::getFixture()->getSetting('password')
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpStatement()
    {
        return new MysqliStatement(self::getFixture()->getQuery(), $this->getConnection());
    }

    /**
     * Sets up a positional statement.
     */
    private function setUpPositionalStatement()
    {
        $this->setStatement(
            new MysqliStatement(self::getFixture()->getQueryWithPositionalParameters(), $this->getConnection())
        );
    }

    /**
     * Sets up a named statement.
     */
    private function setUpNamedStatement()
    {
        $this->setStatement(
            new MysqliStatement(self::getFixture()->getQueryWithNamedParameters(), $this->getConnection())
        );
    }

    public function testStatementWithValidStatement()
    {
        $this->assertInstanceOf('\mysqli_stmt', $this->getStatement()->getMysqliStatement());
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\MysqliException
     */
    public function testStatementWithInvalidStatement()
    {
        new MysqliStatement('foo', $this->getConnection());
    }

    public function testExecuteWithoutParameters()
    {
        $this->assertTrue($this->getStatement()->execute());
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\MysqliException
     */
    public function testFetchWithInvalidStatement()
    {
        $this->getStatement()->fetch();
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\MysqliException
     * @expectedExceptionMessage The fetch mode "6" is not supported.
     */
    public function testFetchWithInvalidHydratation()
    {
        $this->getStatement()->execute();
        $this->getStatement()->fetch(\PDO::FETCH_BOUND);
    }

    public function testFetchWithNumHydratation()
    {
        $this->getStatement()->execute();

        $this->assertEquals(
            array_values(self::getFixture()->getQueryResult()),
            $this->getStatement()->fetch(\PDO::FETCH_NUM)
        );
    }

    public function testFetchWithAssocHydratation()
    {
        $this->getStatement()->execute();

        $this->assertEquals(self::getFixture()->getQueryResult(), $this->getStatement()->fetch(\PDO::FETCH_ASSOC));
    }

    public function testFetchWithBothHydratation()
    {
        $this->getStatement()->execute();

        $expected = self::getFixture()->getQueryResult();
        $expected += array_values(self::getFixture()->getQueryResult());

        $this->assertEquals($expected, $this->getStatement()->fetch());
    }

    public function testFetchWithoutHydratation()
    {
        $this->getStatement()->execute();

        $expected = self::getFixture()->getQueryResult();
        $expected += array_values(self::getFixture()->getQueryResult());

        $this->assertEquals($expected, $this->getStatement()->fetch(null));
    }

    public function testFetchColumn()
    {
        $this->getStatement()->execute();

        $queryResult = array_values(self::getFixture()->getQueryResult());

        $this->assertSame($queryResult[1], $this->getStatement()->fetchColumn(1));
    }

    public function testFetchColumnWithoutDatas()
    {
        self::getFixture()->dropDatas();
        $this->getStatement()->execute();

        $this->assertFalse($this->getStatement()->fetchColumn());
    }

    public function testFetchAll()
    {
        $this->getStatement()->execute();

        $this->assertEquals(
            array(self::getFixture()->getQueryResult()),
            $this->getStatement()->fetchAll(\PDO::FETCH_ASSOC)
        );
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\MysqliException
     * @expectedExceptionMessage The mapped type "foo" does not exist.
     */
    public function testBindParameterWithInvalidType()
    {
        $this->setUpPositionalStatement();
        $parameter = 'foo';

        $this->getStatement()->bindParam(1, $parameter, 'foo');
    }

    public function testBindPositionalParameters()
    {
        $this->setUpPositionalStatement();

        $parameters = self::getFixture()->getPositionalQueryParameters();

        foreach ($parameters as $parameter => &$value) {
            $this->assertTrue($this->getStatement()->bindParam($parameter + 1, $value));
        }

        $this->assertTrue($this->getStatement()->execute());
        $this->assertEquals(self::getFixture()->getQueryResult(), $this->getStatement()->fetch(\PDO::FETCH_ASSOC));
    }

    public function testBindNamedParametersWithColon()
    {
        $this->setUpNamedStatement();
        $parameters = self::getFixture()->getNamedQueryParameters();

        foreach ($parameters as $parameter => &$value) {
            $this->assertTrue($this->getStatement()->bindParam(':'.$parameter, $value));
        }

        $this->assertTrue($this->getStatement()->execute());
        $this->assertEquals(self::getFixture()->getQueryResult(), $this->getStatement()->fetch(\PDO::FETCH_ASSOC));
    }

    public function testBindNamedParametersWithoutColon()
    {
        $this->setUpNamedStatement();
        $parameters = self::getFixture()->getNamedQueryParameters();

        foreach ($parameters as $parameter => &$value) {
            $this->assertTrue($this->getStatement()->bindParam($parameter, $value));
        }

        $this->assertTrue($this->getStatement()->execute());
        $this->assertEquals(self::getFixture()->getQueryResult(), $this->getStatement()->fetch(\PDO::FETCH_ASSOC));
    }

    public function testBindLobParameter()
    {
        $this->setUpNamedStatement();
        $parameters = self::getFixture()->getNamedQueryParameters();

        foreach ($parameters as $parameter => &$value) {
            if ($parameter === 'cblob') {
                $resource = fopen('data://text/plain;base64,'.base64_encode($value), 'r');

                $this->assertTrue($this->getStatement()->bindParam($parameter, $resource, \PDO::PARAM_LOB));
            } else {
                $this->assertTrue($this->getStatement()->bindParam($parameter, $value));
            }
        }

        $this->assertTrue($this->getStatement()->execute());
        $this->assertEquals(self::getFixture()->getQueryResult(), $this->getStatement()->fetch(\PDO::FETCH_ASSOC));
    }

    public function testBindPositionalValues()
    {
        $this->setUpPositionalStatement();

        foreach (self::getFixture()->getPositionalQueryParameters() as $parameter => $value) {
            $this->assertTrue($this->getStatement()->bindValue($parameter + 1, $value));
        }

        $this->assertTrue($this->getStatement()->execute());
        $this->assertEquals(self::getFixture()->getQueryResult(), $this->getStatement()->fetch(\PDO::FETCH_ASSOC));
    }

    public function testBindNamedValues()
    {
        $this->setUpNamedStatement();

        foreach (self::getFixture()->getNamedQueryParameters() as $parameter => $value) {
            $this->assertTrue($this->getStatement()->bindValue(':'.$parameter, $value));
        }

        $this->assertTrue($this->getStatement()->execute());
        $this->assertEquals(self::getFixture()->getQueryResult(), $this->getStatement()->fetch(\PDO::FETCH_ASSOC));
    }

    public function testExecuteWithPositionalParameters()
    {
        $this->setUpPositionalStatement();

        $this->assertTrue($this->getStatement()->execute(self::getFixture()->getPositionalQueryParameters()));
        $this->assertEquals(self::getFixture()->getQueryResult(), $this->getStatement()->fetch(\PDO::FETCH_ASSOC));
    }

    public function testExecuteWithNamedParameters()
    {
        $this->setUpNamedStatement();

        $parameters = array();
        foreach (self::getFixture()->getNamedQueryParameters() as $key => $parameter) {
            $parameters[':'.$key] = $parameter;
        }

        $this->assertTrue($this->getStatement()->execute($parameters));
        $this->assertEquals(self::getFixture()->getQueryResult(), $this->getStatement()->fetch(\PDO::FETCH_ASSOC));
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\MysqliException
     */
    public function testExecuteWithInvalidParameters()
    {
        $this->setUpPositionalStatement();
        $this->getStatement()->execute();
    }

    public function testRownCountWithQueryStatement()
    {
        $this->getStatement()->execute();

        $this->assertSame(1, $this->getStatement()->rowCount());
    }

    public function testRowCountWithQueryUpdate()
    {
        $this->setStatement(new MysqliStatement(self::getFixture()->getUpdateQuery(), $this->getConnection()));
        $this->getStatement()->execute();

        $this->assertSame(1, $this->getStatement()->rowCount());
    }

    public function testSetFetchMode()
    {
        $this->getStatement()->setFetchMode(\PDO::FETCH_ASSOC);
        $this->getStatement()->execute();

        $this->assertEquals(self::getFixture()->getQueryResult(), $this->getStatement()->fetch(null));
    }

    public function testColumnCount()
    {
        $this->getStatement()->execute();

        $this->assertSame(count(self::getFixture()->getQueryResult()), $this->getStatement()->columnCount());
    }

    public function testCloseCursor()
    {
        $this->getStatement()->execute();

        $this->assertTrue($this->getStatement()->closeCursor());
    }

    public function testErrorCode()
    {
        try {
            $this->getStatement()->fetch();

            $this->fail();
        } catch (\Exception $e) {
            $this->assertSame($e->getCode(), $this->getStatement()->errorCode());
        }
    }

    public function testErrorInfo()
    {
        try {
            $this->getStatement()->fetch();

            $this->fail();
        } catch (\Exception $e) {
            $errorInfo = $this->getStatement()->errorInfo();

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
        $this->getStatement()->execute();

        $this->assertInstanceOf('\ArrayIterator', $this->getStatement()->getIterator());
    }
}
