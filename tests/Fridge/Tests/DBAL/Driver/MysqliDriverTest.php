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

use Fridge\DBAL\Driver\MysqliDriver;
use Fridge\Tests\PHPUnitUtility;
use Fridge\Tests\Fixture\MySQLFixture;

/**
 * Mysqli driver tests.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class MysqliDriverTest extends AbstractDriverTest
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
    protected function setUpDriver()
    {
        return new MysqliDriver();
    }
}
