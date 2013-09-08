<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\Fixture;

/**
 * Abstract fixture test case.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractFixtureTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\Tests\Fixture\FixtureInterface */
    private static $fixture;

    /**
     * Sets up the fixture.
     *
     * @return \Fridge\Tests\Fixture\FixtureInterface|null The fixture or null if there is none.
     */
    protected static function setUpFixture()
    {
        throw new \Exception('You must implement your own "setUpFixture" method.');
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        self::$fixture = static::setUpFixture();

        if (!self::hasFixture()) {
            self::markTestSkipped();
        }
    }

    /**
     * Checks if there is a fixture.
     *
     * @return boolean TRUE if there is a fixture else FALSE.
     */
    protected static function hasFixture()
    {
        return self::$fixture !== null;
    }

    /**
     * Gets the fixture.
     *
     * @return \Fridge\Tests\Fixture\FixtureInterface|null The fixture.
     */
    protected static function getFixture()
    {
        return self::$fixture;
    }
}
