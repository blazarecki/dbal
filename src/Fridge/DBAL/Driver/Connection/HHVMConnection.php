<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Driver\Connection;

/**
 * HHVM driver connection.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class HHVMConnection extends \PDO implements DriverConnectionInterface
{
    /**
     * Creates an HHVM connection.
     *
     * @param string $dsn           The database DSN.
     * @param string $username      The database username.
     * @param string $password      The database passord.
     * @param array  $driverOptions The database driver options.
     */
    public function __construct($dsn, $username = null, $password = null, array $driverOptions = array())
    {
        parent::__construct($dsn, $username, $password, $driverOptions);

        $this->setAttribute(self::ATTR_ERRMODE, self::ERRMODE_EXCEPTION);
        $this->setAttribute(self::ATTR_STATEMENT_CLASS, array('Fridge\DBAL\Driver\Statement\HHVMStatement', array()));
    }

    /**
     * {@inheritdoc}
     */
    public function inTransaction()
    {
        return parent::inTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function quote($string, $type = \PDO::PARAM_STR)
    {
        return parent::quote($string, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        return call_user_func_array(array(get_parent_class($this), 'query'), func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($statement)
    {
        return parent::prepare($statement);
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId($name = null)
    {
        return parent::lastInsertId($name);
    }
}
