<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager\Alteration\Column;

use Fridge\Tests\ConnectionUtility;
use Fridge\Tests\Fixture\MySQLFixture;

/**
 * Mysqli column alteration test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class MysqliColumnAlterationTest extends AbstractColumnAlterationTest
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
    protected function setUpSchemaManager()
    {
        return ConnectionUtility::getConnection(ConnectionUtility::MYSQLI)->getSchemaManager();
    }
}
