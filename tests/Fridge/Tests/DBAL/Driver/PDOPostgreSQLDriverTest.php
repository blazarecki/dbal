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
    protected static function setUpFixture()
    {
        if (PHPUnitUtility::hasSettings(PHPUnitUtility::PDO_PGSQL)) {
            return new PostgreSQLFixture();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpDriver()
    {
        return new PDOPostgreSQLDriver();
    }
}
