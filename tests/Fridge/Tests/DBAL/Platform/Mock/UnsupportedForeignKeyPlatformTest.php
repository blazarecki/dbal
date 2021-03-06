<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Platform\Mock;

use Fridge\DBAL\Platform\AbstractPlatform;

/**
 * Unsupported foreign key platform test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedForeignKeyPlatformTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Platform\PlatformInterface */
    private $platform;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->platform = new UnsupportedForeignKeyPlatformMock();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->platform);
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testCreateForeignKeySQLQueries()
    {
        $foreignKeyMock = $this->getMockBuilder('Fridge\DBAL\Schema\ForeignKey')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getCreateForeignKeySQLQueries($foreignKeyMock, 'foo');
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testDropForeignKeySQLQueries()
    {
        $foreignKeyMock = $this->getMockBuilder('Fridge\DBAL\Schema\ForeignKey')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getDropForeignKeySQLQueries($foreignKeyMock, 'foo');
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testCreateConstraintSQLQueriesWithForeignKey()
    {
        $foreignKeyMock = $this->getMockBuilder('Fridge\DBAL\Schema\ForeignKey')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getCreateConstraintSQLQueries($foreignKeyMock, 'foo');
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testDropConstraintSQLQueriesWithForeignKey()
    {
        $foreignKeyMock = $this->getMockBuilder('Fridge\DBAL\Schema\ForeignKey')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getDropConstraintSQLQueries($foreignKeyMock, 'foo');
    }
}

/**
 * Unsupported foreign key platform mock.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedForeignKeyPlatformMock extends AbstractPlatform
{
    /**
     * {@inheritdoc}
     */
    public function supportForeignKeys()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeMappedTypes()
    {

    }
}
