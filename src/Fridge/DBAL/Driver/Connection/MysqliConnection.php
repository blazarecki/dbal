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

use Fridge\DBAL\Driver\Statement\MysqliStatement;
use Fridge\DBAL\Exception\MysqliException;

/**
 * Mysqli driver connection.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class MysqliConnection implements DriverConnectionInterface
{
    /** @var \mysqli */
    private $mysqli;

    /** @var boolean */
    private $inTransaction = false;

    /** @var integer */
    private $maxAllowedPacket;

    /**
     * Mysqli connection constructor.
     *
     * $parameters can contain:
     *  - dbname
     *  - host
     *  - port
     *  - unix_socket
     *  - charset
     *
     * @param array  $parameters The database parameters.
     * @param string $username   The database username.
     * @param string $password   The database password.
     *
     * @throws \Fridge\DBAL\Exception\MysqliException If the connection can not be established or if the
     *                                                charset can not be setted.
     */
    public function __construct(array $parameters, $username, $password)
    {
        $host = isset($parameters['host']) ? $parameters['host'] : ini_get('mysqli.default_host');
        $database = isset($parameters['dbname']) ? $parameters['dbname'] : '';
        $port = isset($parameters['post']) ? $parameters['port'] : ini_get('mysqli.default_port');
        $unixSocket = isset($parameters['unix_socket']) ? $parameters['unix_socket'] : ini_get('mysqli.default_socket');

        $this->mysqli = @new \mysqli($host, $username, $password, $database, $port, $unixSocket);

        if ($this->mysqli->connect_error !== null) {
            throw new MysqliException($this->mysqli->connect_error, $this->mysqli->connect_errno);
        }

        if (isset($parameters['charset']) && ($this->mysqli->set_charset($parameters['charset']) === false)) {
            throw new MysqliException($this->mysqli->error, $this->mysqli->errno);
        }
    }

    /**
     * Gets the mysqli driver connection.
     *
     * @return \mysqli The mysqli driver connection.
     */
    public function getMysqli()
    {
        return $this->mysqli;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        return $this->inTransaction = $this->mysqli->query('START TRANSACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        $this->inTransaction = false;

        return $this->mysqli->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function rollBack()
    {
        $this->inTransaction = false;

        return $this->mysqli->rollback();
    }

    /**
     * {@inheritdoc}
     */
    public function inTransaction()
    {
        return $this->inTransaction;
    }

    /**
     * {@inheritdoc}
     */
    public function quote($string, $type = \PDO::PARAM_STR)
    {
        return '\''.$this->mysqli->real_escape_string($string).'\'';
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        $statement = $this->prepare(func_get_arg(0));
        $statement->execute();

        return $statement;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($statement)
    {
        return new MysqliStatement($statement, $this);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Fridge\DBAL\Exception\MysqliException If the statement can not be executed.
     */
    public function exec($statement)
    {
        $result = $this->mysqli->query($statement);

        if ($result === false) {
            throw new MysqliException($this->mysqli->error, $this->mysqli->errno);
        }

        return $this->mysqli->affected_rows;
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId($name = null)
    {
        return $this->mysqli->insert_id;
    }

    /**
     * {@inheritdoc}
     */
    public function errorCode()
    {
        return $this->mysqli->errno;
    }

    /**
     * {@inheritdoc}
     */
    public function errorInfo()
    {
        return array($this->mysqli->errno, $this->mysqli->errno, $this->mysqli->error);
    }

    /**
     * Gets the MySQL max allowed packet constant.
     *
     * @link http://dev.mysql.com/doc/refman/5.0/en/program-variables.html
     *
     * @return integer The max allowed packet.
     */
    public function getMaxAllowedPacket()
    {
        if ($this->maxAllowedPacket === null) {
            $statement = $this->prepare('SELECT @@global.max_allowed_packet');
            $statement->execute();

            $this->maxAllowedPacket = (int) $statement->fetchColumn();
        }

        return $this->maxAllowedPacket;
    }
}
