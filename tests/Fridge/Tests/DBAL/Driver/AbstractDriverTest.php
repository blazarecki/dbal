<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Driver;

/**
 * Abstract driver test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractDriverTest extends AbstractDriverTestCase
{
    /**
     * {@inheritdoc}
     */
    protected static function setUpBeforeClassFixtureMode()
    {
        return self::MODE_CREATE;
    }

    public function testConnect()
    {
        $this->assertInstanceOf(
            'Fridge\DBAL\Driver\Connection\DriverConnectionInterface',
            $this->getDriver()->connect(
                self::getFixture()->getSettings(),
                self::getFixture()->getSetting('username'),
                self::getFixture()->getSetting('password')
            )
        );
    }

    public function testPlatform()
    {
        $this->assertInstanceOf('Fridge\DBAL\Platform\PlatformInterface', $this->getDriver()->getPlatform());
    }

    public function testSchemaManager()
    {
        $connectionMock = $this->getMock('Fridge\DBAL\Connection\ConnectionInterface');

        $this->assertInstanceOf(
            'Fridge\DBAL\SchemaManager\SchemaManagerInterface',
            $this->getDriver()->getSchemaManager($connectionMock)
        );
    }
}
