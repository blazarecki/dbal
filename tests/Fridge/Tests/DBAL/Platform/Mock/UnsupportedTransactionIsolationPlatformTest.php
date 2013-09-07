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

use Fridge\DBAL\Connection\Connection;
use Fridge\DBAL\Platform\AbstractPlatform;

/**
 * Unsupported transaction isolation platform test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedTransactionIsolationPlatformTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Platform\PlatformInterface */
    private $platform;

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
    public function testSetTransactionIsolationSQLQueryWithUnsupportedOne()
    {
        $this->platform = new UnsupportedTransactionIsolationPlatformMock();
        $this->platform->getSetTransactionIsolationSQLQuery(Connection::TRANSACTION_READ_COMMITTED);
    }

    /**
     * @expectedException Fridge\DBAL\Exception\PlatformException
     */
    public function testSetTransactionIsolationSQLQueryWithForgottenOne()
    {
        $this->platform = new ForgottenTransactionIsolationPlatformMock();
        $this->platform->getSetTransactionIsolationSQLQuery(Connection::TRANSACTION_READ_COMMITTED);
    }
}

/**
 * Unsupported transaction isolation platform mock.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedTransactionIsolationPlatformMock extends AbstractPlatform
{
    /**
     * {@inheritdoc}
     */
    public function supportTransactionIsolations()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getSetTransactionIsolationSQLQuery($isolation)
    {
        return 'SET '.$this->getTransactionIsolationSQLDeclaration($isolation);
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeMappedTypes()
    {

    }
}

/**
 * Forgotten transaction isolation platform mock.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class ForgottenTransactionIsolationPlatformMock extends AbstractPlatform
{
    /**
     * {@inheritdoc}
     */
    public function supportTransactionIsolations()
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
