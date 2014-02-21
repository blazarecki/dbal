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
use Fridge\DBAL\Event\Events;
use Fridge\DBAL\Type\Type;

/**
 * Connection tests which needs a database.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractConnectionTest extends AbstractConnectionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected static function setUpBeforeClassFixtureMode()
    {
        return self::MODE_CREATE;
    }

    /**
     * {@inheritdoc}
     */
    protected static function setUpFixtureMode()
    {
        return self::MODE_DATAS;
    }

    public function testConnectAndClose()
    {
        $this->assertTrue($this->getConnection()->connect());
        $this->assertTrue($this->getConnection()->isConnected());

        $this->getConnection()->close();
        $this->assertFalse($this->getConnection()->isConnected());
    }

    public function testConnectWithConnectionAlreadyEstablished()
    {
        $this->assertTrue($this->getConnection()->connect());
        $this->assertTrue($this->getConnection()->connect());
    }

    public function testConnectDispatchEvent()
    {
        $this->getConnection()
            ->getConfiguration()
            ->setEventDispatcher($eventDispatcherMock = $this->createEventDispatcherMock());

        $eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->identicalTo(Events::POST_CONNECT),
                $this->isInstanceOf('Fridge\DBAL\Event\PostConnectEvent')
            );

        $this->getConnection()->connect();
    }

    public function testDriverConnection()
    {
        $this->assertInstanceOf(
            'Fridge\DBAL\Driver\Connection\DriverConnectionInterface',
            $this->getConnection()->getDriverConnection()
        );
    }

    public function testTransactionIsolation()
    {
        $this->assertSame(
            $this->getConnection()->getPlatform()->getDefaultTransactionIsolation(),
            $this->getConnection()->getTransactionIsolation()
        );

        if (!$this->getConnection()->getPlatform()->supportTransactionIsolations()) {
            $this->setExpectedException('Fridge\DBAL\Exception\ConnectionException');
        }

        $this->getConnection()->setTransactionIsolation(Connection::TRANSACTION_READ_COMMITTED);
        $this->assertSame(Connection::TRANSACTION_READ_COMMITTED, $this->getConnection()->getTransactionIsolation());
    }

    public function testCharset()
    {
        $this->getConnection()->setCharset('utf8');
    }

    public function testExecuteQueryWithoutParameters()
    {
        $this->assertQueryResult(
            self::getFixture()->getQueryResult(),
            $this->getConnection()->executeQuery(self::getFixture()->getQuery())->fetch(\PDO::FETCH_ASSOC)
        );
    }

    public function testExecuteQueryDoesNotDispatchEventWithoutDebug()
    {
        $this->assertNoQueryDebug();

        $this->getConnection()->executeQuery(self::getFixture()->getQuery());
    }

    public function testExecuteQueryDispatchEventWithDebug()
    {
        $this->getConnection()->getConfiguration()->setDebug(true);

        $this->assertQueryDebug();

        $this->getConnection()->executeQuery(self::getFixture()->getQuery());
    }

    public function testExecuteQueryWithNamedParameters()
    {
        $this->assertQueryResult(
            self::getFixture()->getQueryResult(),
            $this->getConnection()->executeQuery(
                self::getFixture()->getQueryWithNamedParameters(),
                self::getFixture()->getNamedQueryParameters()
            )->fetch(\PDO::FETCH_ASSOC)
        );
    }

    public function testExecuteQueryWithNamedTypedParameters()
    {
        $this->assertQueryResult(
            self::getFixture()->getQueryResult(),
            $this->getConnection()->executeQuery(
                self::getFixture()->getQueryWithNamedParameters(),
                self::getFixture()->getNamedTypedQueryParameters(),
                self::getFixture()->getNamedQueryTypes()
            )->fetch(\PDO::FETCH_ASSOC)
        );
    }

    public function testExecuteQueryWithPartialNamedTypedParameters()
    {
        $this->assertQueryResult(
            self::getFixture()->getQueryResult(),
            $this->getConnection()->executeQuery(
                self::getFixture()->getQueryWithNamedParameters(),
                self::getFixture()->getNamedTypedQueryParameters(),
                self::getFixture()->getPartialNamedQueryTypes()
            )->fetch(\PDO::FETCH_ASSOC)
        );
    }

    public function testExecuteQueryWithPositionalParameters()
    {
        $this->assertQueryResult(
            self::getFixture()->getQueryResult(),
            $this->getConnection()->executeQuery(
                self::getFixture()->getQueryWithPositionalParameters(),
                self::getFixture()->getPositionalQueryParameters()
            )->fetch(\PDO::FETCH_ASSOC)
        );
    }

    public function testExecuteQueryWithPositionalTypedParameters()
    {
        $this->assertQueryResult(
            self::getFixture()->getQueryResult(),
            $this->getConnection()->executeQuery(
                self::getFixture()->getQueryWithPositionalParameters(),
                self::getFixture()->getPositionalTypedQueryParameters(),
                self::getFixture()->getPositionalQueryTypes()
            )->fetch(\PDO::FETCH_ASSOC)
        );
    }

    public function testExecuteQueryWithPartialPositionalTypedParameters()
    {
        $this->assertQueryResult(
            self::getFixture()->getQueryResult(),
            $this->getConnection()->executeQuery(
                self::getFixture()->getQueryWithPositionalParameters(),
                self::getFixture()->getPositionalTypedQueryParameters(),
                self::getFixture()->getPartialPositionalQueryTypes()
            )->fetch(\PDO::FETCH_ASSOC)
        );
    }

    public function testExecuteUpdateWithoutParameters()
    {
        $this->assertUpdateResult($this->getConnection()->executeUpdate(self::getFixture()->getUpdateQuery()));
    }

    public function testExecuteUpdateDoesNotDispatchEventWithoutDebug()
    {
        $this->assertNoQueryDebug();

        $this->getConnection()->executeUpdate(self::getFixture()->getUpdateQuery());
    }

    public function testExecuteUpdateDispatchEventWithDebug()
    {
        $this->getConnection()->getConfiguration()->setDebug(true);

        $this->assertQueryDebug();

        $this->getConnection()->executeUpdate(self::getFixture()->getUpdateQuery());
    }

    public function testExecuteUpdateWithNamedParameters()
    {
        $this->assertUpdateResult($this->getConnection()->executeUpdate(
            self::getFixture()->getUpdateQueryWithNamedParameters(),
            self::getFixture()->getNamedQueryParameters()
        ));
    }

    public function testExecuteUpdateWithNamedTypedParameters()
    {
        $this->assertUpdateResult($this->getConnection()->executeUpdate(
            self::getFixture()->getUpdateQueryWithNamedParameters(),
            self::getFixture()->getNamedTypedQueryParameters(),
            self::getFixture()->getNamedQueryTypes()
        ));
    }

    public function testExecuteUpdateWithPositionalParameters()
    {
        $this->assertUpdateResult($this->getConnection()->executeUpdate(
            self::getFixture()->getUpdateQueryWithPositionalParameters(),
            self::getFixture()->getPositionalQueryParameters()
        ));
    }

    public function testExecuteUpdateWithPositionalTypedParameters()
    {
        $this->assertUpdateResult($this->getConnection()->executeUpdate(
            self::getFixture()->getUpdateQueryWithPositionalParameters(),
            self::getFixture()->getPositionalTypedQueryParameters(),
            self::getFixture()->getPositionalQueryTypes()
        ));
    }

    public function testFetchAll()
    {
        $results = $this->getConnection()->fetchAll(
            self::getFixture()->getQueryWithNamedParameters(),
            self::getFixture()->getNamedTypedQueryParameters(),
            self::getFixture()->getNamedQueryTypes()
        );

        $this->assertCount(1, $results);
        $this->assertArrayHasKey(0, $results);
        $this->assertQueryResult(self::getFixture()->getQueryResult(), $results[0]);
    }

    public function testFetchArray()
    {
        $this->assertQueryResult(
            array_values(self::getFixture()->getQueryResult()),
            $this->getConnection()->fetchArray(
                self::getFixture()->getQueryWithNamedParameters(),
                self::getFixture()->getNamedTypedQueryParameters(),
                self::getFixture()->getNamedQueryTypes()
            )
        );
    }

    public function testFetchAssoc()
    {
        $this->assertQueryResult(
            self::getFixture()->getQueryResult(),
            $this->getConnection()->fetchAssoc(
                self::getFixture()->getQueryWithNamedParameters(),
                self::getFixture()->getNamedTypedQueryParameters(),
                self::getFixture()->getNamedQueryTypes()
            )
        );
    }

    public function testFetchColumn()
    {
        $queryResult = self::getFixture()->getQueryResult();

        $this->assertSame(
            $queryResult['carray'],
            $this->getConnection()->fetchColumn(
                self::getFixture()->getQueryWithNamedParameters(),
                self::getFixture()->getNamedTypedQueryParameters(),
                self::getFixture()->getNamedQueryTypes()
            )
        );
    }

    public function testInsertWithTypedParameters()
    {
        $this->assertUpdateResult($this->getConnection()->insert(
            'tcolumns',
            self::getFixture()->getNamedTypedQueryParameters(),
            self::getFixture()->getNamedQueryTypes()
        ));
    }

    public function testInsertWithPartialTypedParameters()
    {
        $this->assertUpdateResult($this->getConnection()->insert(
            'tcolumns',
            self::getFixture()->getNamedTypedQueryParameters(),
            self::getFixture()->getPartialNamedQueryTypes()
        ));
    }

    public function testUpdateWithoutExpression()
    {
        $this->assertUpdateResult($this->getConnection()->update(
            'tcolumns',
            array_merge(self::getFixture()->getNamedTypedQueryParameters(), array('cboolean' => false)),
            self::getFixture()->getNamedQueryTypes()
        ));
    }

    public function testUpdateWithTypedPositionalExpressionParameters()
    {
        $datas = self::getFixture()->getNamedTypedQueryParameters();

        $this->assertUpdateResult($this->getConnection()->update(
            'tcolumns',
            array_merge($datas, array('cboolean' => false)),
            self::getFixture()->getNamedQueryTypes(),
            'carray = ?',
            array($datas['carray']),
            array(Type::TARRAY)
        ));
    }

    public function testUpdateWithTypedNamedExpressionParameters()
    {
        $datas = self::getFixture()->getNamedTypedQueryParameters();

        $this->assertUpdateResult($this->getConnection()->update(
            'tcolumns',
            array_merge($datas, array('cboolean' => false)),
            self::getFixture()->getNamedQueryTypes(),
            'carray = :carrayParameter',
            array('carrayParameter' => $datas['carray']),
            array('carrayParameter' => Type::TARRAY)
        ));
    }

    public function testDeleteWithoutExpression()
    {
        $this->assertUpdateResult($this->getConnection()->delete('tcolumns'));
    }

    public function testDeleteWithTypedExpressionParameters()
    {
        $this->assertUpdateResult($this->getConnection()->delete(
            'tcolumns',
            'carray = :carrayParameter',
            array('carrayParameter' => array('foo' => 'bar')),
            array('carrayParameter' => Type::TARRAY)
        ));
    }

    public function testBeginTransaction()
    {
        $this->assertFalse($this->getConnection()->inTransaction());
        $this->assertSame(0, $this->getConnection()->getTransactionLevel());

        $this->getConnection()->beginTransaction();

        $this->assertTrue($this->getConnection()->inTransaction());
        $this->assertSame(1, $this->getConnection()->getTransactionLevel());
    }

    public function testBeginTransactionDoesNotDispatchEventWithoutDebug()
    {
        $this->assertNoQueryDebug();

        $this->getConnection()->beginTransaction();
    }

    public function testBeginTransactionDispatchEventWithDebug()
    {
        $this->getConnection()->getConfiguration()->setDebug(true);

        $this->assertQueryDebug();

        $this->getConnection()->beginTransaction();
    }

    public function testTransactionWithCommit()
    {
        $this->getConnection()->beginTransaction();
        $this->getConnection()->commit();

        $this->assertFalse($this->getConnection()->inTransaction());
        $this->assertSame(0, $this->getConnection()->getTransactionLevel());
    }

    public function testTransactionWithCommitDoesNotDispatchEventWithoutDebug()
    {
        $this->getConnection()->beginTransaction();

        $this->assertNoQueryDebug();

        $this->getConnection()->commit();
    }

    public function testTransactionWithCommitDispatchEventWithDebug()
    {
        $this->getConnection()->beginTransaction();
        $this->getConnection()->getConfiguration()->setDebug(true);

        $this->assertQueryDebug();

        $this->getConnection()->commit();
    }

    public function testTransactionWithRollback()
    {
        $this->getConnection()->beginTransaction();
        $this->getConnection()->rollBack();

        $this->assertFalse($this->getConnection()->inTransaction());
        $this->assertSame(0, $this->getConnection()->getTransactionLevel());
    }

    public function testTransactionWithRollbackDoesNotDispatchEventWithoutDebug()
    {
        $this->getConnection()->beginTransaction();

        $this->assertNoQueryDebug();

        $this->getConnection()->rollBack();
    }

    public function testTransactionWithRollbackDispatchEventWithDebug()
    {
        $this->getConnection()->beginTransaction();
        $this->getConnection()->getConfiguration()->setDebug(true);

        $this->assertQueryDebug();

        $this->getConnection()->rollBack();
    }

    public function testNestedTransactionWithCommit()
    {
        $this->getConnection()->beginTransaction();
        $this->getConnection()->beginTransaction();
        $this->assertSame(2, $this->getConnection()->getTransactionLevel());

        $this->getConnection()->commit();
        $this->assertSame(1, $this->getConnection()->getTransactionLevel());

        $this->getConnection()->commit();
        $this->assertSame(0, $this->getConnection()->getTransactionLevel());
    }

    public function testNestedTransactionWithRollback()
    {
        $this->getConnection()->beginTransaction();
        $this->getConnection()->beginTransaction();
        $this->assertSame(2, $this->getConnection()->getTransactionLevel());

        $this->getConnection()->rollBack();
        $this->assertSame(1, $this->getConnection()->getTransactionLevel());

        $this->getConnection()->rollBack();
        $this->assertSame(0, $this->getConnection()->getTransactionLevel());
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\ConnectionException
     * @expectedExceptionMessage The connection does not have an active transaction.
     */
    public function testCommitWithoutTransaction()
    {
        $this->getConnection()->commit();
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\ConnectionException
     */
    public function testRollbackWithoutTransaction()
    {
        $this->getConnection()->rollback();
    }

    public function testQuoteWithDBALType()
    {
        $this->assertSame('\'foo\'', $this->getConnection()->quote('foo', Type::STRING));
    }

    public function testQuoteWithPDOType()
    {
        $this->assertSame('\'foo\'', $this->getConnection()->quote('foo', \PDO::PARAM_STR));
    }

    public function testQuery()
    {
        $this->assertQueryResult(
            self::getFixture()->getQueryResult(),
            $this->getConnection()->query(self::getFixture()->getQuery())->fetch(\PDO::FETCH_ASSOC)
        );
    }

    public function testQueryDoesNotDispatchEventWithoutDebug()
    {
        $this->assertNoQueryDebug();

        $this->assertQueryResult(
            self::getFixture()->getQueryResult(),
            $this->getConnection()->query(self::getFixture()->getQuery())->fetch(\PDO::FETCH_ASSOC)
        );
    }

    public function testQueryDispatchEventWithDebug()
    {
        $this->getConnection()->getConfiguration()->setDebug(true);

        $this->assertQueryDebug();

        $this->assertQueryResult(
            self::getFixture()->getQueryResult(),
            $this->getConnection()->query(self::getFixture()->getQuery())->fetch(\PDO::FETCH_ASSOC)
        );
    }

    public function testPrepare()
    {
        $this->assertInstanceOf(
            'Fridge\DBAL\Statement\Statement',
            $this->getConnection()->prepare(self::getFixture()->getQueryWithNamedParameters())
        );
    }

    public function testExec()
    {
        $this->assertUpdateResult($this->getConnection()->exec(self::getFixture()->getUpdateQuery()));
    }

    public function testExecDoesNotDispatchEventWithoutDebug()
    {
        $this->assertNoQueryDebug();

        $this->assertUpdateResult($this->getConnection()->exec(self::getFixture()->getUpdateQuery()));
    }

     public function testExecDispatchEventWithDebug()
    {
        $this->getConnection()->getConfiguration()->setDebug(true);

        $this->assertQueryDebug();

        $this->assertUpdateResult($this->getConnection()->exec(self::getFixture()->getUpdateQuery()));
    }

    public function testLastInsertId()
    {
        $this->getConnection()->lastInsertId();
    }

    public function testErrorCode()
    {
        try {
            $this->getConnection()->exec('foo');

            $this->fail();
        } catch (\Exception $e) {
            $this->assertTrue(ctype_digit($this->getConnection()->errorCode()));
        }
    }

    public function testErrorInfo()
    {
        try {
            $this->getConnection()->exec('foo');

            $this->fail();
        } catch (\Exception $e) {
            $errorInfo = $this->getConnection()->errorInfo();

            $this->assertArrayHasKey(0, $errorInfo);
            $this->assertTrue(ctype_digit($errorInfo[0]));

            $this->assertArrayHasKey(1, $errorInfo);
            $this->assertInternalType('int', $errorInfo[1]);

            $this->assertArrayHasKey(2, $errorInfo);
            $this->assertInternalType('string', $errorInfo[2]);
        }
    }

    /**
     * Creates an event dispatcher mock.
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface The event dispatcher mock.
     */
    private function createEventDispatcherMock()
    {
        return $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
    }

    /**
     * Asserts a query result.
     *
     * @param array $expected The expected query result.
     * @param mixed $actual   The actual query result.
     */
    private function assertQueryResult(array $expected, $actual)
    {
        $this->assertInternalType('array', $actual);
        $this->assertCount(count($expected), $actual);

        foreach ($expected as $key => $result) {
            $this->assertArrayHasKey($key, $actual);

            if (is_resource($actual[$key])) {
                $actual[$key] = fread($actual[$key], strlen($result));
            }

            $this->assertEquals($result, $actual[$key]);
        }
    }

    /**
     * Asserts an update result.
     *
     * @param integer $actual The expected affected rows.
     */
    private function assertUpdateResult($actual)
    {
        $this->assertSame(1, $actual);
    }

    /**
     * Asserts a query debug.
     */
    private function assertQueryDebug()
    {
        $this->getConnection()->connect();

        $this->getConnection()
            ->getConfiguration()
            ->setEventDispatcher($eventDispatcherMock = $this->createEventDispatcherMock());

        $eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->identicalTo(Events::QUERY_DEBUG),
                $this->isInstanceOf('Fridge\DBAL\Event\QueryDebugEvent')
            );
    }

    /**
     * Asserts no query debug.
     */
    private function assertNoQueryDebug()
    {
        $this->getConnection()->connect();

        $this->getConnection()
            ->getConfiguration()
            ->setEventDispatcher($eventDispatcherMock = $this->createEventDispatcherMock());

        $eventDispatcherMock
            ->expects($this->never())
            ->method('dispatch');
    }
}
