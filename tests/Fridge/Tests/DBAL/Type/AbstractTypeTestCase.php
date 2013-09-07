<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Type;

/**
 * Abstract type test case.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractTypeTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Type\TypeInterface */
    private $type;

    /** @var \Fridge\DBAL\Platform\PlatformInterface */
    private $platform;

    /**
     * Sets up the type.
     *
     * @return \Fridge\DBAL\Type\TypeInterface The type.
     */
    abstract protected function setUpType();

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->platform = $this->getMock('Fridge\DBAL\Platform\PlatformInterface');
        $this->type = $this->setUpType();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->type);
        unset($this->platform);
    }

    /**
     * Gets the type.
     *
     * @return \Fridge\DBAL\Type\TypeInterface The type.
     */
    protected function getType()
    {
        return $this->type;
    }

    /**
     * Gets the platform.
     *
     * @return \Fridge\DBAL\Platform\PlatformInterface The platform.
     */
    protected function getPlatform()
    {
        return $this->platform;
    }
}
