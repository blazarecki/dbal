<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager;

use Fridge\Tests\ConnectionUtility;
use Fridge\Tests\Fixture\MySQLFixture;

/**
 * Mysqli schema manager tests.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class MysqliSchemaManagerTest extends AbstractMySQLSchemaManagerTest
{
    /**
     * {@inheritdoc}
     */
    protected static function hasFixture()
    {
        return ConnectionUtility::hasConnection(ConnectionUtility::MYSQLI);
    }

    /**
     * {@inheritdoc}
     */
    protected static function setUpFixture()
    {
        return new MySQLFixture(ConnectionUtility::MYSQLI);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpSchemaManager()
    {
        return ConnectionUtility::getConnection(ConnectionUtility::MYSQLI)->getSchemaManager();
    }
}
