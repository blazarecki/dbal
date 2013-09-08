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

use Fridge\Tests\ConnectionUtility;
use Fridge\Tests\Fixture\MySQLFixture;

/**
 * Mysqli connection test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class MysqliConnectionTest extends AbstractConnectionTest
{
    /**
     * {@inheritdoc}
     */
    protected static function setUpFixture()
    {
        if (ConnectionUtility::hasConnection(ConnectionUtility::MYSQLI)) {
            return new MySQLFixture(ConnectionUtility::MYSQLI);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpConnection()
    {
        if (ConnectionUtility::hasConnection(ConnectionUtility::MYSQLI)) {
            return ConnectionUtility::getConnection(ConnectionUtility::MYSQLI);
        }
    }
}
