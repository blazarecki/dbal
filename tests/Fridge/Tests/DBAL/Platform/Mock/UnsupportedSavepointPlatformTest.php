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
 * Unsupported savepoint platform test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedSavepointPlatformTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Platform\PlatformInterface */
    protected $platform;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->platform = new UnsupportedSavepointPlatformMock();
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
    public function testCreateSavepointSQLQuery()
    {
        $this->platform->getCreateSavepointSQLQuery('foo');
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testReleaseSavepointSQLQuery()
    {
        $this->platform->getReleaseSavepointSQLQuery('foo');
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testRollbackSavepointSQLQuery()
    {
        $this->platform->getRollbackSavepointSQLQuery('foo');
    }
}

/**
 * Unsuported savepoint platform mock.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedSavepointPlatformMock extends AbstractPlatform
{
    /**
     * {@inheritdoc}
     */
    public function supportSavepoints()
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
