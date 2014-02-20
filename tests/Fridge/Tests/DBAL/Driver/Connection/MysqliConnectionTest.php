<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Driver\Connection;

use Fridge\DBAL\Driver\Connection\MysqliConnection;
use Fridge\Tests\PHPUnitUtility;
use Fridge\Tests\Fixture\MySQLFixture;

/**
 * Mysqli driver connection tests.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class MysqliConnectionTest extends AbstractDriverConnectionTest
{
    /**
     * {@inheritdoc}
     */
    protected static function hasFixture()
    {
        return PHPUnitUtility::hasSettings(PHPUnitUtility::MYSQLI);
    }

    /**
     * {@inheritdoc}
     */
    protected static function setUpFixture()
    {
        return new MySQLFixture(PHPUnitUtility::MYSQLI);
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

    public function testConnectionWithUsernameAndPassword()
    {
        $this->setConnection(new MysqliConnection(
            array(),
            self::getFixture()->getSetting('username'),
            self::getFixture()->getSetting('password')
        ));

        $this->assertInstanceOf('\mysqli', $this->getConnection()->getMysqli());
    }

    public function testConnectionWithHost()
    {
        $this->setConnection(new MysqliConnection(
            array('host' => self::getFixture()->getSetting('host')),
            self::getFixture()->getSetting('username'),
            self::getFixture()->getSetting('password')
        ));

        $this->assertInstanceOf('\mysqli', $this->getConnection()->getMysqli());
    }

    public function testConnectionWithDatabase()
    {
        $this->setConnection(new MysqliConnection(
            array('dbname' => self::getFixture()->getSetting('dbname')),
            self::getFixture()->getSetting('username'),
            self::getFixture()->getSetting('password')
        ));

        $this->assertInstanceOf('\mysqli', $this->getConnection()->getMysqli());
    }

    public function testConnectionWithPort()
    {
        $this->setConnection(new MysqliConnection(
            array('port' => self::getFixture()->getSetting('port')),
            self::getFixture()->getSetting('username'),
            self::getFixture()->getSetting('password')
        ));

        $this->assertInstanceOf('\mysqli', $this->getConnection()->getMysqli());
    }

    public function testConnectionWithUnixSocket()
    {
        $this->setConnection(new MysqliConnection(
            array('unix_socket' => ini_get('mysqli.default_socket')),
            self::getFixture()->getSetting('username'),
            self::getFixture()->getSetting('password')
        ));

        $this->assertInstanceOf('\mysqli', $this->getConnection()->getMysqli());
    }

    public function testConnectionWithValidCharset()
    {
        $this->setConnection(new MysqliConnection(
            array('charset' => 'utf8'),
            self::getFixture()->getSetting('username'),
            self::getFixture()->getSetting('password')
        ));

        $this->assertInstanceOf('\mysqli', $this->getConnection()->getMysqli());
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\MysqliException
     */
    public function testConnectionWithInvalidCharset()
    {
        new MysqliConnection(
            array('charset' => 'foo'),
            self::getFixture()->getSetting('username'),
            self::getFixture()->getSetting('password')
        );
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\MysqliException
     */
    public function testConnectionWithInvalidParameters()
    {
        new MysqliConnection(array(), 'foo', 'bar');
    }

    public function testBeginTransaction()
    {
        $this->setUpConnection();

        $this->assertTrue($this->getConnection()->beginTransaction());
        $this->assertTrue($this->getConnection()->inTransaction());
    }

    public function testCommit()
    {
        $this->setUpConnection();

        $this->getConnection()->beginTransaction();

        $this->assertTrue($this->getConnection()->commit());
        $this->assertFalse($this->getConnection()->inTransaction());
    }

    public function testRollback()
    {
        $this->setUpConnection();

        $this->getConnection()->beginTransaction();

        $this->assertTrue($this->getConnection()->rollBack());
        $this->assertFalse($this->getConnection()->inTransaction());
    }

    public function testQuote()
    {
        $this->setUpConnection();

        $this->assertSame('\'foo\'', $this->getConnection()->quote('foo'));
    }

    public function testPrepare()
    {
        $this->setUpConnection();

        $this->assertInstanceOf(
            '\Fridge\DBAL\Driver\Statement\DriverStatementInterface',
            $this->getConnection()->query(self::getFixture()->getQuery())
        );
    }

    public function testQuery()
    {
        $this->setUpConnection();

        $this->assertInstanceOf(
            '\Fridge\DBAL\Driver\Statement\DriverStatementInterface',
            $this->getConnection()->query(self::getFixture()->getQuery())
        );
    }

    public function testExec()
    {
        $this->setUpConnection();

        $this->assertSame(1, $this->getConnection()->exec(self::getFixture()->getUpdateQuery()));
    }

    public function testLastInsertId()
    {
        $this->setUpConnection();

        $this->assertSame(0, $this->getConnection()->lastInsertId());
    }

    public function testErrorCode()
    {
        $this->setUpConnection();

        try {
            $this->getConnection()->exec('foo');

            $this->fail();
        } catch (\Exception $e) {
            $this->assertSame($e->getCode(), $this->getConnection()->errorCode());
        }
    }

    public function testErrorInfo()
    {
        $this->setUpConnection();

        try {
            $this->getConnection()->exec('foo');

            $this->fail();
        } catch (\Exception $e) {
            $errorInfo = $this->getConnection()->errorInfo();

            $this->assertArrayHasKey(0, $errorInfo);
            $this->assertSame($e->getCode(), $errorInfo[0]);

            $this->assertArrayHasKey(1, $errorInfo);
            $this->assertSame($e->getCode(), $errorInfo[1]);

            $this->assertArrayHasKey(2, $errorInfo);
            $this->assertSame($e->getMessage(), $errorInfo[2]);
        }
    }

    public function testMaxAllowedPacket()
    {
        $this->setUpConnection();

        $maxAllowedPacket = $this->getConnection()->getMaxAllowedPacket();

        $this->assertInternalType('int', $maxAllowedPacket);
        $this->assertGreaterThan(0, $maxAllowedPacket);
    }
}
