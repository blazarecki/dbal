<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL;

use Fridge\DBAL\Configuration;

/**
 * Configuration test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultDebug()
    {
        $configuration = new Configuration();

        $this->assertFalse($configuration->getDebug());
    }

    public function testDefaultEventDispatcher()
    {
        $configuration = new Configuration();

        $this->assertInstanceOf(
            'Symfony\Component\EventDispatcher\EventDispatcher',
            $configuration->getEventDispatcher()
        );
    }

    public function testCustomDebug()
    {
        $configuration = new Configuration(true);

        $this->assertTrue($configuration->getDebug());
    }

    public function testCustomEventDispatcher()
    {
        $eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
        $configuration = new Configuration(false, $eventDispatcher);

        $this->assertSame($eventDispatcher, $configuration->getEventDispatcher());
    }
}
