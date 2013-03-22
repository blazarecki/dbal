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

use Fridge\DBAL\Driver\PDOPostgreSQLDriver;
use Fridge\Tests\PHPUnitUtility;
use Fridge\Tests\Fixture\PostgreSQLFixture;

/**
 * PDO PostgreSQL driver test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PDOPostgreSQLDriverTest extends AbstractDriverTest
{
    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        if (PHPUnitUtility::hasSettings(PHPUnitUtility::PDO_PGSQL)) {
            self::$fixture = new PostgreSQLFixture();
        } else {
            self::$fixture = null;
        }

        parent::setUpBeforeClass();
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        if (PHPUnitUtility::hasSettings(PHPUnitUtility::PDO_PGSQL)) {
            $this->driver = new PDOPostgreSQLDriver();
        }

        parent::setUp();
    }
}
