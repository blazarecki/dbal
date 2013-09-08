<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Driver\Connection;

/**
 * Abstract native connection test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractNativeConnectionTest extends AbstractNativeConnectionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected static function setUpBeforeClassFixtureMode()
    {
        return self::MODE_CREATE;
    }

    /**
     * {@inheritdoc}
     */
    protected static function setUpFixtureMode()
    {
        return self::MODE_DATAS;
    }
}
