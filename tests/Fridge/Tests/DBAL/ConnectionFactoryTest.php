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

use Fridge\DBAL\ConnectionFactory;

/**
 * Connection Factory test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class ConnectionFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Gets a list of valid drivers.
     *
     * @return array A list of valid drivers.
     */
    public static function validDriverProvider()
    {
        return array(
            array('pdo_mysql'),
            array('pdo_pgsql'),
            array('mysqli'),
        );
    }

    /**
     * Gets a list of valid driver classes.
     *
     * @return array A list of valid driver classes.
     */
    public static function validDriverClassProvider()
    {
        return array(
            array('Fridge\DBAL\Driver\PDOMySQLDriver'),
            array('Fridge\DBAL\Driver\PDOPostgreSQLDriver'),
            array('Fridge\DBAL\Driver\MysqliDriver'),
        );
    }

    /**
     * Gets a list of valid connection classes.
     *
     * @return array A list of valid connection classes.
     */
    public static function validConnectionClassProvider()
    {
        $validConnectionClass = get_class(
            \PHPUnit_Framework_MockObject_Generator::getMock('Fridge\DBAL\Connection\ConnectionInterface')
        );

        return array(
            array($validConnectionClass),
        );
    }

    /**
     * Gets a list of invalid drivers.
     *
     * @return array A list of invalid drives.
     */
    public static function invalidDriverProvider()
    {
        return array(
            array('foo'),
        );
    }

    /**
     * Gets a list of invalid classes.
     *
     * @return array A list of invalid classes.
     */
    public static function invalidClassProvider()
    {
        return array(
            array('\stdClass'),
        );
    }

    /**
     * Gets a list of valid drivers and driver classes.
     *
     * @return array A list of invalid drivers and driver classes ([0] => driver, [1] => driverClass).
     */
    public static function validDriverAndDriverClassProvider()
    {
        $provider = array();

        foreach (static::validDriverProvider() as $driver) {
            foreach (static::validDriverClassProvider() as $driverClass) {
                $provider[] = array($driver[0], $driverClass[0]);
            }
        }

        return $provider;
    }

    /**
     * Gets a list of valid drivers and connection classes.
     *
     * @return array A list of valid drivers and connection classes ([0] => driver, [1] => connectionClass).
     */
    public static function validDriverAndConnectionClassProvider()
    {
        $provider = array();

        foreach (static::validDriverProvider() as $driver) {
            foreach (static::validConnectionClassProvider() as $connectionClass) {
                $provider[] = array($driver[0], $connectionClass[0]);
            }
        }

        return $provider;
    }

    /**
     * Gets a list of valid drivers and invalid connection classes.
     *
     * @return array A list of valid drivers and invalid connection classes ([0] => driver, [1] => connectionClass).
     */
    public static function validDriverAndInvalidConnectionClassProvider()
    {
        $provider = array();

        foreach (static::validDriverProvider() as $driver) {
            foreach (static::invalidClassProvider() as $connectionClass) {
                $provider[] = array($driver[0], $connectionClass[0]);
            }
        }

        return $provider;
    }

    public function testAvailableDrivers()
    {
        $expected = array(
            'pdo_mysql',
            'pdo_pgsql',
            'mysqli',
        );

        $this->assertSame($expected, ConnectionFactory::getAvailableDrivers());
    }

    /**
     * @param string $driver A valid driver.
     *
     * @dataProvider validDriverProvider
     */
    public function testConnectionWithValidDriver($driver)
    {
        $this->assertInstanceOf(
            'Fridge\DBAL\Connection\ConnectionInterface',
            ConnectionFactory::create(array('driver' => $driver))
        );
    }

    /**
     * @param string $driver An invalid driver.
     *
     * @dataProvider invalidDriverProvider
     *
     * @expectedException \Fridge\DBAL\Exception\FactoryException
     */
    public function testConnectionWithInvalidDriver($driver)
    {
        ConnectionFactory::create(array('driver' => $driver));
    }

    /**
     * @param string $driverClass A valid driver class.
     *
     * @dataProvider validDriverClassProvider
     */
    public function testConnectionWithValidDriverClass($driverClass)
    {
        $this->assertInstanceOf(
            'Fridge\DBAL\Connection\ConnectionInterface',
            ConnectionFactory::create(array('driver_class' => $driverClass))
        );
    }

    /**
     * @param string $driverClass An invalid driver class.
     *
     * @dataProvider invalidClassProvider
     *
     * @expectedException \Fridge\DBAL\Exception\FactoryException
     */
    public function testConnectionWithInvalidDriverClass($driverClass)
    {
        ConnectionFactory::create(array('driver_class' => $driverClass));
    }

    /**
     * @param string $driver      A valid driver.
     * @param string $driverClass A valid driver class.
     *
     * @dataProvider validDriverAndDriverClassProvider
     */
    public function testConnectionWithValidDriverAndDriverClass($driver, $driverClass)
    {
        $this->assertInstanceOf(
            $driverClass,
            ConnectionFactory::create(array('driver' => $driver, 'driver_class' => $driverClass))->getDriver()
        );
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\FactoryException
     */
    public function testConnectionWithoutDriverAndDriverClass()
    {
        ConnectionFactory::create(array());
    }

    /**
     * @param string $driver          A valid driver.
     * @param string $connectionClass A valid connection class.
     *
     * @dataProvider validDriverAndConnectionClassProvider
     */
    public function testConnectionWithValidDriverAndConnectionClass($driver, $connectionClass)
    {
        $this->assertInstanceOf(
            $connectionClass,
            ConnectionFactory::create(array('driver' => $driver, 'connection_class' => $connectionClass))
        );
    }

    /**
     * @param string $driver          A valid driver.
     * @param string $connectionClass An invalid connection class.
     *
     * @dataProvider validDriverAndInvalidConnectionClassProvider
     *
     * @expectedException \Fridge\DBAL\Exception\FactoryException
     */
    public function testConnectionWithValidDriverAndInvalidConnectionClass($driver, $connectionClass)
    {
        $this->assertInstanceOf(
            $connectionClass,
            ConnectionFactory::create(array('driver' => $driver, 'connection_class' => $connectionClass))
        );
    }
}
