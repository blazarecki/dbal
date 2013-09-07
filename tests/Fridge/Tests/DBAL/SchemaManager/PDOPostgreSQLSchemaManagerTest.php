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
use Fridge\Tests\Fixture\PostgreSQLFixture;

/**
 * PDO PostgreSQL schema manager test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PDOPostgreSQLSchemaManagerTest extends AbstractSchemaManagerTest
{
    /**
     * {@inheritdoc}
     */
    public static function setUpFixture()
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

    public function testGetDatabaseWithoutConfiguredDatabase()
    {
        $this->getSchemaManager()->getConnection()->setDatabase(null);

        $this->assertSame(self::getFixture()->getSetting('username'), $this->getSchemaManager()->getDatabase());
    }
}
