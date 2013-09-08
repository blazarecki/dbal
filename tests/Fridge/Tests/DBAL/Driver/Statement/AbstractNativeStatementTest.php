<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Driver\Statement;

/**
 * Abstract native statement test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractNativeStatementTest extends AbstractNativeStatementTestCase
{
    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::getFixture()->create();
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        if (self::hasFixture()) {
            self::getFixture()->drop();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        self::getFixture()->createDatas();
    }
}
