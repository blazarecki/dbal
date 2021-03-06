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

/**
 * Schema manager test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class SchemaManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\SchemaManager\AbstractSchemaManager */
    private $schemaManager;

    /** @var \Fridge\DBAL\Platform\PlatformInterface */
    private $platformMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->platformMock = $this->getMock('Fridge\DBAL\Platform\PlatformInterface');

        $connection = $this->getMockBuilder('Fridge\DBAL\Connection\ConnectionInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $connection
            ->expects($this->any())
            ->method('getPlatform')
            ->will($this->returnValue($this->platformMock));

        $this->schemaManager = $this->getMockBuilder('Fridge\DBAL\SchemaManager\AbstractSchemaManager')
            ->setConstructorArgs(array($connection))
            ->getMockForAbstractClass();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->schemaManager);
        unset($this->platformMock);
    }

    public function testGetViewsWhenNotSupported()
    {
        $this->platformMock
            ->expects($this->once())
            ->method('supportViews')
            ->will($this->returnValue(false));

        $this->assertEmpty($this->schemaManager->getViews());
    }

    public function testGetPrimaryKeyWhenNotSupported()
    {
        $this->platformMock
            ->expects($this->once())
            ->method('supportPrimaryKeys')
            ->will($this->returnValue(false));

        $this->assertNull($this->schemaManager->getPrimaryKey('foo'));
    }

    public function testGetForeignKeysWhenNotSupported()
    {
        $this->platformMock
            ->expects($this->once())
            ->method('supportForeignKeys')
            ->will($this->returnValue(false));

        $this->assertEmpty($this->schemaManager->getForeignKeys('foo'));
    }

    public function testGetIndexesWhenNotSupported()
    {
        $this->platformMock
            ->expects($this->once())
            ->method('supportIndexes')
            ->will($this->returnValue(false));

        $this->assertEmpty($this->schemaManager->getIndexes('foo'));
    }
}
