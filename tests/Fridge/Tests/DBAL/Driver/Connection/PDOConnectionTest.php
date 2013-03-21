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

use \PDO;

use Fridge\DBAL\Driver\Connection\PDOConnection,
    Fridge\Tests\PHPUnitUtility,
    Fridge\Tests\Fixture\MySQLFixture;

/**
 * PDO connection tests.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PDOConnectionTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\Tests\Fixture\FixtureInterface */
    static protected $fixture;

    /** @var \Fridge\DBAL\Driver\Connection\PDOConnection */
    protected $connection;

    /**
     * {@inheritdoc}
     */
    static public function setUpBeforeClass()
    {
        if (PHPUnitUtility::hasSettings(PHPUnitUtility::PDO_MYSQL)) {
            self::$fixture = new MySQLFixture(PHPUnitUtility::PDO_MYSQL);
            self::$fixture->create();
        }
    }

    /**
     * {@inheritdoc}
     */
    static public function tearDownAfterCLass()
    {
        if (self::$fixture !== null) {
            self::$fixture->drop();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        if (!PHPUnitUtility::hasSettings(PHPUnitUtility::PDO_MYSQL)) {
            $this->markTestSkipped();
        }

        $setting = PHPUnitUtility::getSettings(PHPUnitUtility::PDO_MYSQL);

        $dsnOptions = array();

        foreach ($setting as $dsnKey => $dsnSetting) {
            if (in_array($dsnKey, array('dbname', 'host', 'port'))) {
                $dsnOptions[] = $dsnKey.'='.$dsnSetting;
            }
        }

        $dsn = substr($setting['driver'], 4).':'.implode(';', $dsnOptions);
        $username = $setting['username'];
        $password = $setting['password'];

        $this->connection = new PDOConnection($dsn, $username, $password);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->connection);
    }

    public function testAttributes()
    {
        $this->assertSame(PDO::ERRMODE_EXCEPTION, $this->connection->getAttribute(PDO::ATTR_ERRMODE));

        $this->assertSame(
            array('Fridge\DBAL\Driver\Statement\PDOStatement', array()),
            $this->connection->getAttribute(PDO::ATTR_STATEMENT_CLASS)
        );
    }
}