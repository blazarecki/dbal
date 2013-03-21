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
    public static function setUpBeforeClass()
    {
        if (ConnectionUtility::hasConnection(ConnectionUtility::PDO_MYSQL)) {
            self::$fixture = new MySQLFixture(ConnectionUtility::PDO_MYSQL);
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
        if (ConnectionUtility::hasConnection(ConnectionUtility::PDO_MYSQL)) {
            $this->connection = ConnectionUtility::getConnection(ConnectionUtility::PDO_MYSQL);
        }

        parent::setUp();
    }
}
