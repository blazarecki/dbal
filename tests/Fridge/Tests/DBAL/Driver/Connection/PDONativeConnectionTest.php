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

use Fridge\DBAL\Driver\Connection\PDOConnection;
use Fridge\Tests\PHPUnitUtility;
use Fridge\Tests\Fixture\MySQLFixture;

/**
 * PDO native connection tests.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PDONativeConnectionTest extends AbstractNativeConnectionTest
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
    protected function setUpConnection()
    {
        $dsnOptions = array();
        foreach (self::getFixture()->getSettings() as $dsnKey => $dsnSetting) {
            if (in_array($dsnKey, array('dbname', 'host', 'port'))) {
                $dsnOptions[] = $dsnKey.'='.$dsnSetting;
            }
        }

        return new PDOConnection(
            substr(self::getFixture()->getSetting('driver'), 4).':'.implode(';', $dsnOptions),
            self::getFixture()->getSetting('username'),
            self::getFixture()->getSetting('password')
        );
    }

    public function testAttributes()
    {
        $this->assertSame(\PDO::ERRMODE_EXCEPTION, $this->getConnection()->getAttribute(\PDO::ATTR_ERRMODE));

        $this->assertSame(
            array('Fridge\DBAL\Driver\Statement\PDOStatement', array()),
            $this->getConnection()->getAttribute(\PDO::ATTR_STATEMENT_CLASS)
        );
    }
}
