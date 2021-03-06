<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Driver;

use Fridge\DBAL\Driver\Connection\HHVMConnection;
use Fridge\DBAL\Driver\Connection\PDOConnection;

/**
 * Abstract PDO driver.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractPDODriver extends AbstractDriver
{
    /**
     * {@inheritdoc}
     */
    public function connect(array $parameters, $username = null, $password = null, array $driverOptions = array())
    {
        if (defined('HHVM_VERSION')) {
            return new HHVMConnection($this->generateDSN($parameters), $username, $password, $driverOptions);
        }

        return new PDOConnection($this->generateDSN($parameters), $username, $password, $driverOptions);
    }

    /**
     * Generates the PDO DSN.
     *
     * @param array $parameters The PDO DSN parameters
     *
     * @return string The PDO DSN
     */
    abstract protected function generateDSN(array $parameters);
}
