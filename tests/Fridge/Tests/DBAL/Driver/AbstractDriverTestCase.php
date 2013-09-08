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

use Fridge\Tests\Fixture\AbstractFixtureTestCase;

/**
 * Abstract driver test case.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractDriverTestCase extends AbstractFixtureTestCase
{
    /** @var \Fridge\DBAL\Driver\DriverInterface */
    private $driver;

    /**
     * Sets up the driver.
     *
     * @return \Fridge\DBAL\Driver\DriverInterface The driver.
     */
    abstract protected function setUpDriver();

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->driver = $this->setUpDriver();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->driver);
    }

    /**
     * Gets the driver.
     *
     * @return \Fridge\DBAL\Driver\DriverInterface The driver.
     */
    public function getDriver()
    {
        return $this->driver;
    }
}
