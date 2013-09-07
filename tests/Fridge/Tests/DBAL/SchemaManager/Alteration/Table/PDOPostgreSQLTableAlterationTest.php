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
use Fridge\Tests\Fixture\PostgreSQLFixture;

/**
 * PDO PostgreSQL table alteration test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PDOPostgreSQLTableAlterationTest extends AbstractTableAlterationTest
{
    /**
     * {@inheritdoc}
     */
    protected static function setUpFixture()
    {
        if (ConnectionUtility::hasConnection(ConnectionUtility::PDO_PGSQL)) {
            return new PostgreSQLFixture();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpSchemaManager()
    {
        return ConnectionUtility::getConnection(ConnectionUtility::PDO_PGSQL)->getSchemaManager();
    }
}
