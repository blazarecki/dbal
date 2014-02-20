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
    /** @const integer The "NONE" fixture mode. */
    const MODE_NONE = 0;

    /** @const integer The "CREATE" fixture mode. */
    const MODE_CREATE = 1;

    /** @const integer The "DATABASE" fixture mode. */
    const MODE_DATABASE = 2;

    /** @const integer The "SCHEMA" fixture mode. */
    const MODE_SCHEMA = 3;

    /** @const integer The "DATAS" fixture mode. */
    const MODE_DATAS = 4;

    /** @var \Fridge\Tests\Fixture\FixtureInterface */
    private static $fixture;

    /** @var integer */
    private static $setUpBeforeClassFixtureMode;

    /** @var integer */
    private static $setUpFixtureMode;

    /**
     * Checks if there is a fixture.
     *
     * @return boolean TRUE if there is a fixture else FALSE.
     */
    protected static function hasFixture()
    {
        throw new \Exception('You must implement your own "hasFixture" method.');
    }

    /**
     * Sets up the fixture.
     *
     * @return \Fridge\Tests\Fixture\FixtureInterface The fixture.
     */
    protected static function setUpFixture()
    {
        throw new \Exception('You must implement your own "setUpFixture" method.');
    }

    /**
     * Sets up the before class fixture mode.
     *
     * @return integer The before class fixture mode.
     */
    protected static function setUpBeforeClassFixtureMode()
    {
        return self::MODE_NONE;
    }

    /**
     * Sets up the fixture mode.
     *
     * @return integer The set up fixture mode.
     */
    protected static function setUpFixtureMode()
    {
        return self::MODE_NONE;
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        if (!static::hasFixture()) {
            self::markTestSkipped();
        }

        self::setSetUpBeforeClassFixtureMode(static::setUpBeforeClassFixtureMode());
        self::setSetUpFixtureMode(static::setUpFixtureMode());

        self::setFixture(static::setUpFixture());
        self::handleFixtureMode(self::getSetUpBeforeClassFixtureMode());
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        if ((self::getSetUpBeforeClassFixtureMode() > self::MODE_NONE)
            || (self::getSetUpFixtureMode() > self::MODE_NONE)
        ) {
            self::$fixture->drop();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        self::handleFixtureMode(self::getSetUpFixtureMode());
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

    /**
     * Sets the fixture.
     *
     * @param \Fridge\Tests\Fixture\FixtureInterface $fixture The fixture.
     */
    protected static function setFixture(FixtureInterface $fixture)
    {
        self::$fixture = $fixture;
    }

    /**
     * Gets the set up before class fixture mode.
     *
     * @return integer The set up before class fixture mode.
     */
    protected static function getSetUpBeforeClassFixtureMode()
    {
        return self::$setUpBeforeClassFixtureMode;
    }

    /**
     * Sets the set up before class fixture mode.
     *
     * @param integer $setUpBeforeClassFixtureMode The set up before class fixture mode.
     */
    protected static function setSetUpBeforeClassFixtureMode($setUpBeforeClassFixtureMode)
    {
        self::$setUpBeforeClassFixtureMode = $setUpBeforeClassFixtureMode;
    }

    /**
     * Gets the set up fixture mode.
     *
     * @return integer The set up fixture mode.
     */
    protected static function getSetUpFixtureMode()
    {
        return self::$setUpFixtureMode;
    }

    /**
     * Sets the set up fixture mode.
     *
     * @param integer $setUpFixtureMode The set up fixture mode.
     */
    protected static function setSetUpFixtureMode($setUpFixtureMode)
    {
        self::$setUpFixtureMode = $setUpFixtureMode;
    }

    /**
     * Handles a fixture mode.
     *
     * @param integer $fixtureMode The fixture mode.
     */
    private static function handleFixtureMode($fixtureMode)
    {
        switch ($fixtureMode) {
            case self::MODE_CREATE:
                self::$fixture->create();
                break;

            case self::MODE_DATABASE:
                self::$fixture->createDatabase();
                break;

            case self::MODE_SCHEMA:
                self::$fixture->createSchema();
                break;

            case self::MODE_DATAS:
                self::$fixture->createDatas();
                break;
        }
    }
}
