<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager\Alteration\Table;

use Fridge\Tests\ConnectionUtility;
use Fridge\Tests\Fixture\MySQLFixture;

/**
 * PDO MySQL table alteration test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PDOMySQLTableAlterationTest extends AbstractMySQLTableAlterationTest
{
    /**
     * {@inheritdoc}
     */
    protected static function hasFixture()
    {
        return ConnectionUtility::hasConnection(ConnectionUtility::PDO_MYSQL);
    }

    /**
     * {@inheritdoc}
     */
    protected static function setUpFixture()
    {
        return new MySQLFixture(ConnectionUtility::PDO_MYSQL);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpSchemaManager()
    {
        return ConnectionUtility::getConnection(ConnectionUtility::PDO_MYSQL)->getSchemaManager();
    }
}
