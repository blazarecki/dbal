<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Driver;

use Fridge\DBAL\Driver\PDOMySQLDriver;
use Fridge\Tests\PHPUnitUtility;
use Fridge\Tests\Fixture\MySQLFixture;

/**
 * PDO MySQL driver test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PDOMySQLDriverTest extends AbstractDriverTest
{
    /**
     * {@inheritdoc}
     */
    protected static function setUpFixture()
    {
        if (PHPUnitUtility::hasSettings(PHPUnitUtility::PDO_MYSQL)) {
            return new MySQLFixture(PHPUnitUtility::PDO_MYSQL);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpDriver()
    {
        return new PDOMySQLDriver();
    }

    public function testConnectWithUnixSocket()
    {
        $settings = self::getFixture()->getSettings();

        unset($settings['host']);
        unset($settings['port']);

        $settings['unix_socket'] = ini_get('mysql.default_socket');

        $this->assertInstanceOf(
            'Fridge\DBAL\Driver\Connection\DriverConnectionInterface',
            $this->getDriver()->connect(
                $settings,
                self::getFixture()->getSetting('username'),
                self::getFixture()->getSetting('password')
            )
        );
    }

    public function testConnectWithCharset()
    {
        $settings = self::getFixture()->getSettings();
        $settings['charset'] = 'utf8';

        $this->assertInstanceOf(
            'Fridge\DBAL\Driver\Connection\DriverConnectionInterface',
            $this->getDriver()->connect(
                $settings,
                self::getFixture()->getSetting('username'),
                self::getFixture()->getSetting('password')
            )
        );
    }
}
