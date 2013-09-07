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
 * Unsupported view platform test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedViewPlatformTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Platform\PlatformInterface */
    private $platform;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->platform = new UnsupportedViewPlatformMock();
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
    public function testCreateViewSQLQueries()
    {
        $viewMock = $this->getMockBuilder('Fridge\DBAL\Schema\View')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getCreateViewSQLQueries($viewMock);
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testDropViewSQLQueries()
    {
        $viewMock = $this->getMockBuilder('Fridge\DBAL\Schema\View')
            ->disableOriginalConstructor()
            ->getMock();

        $this->platform->getDropViewSQLQueries($viewMock);
    }
}

/**
 * Unsupported view platform mock.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedViewPlatformMock extends AbstractPlatform
{
    /**
     * {@inheritdoc}
     */
    public function supportViews()
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
