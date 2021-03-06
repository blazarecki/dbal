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
 * Unsupported primary key platform test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedPrimaryKeyPlatformTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Platform\PlatformInterface */
    private $platform;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->platform = new UnsupportedPrimaryKeyPlatformMock();
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
    public function testCreatePrimaryKeySQLQueries()
    {
        $primaryKeyMock = $this->getMockBuilder('Fridge\DBAL\Schema\PrimaryKey')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getCreatePrimaryKeySQLQueries($primaryKeyMock, 'foo');
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testDropPrimaryKeySQLQueries()
    {
        $primaryKeyMock = $this->getMockBuilder('Fridge\DBAL\Schema\PrimaryKey')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getDropPrimaryKeySQLQueries($primaryKeyMock, 'foo');
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testCreateConsraintSQLQueriesWithPrimaryKey()
    {
        $primaryKeyMock = $this->getMockBuilder('Fridge\DBAL\Schema\PrimaryKey')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getCreateConstraintSQLQueries($primaryKeyMock, 'foo');
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testDropConstraintSQLQueriesWithPrimaryKey()
    {
        $primaryKeyMock = $this->getMockBuilder('Fridge\DBAL\Schema\PrimaryKey')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getDropConstraintSQLQueries($primaryKeyMock, 'foo');
    }
}

/**
 * Unsupported primary key platform mock.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedPrimaryKeyPlatformMock extends AbstractPlatform
{
    /**
     * {@inheritdoc}
     */
    public function supportPrimaryKeys()
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
