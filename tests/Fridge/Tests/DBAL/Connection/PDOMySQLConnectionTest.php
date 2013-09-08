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
 * PDO MySQL functional connection test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PDOMySQLConnectionTest extends AbstractConnectionTest
{
    /**
     * {@inheritdoc}
     */
    protected static function setUpFixture()
    {
        if (ConnectionUtility::hasConnection(ConnectionUtility::PDO_MYSQL)) {
            return new MySQLFixture(ConnectionUtility::PDO_MYSQL);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpConnection()
    {
        if (ConnectionUtility::hasConnection(ConnectionUtility::PDO_MYSQL)) {
            return ConnectionUtility::getConnection(ConnectionUtility::PDO_MYSQL);
        }
    }
}
