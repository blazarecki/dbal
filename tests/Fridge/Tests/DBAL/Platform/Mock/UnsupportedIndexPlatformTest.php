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
 * Unsupported index platform test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedIndexPlatformTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Platform\PlatformInterface */
    protected $platform;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->platform = new UnsupportedIndexPlatformMock();
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
    public function testCreateIndexSQLQueriesWithUniqueIndex()
    {
        $indexMock = $this->getMockBuilder('Fridge\DBAL\Schema\Index')
            ->disableOriginalConstructor()
            ->getMock();

        $indexMock
            ->expects($this->any())
            ->method('isUnique')
            ->will($this->returnValue(true));

        $this->platform->getCreateIndexSQLQueries($indexMock, 'foo');
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testCreateIndexSQLQueriesWithNonUniqueIndex()
    {
        $indexMock = $this->getMockBuilder('Fridge\DBAL\Schema\Index')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getCreateIndexSQLQueries($indexMock, 'foo');
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testDropIndexSQLQueries()
    {
        $indexMock = $this->getMockBuilder('Fridge\DBAL\Schema\Index')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getDropIndexSQLQueries($indexMock, 'foo');
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testCreateConstraintSQLQueriesWithUniqueIndex()
    {
        $indexMock = $this->getMockBuilder('Fridge\DBAL\Schema\Index')
            ->disableOriginalConstructor()
            ->getMock();

        $indexMock
            ->expects($this->any())
            ->method('isUnique')
            ->will($this->returnValue(true));

        $this->platform->getCreateConstraintSQLQueries($indexMock, 'foo');
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testCreateConstraintSQLQueriesWithNonUniqueIndex()
    {
        $indexMock = $this->getMockBuilder('Fridge\DBAL\Schema\Index')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getCreateConstraintSQLQueries($indexMock, 'foo');
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testDropConstraintSQLQueriesWithIndex()
    {
        $indexMock = $this->getMockBuilder('Fridge\DBAL\Schema\Index')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getDropConstraintSQLQueries($indexMock, 'foo');
    }
}

/**
 * Unsupported index platform mock.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedIndexPlatformMock extends AbstractPlatform
{
    /**
     * {@inheritdoc}
     */
    public function supportIndexes()
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
