<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager\SQLCollector;

/**
 * Abstract SQL collector test case.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractSQLCollectorTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var mixed */
    private $sqlCollector;

    /** @var \Fridge\DBAL\Platform\PlatformInterface */
    private $platform;

    /**
     * Sets up the SQL collector.
     *
     * @return mixed The SQL collector.
     */
    abstract protected function setUpSQLCollector();

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->platform = $this->getMock('Fridge\DBAL\Platform\PlatformInterface');
        $this->sqlCollector = $this->setUpSQLCollector();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->platform);
        unset($this->sqlCollector);
    }

    /**
     * Gets the SQL collector.
     *
     * @return mixed The SQL collector.
     */
    public function getSQLCollector()
    {
        return $this->sqlCollector;
    }

    /**
     * Gets the platform.
     *
     * @return \Fridge\DBAL\Platform\PlatformInterface The platform.
     */
    public function getPlatform()
    {
        return $this->platform;
    }
}
