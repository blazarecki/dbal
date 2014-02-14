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
 * Executes the functional connection test suite on a specific database.
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

    /**
     * Asserts a query result.
     *
     * @param array $expectedResult The expected result.
     * @param mixed $actualResult   The actual result.
     */
    private function assertQueryResult(array $expectedResult, $actualResult)
    {
        $this->assertInternalType('array', $actualResult);

        $this->assertCount(count($expectedResult), $actualResult);

        foreach ($expectedResult as $key => $result) {
            $this->assertArrayHasKey($key, $actualResult);

            if (is_resource($actualResult[$key])) {
                $actualResult[$key] = fread($actualResult[$key], strlen($result));
            }

            $this->assertEquals($result, $actualResult[$key]);
        }
    }

    public function testConnectAndClose()
    {
        $this->assertTrue($this->getConnection()->connect());
        $this->assertTrue($this->getConnection()->isConnected());

        $this->getConnection()->close();
        $this->assertFalse($this->getConnection()->isConnected());
    }

    public function testConnectIfConnectionIsAlreadyEstablished()
    {
        $this->getConnection()->connect();
        $this->assertTrue($this->getConnection()->connect());
    }

    public function testConnectDispatchEvent()
    {
        $eventDispatcherMock = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
        $eventDispatcherMock
            ->expects($this->any())
            ->method('hasListeners')
            ->with($this->equalTo(Events::POST_CONNECT))
            ->will($this->returnValue(true));

        $eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->identicalTo(Events::POST_CONNECT),
                $this->isInstanceOf('Fridge\DBAL\Event\PostConnectEvent')
            );

        $this->getConnection()->getConfiguration()->setEventDispatcher($eventDispatcherMock);

        $this->getConnection()->connect();
    }

    public function testNativeConnection()
    {
        $this->assertInstanceOf(
            'Fridge\DBAL\Driver\Connection\NativeConnectionInterface',
            $this->getConnection()->getNativeConnection()
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
        $this->getConnection()->connect();

        $eventDispatcherMock = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
        $eventDispatcherMock
            ->expects($this->never())
            ->method('dispatch');

        $this->getConnection()->getConfiguration()->setEventDispatcher($eventDispatcherMock);

        $this->getConnection()->executeQuery(self::getFixture()->getQuery());
    }

    public function testExecuteQueryDispatchEventWithDebug()
    {
        $this->getConnection()->connect();

        $eventDispatcherMock = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
        $eventDispatcherMock
            ->expects($this->any())
            ->method('hasListeners')
            ->with($this->equalTo(Events::QUERY_DEBUG))
            ->will($this->returnValue(true));

        $eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->identicalTo(Events::QUERY_DEBUG),
                $this->isInstanceOf('Fridge\DBAL\Event\QueryDebugEvent')
            );;

        $this->getConnection()->getConfiguration()->setDebug(true);
        $this->getConnection()->getConfiguration()->setEventDispatcher($eventDispatcherMock);

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
        $this->assertSame(1, $this->getConnection()->executeUpdate(self::getFixture()->getUpdateQuery()));
    }

    public function testExecuteUpdateDoesNotDispatchEventWithoutDebug()
    {
        $this->getConnection()->connect();

        $eventDispatcherMock = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
        $eventDispatcherMock
            ->expects($this->never())
            ->method('dispatch');

        $this->getConnection()->getConfiguration()->setEventDispatcher($eventDispatcherMock);

        $this->getConnection()->executeUpdate(self::getFixture()->getUpdateQuery());
    }

    public function testExecuteUpdateDispatchEventWithDebug()
    {
        $this->getConnection()->connect();

        $eventDispatcherMock = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
        $eventDispatcherMock
            ->expects($this->any())
            ->method('hasListeners')
            ->with($this->equalTo(Events::QUERY_DEBUG))
            ->will($this->returnValue(true));

        $eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->equalTo(Events::QUERY_DEBUG),
                $this->isInstanceOf('Fridge\DBAL\Event\QueryDebugEvent')
            );

        $this->getConnection()->getConfiguration()->setDebug(true);
        $this->getConnection()->getConfiguration()->setEventDispatcher($eventDispatcherMock);

        $this->getConnection()->executeUpdate(self::getFixture()->getUpdateQuery());
    }

    public function testExecuteUpdateWithNamedParameters()
    {
        $count = $this->getConnection()->executeUpdate(
            self::getFixture()->getUpdateQueryWithNamedParameters(),
            self::getFixture()->getNamedQueryParameters()
        );

        $this->assertSame(1, $count);
    }

    public function testExecuteUpdateWithNamedTypedParameters()
    {
        $count = $this->getConnection()->executeUpdate(
            self::getFixture()->getUpdateQueryWithNamedParameters(),
            self::getFixture()->getNamedTypedQueryParameters(),
            self::getFixture()->getNamedQueryTypes()
        );

        $this->assertSame(1, $count);
    }

    public function testExecuteUpdateWithPositionalParameters()
    {
        $count = $this->getConnection()->executeUpdate(
            self::getFixture()->getUpdateQueryWithPositionalParameters(),
            self::getFixture()->getPositionalQueryParameters()
        );

        $this->assertSame(1, $count);
    }

    public function testExecuteUpdateWithPositionalTypedParameters()
    {
        $count = $this->getConnection()->executeUpdate(
            self::getFixture()->getUpdateQueryWithPositionalParameters(),
            self::getFixture()->getPositionalTypedQueryParameters(),
            self::getFixture()->getPositionalQueryTypes()
        );

        $this->assertSame(1, $count);
    }

    public function testFetchAll()
    {
        $expected = array(self::getFixture()->getQueryResult());

        $results = $this->getConnection()->fetchAll(
            self::getFixture()->getQueryWithNamedParameters(),
            self::getFixture()->getNamedTypedQueryParameters(),
            self::getFixture()->getNamedQueryTypes()
        );

        $this->assertCount(count($expected), $results);

        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $results);
            $this->assertQueryResult($value, $results[$key]);
        }
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
        $count = $this->getConnection()->insert(
            'tcolumns',
            self::getFixture()->getNamedTypedQueryParameters(),
            self::getFixture()->getNamedQueryTypes()
        );

        $this->assertSame(1, $count);
    }

    public function testInsertWithPartialTypedParameters()
    {
        $count = $this->getConnection()->insert(
            'tcolumns',
            self::getFixture()->getNamedTypedQueryParameters(),
            self::getFixture()->getPartialNamedQueryTypes()
        );

        $this->assertSame(1, $count);
    }

    public function testUpdateWithoutExpression()
    {
        $datas = array_merge(self::getFixture()->getNamedTypedQueryParameters(), array('carray' => array('bar' => 'foo')));
        $count = $this->getConnection()->update('tcolumns', $datas, self::getFixture()->getNamedQueryTypes());

        $this->assertSame(1, $count);
    }

    public function testUpdateWithTypedPositionalExpressionParameters()
    {
        $originalDatas = self::getFixture()->getNamedTypedQueryParameters();
        $datas = array_merge($originalDatas, array('carray' => array('bar' => 'foo')));

        $count = $this->getConnection()->update(
            'tcolumns',
            $datas,
            self::getFixture()->getNamedQueryTypes(),
            'carray = ?',
            array($originalDatas['carray']),
            array(Type::TARRAY)
        );

        $this->assertSame(1, $count);
    }

    public function testUpdateWithTypedNamedExpressionParameters()
    {
        $originalDatas = self::getFixture()->getNamedTypedQueryParameters();
        $datas = array_merge($originalDatas, array('carray' => array('bar' => 'foo')));

        $count = $this->getConnection()->update(
            'tcolumns',
            $datas,
            self::getFixture()->getNamedQueryTypes(),
            'carray = :carrayParameter',
            array('carrayParameter' => $originalDatas['carray']),
            array('carrayParameter' => Type::TARRAY)
        );

        $this->assertSame(1, $count);
    }

    public function testDeleteWithoutExpression()
    {
        $this->assertSame(1, $this->getConnection()->delete('tcolumns'));
    }

    public function testDeleteWithTypedExpressionParameters()
    {
        $count = $this->getConnection()->delete(
            'tcolumns',
            'carray = :carrayParameter',
            array('carrayParameter' => array('foo' => 'bar')),
            array('carrayParameter' => Type::TARRAY)
        );

        $this->assertSame(1, $count);
    }

    public function testBeginTransaction()
    {
        $this->assertFalse($this->getConnection()->inTransaction());
        $this->assertSame(0, $this->getConnection()->getTransactionLevel());

        $this->getConnection()->beginTransaction();

        $this->assertTrue($this->getConnection()->inTransaction());
        $this->assertSame(1, $this->getConnection()->getTransactionLevel());
    }

    public function testTransactionWithCommit()
    {
        $this->getConnection()->beginTransaction();

        try {
            $this->getConnection()->commit();
        } catch (\Exception $e) {
            $this->getConnection()->rollBack();

            $this->fail($e->getMessage());
        }

        $this->assertFalse($this->getConnection()->inTransaction());
        $this->assertSame(0, $this->getConnection()->getTransactionLevel());
    }

    public function testTransactionWithRollback()
    {
        $this->getConnection()->beginTransaction();

        try {
            throw new \Exception();
        } catch (\Exception $e) {
            $this->getConnection()->rollBack();
        }

        $this->assertFalse($this->getConnection()->inTransaction());
        $this->assertSame(0, $this->getConnection()->getTransactionLevel());
    }

    public function testNestedTransactionWithCommit()
    {
        $this->getConnection()->beginTransaction();

        try {
            $this->getConnection()->beginTransaction();
            $this->assertSame(2, $this->getConnection()->getTransactionLevel());

            try {
                $this->getConnection()->commit();
            } catch (\Exception $e) {
                $this->getConnection()->rollBack();

                $this->fail($e->getMessage());
            }

            $this->assertSame(1, $this->getConnection()->getTransactionLevel());

            $this->getConnection()->commit();
        } catch (\Exception $e) {
            $this->getConnection()->rollBack();

            if ($this->getConnection()->getPlatform()->supportSavepoints()) {
                $this->fail($e->getMessage());
            }
        }

        $this->assertFalse($this->getConnection()->inTransaction());
    }

    public function testNestedTransactionWithRollback()
    {
        $this->getConnection()->beginTransaction();

        try {
            $this->getConnection()->beginTransaction();
            $this->assertSame(2, $this->getConnection()->getTransactionLevel());

            try {
                throw new \Exception();
            } catch (\Exception $e) {
                $this->getConnection()->rollBack();
            }

            $this->assertSame(1, $this->getConnection()->getTransactionLevel());

            throw new \Exception();
        } catch (\Exception $e) {
            $this->getConnection()->rollBack();
        }

        $this->assertFalse($this->getConnection()->inTransaction());
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

    public function testPrepare()
    {
        $this->assertInstanceOf(
            'Fridge\DBAL\Statement\Statement',
            $this->getConnection()->prepare(self::getFixture()->getQueryWithNamedParameters())
        );
    }

    public function testExec()
    {
        $this->assertSame(1, $this->getConnection()->exec(self::getFixture()->getUpdateQuery()));
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
            $this->assertSame($e->getCode(), $this->getConnection()->errorCode());
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
            $this->assertSame($e->getCode(), $errorInfo[0]);

            $this->assertArrayHasKey(1, $errorInfo);
            $this->assertInternalType('int', $errorInfo[1]);

            $this->assertArrayHasKey(2, $errorInfo);
            $this->assertInternalType('string', $errorInfo[2]);
        }
    }
}
