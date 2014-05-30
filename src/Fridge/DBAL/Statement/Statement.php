<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Statement;

use Fridge\DBAL\Connection\ConnectionInterface;
use Fridge\DBAL\Type\TypeUtility;

/**
 * {@inheritdoc}
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class Statement implements StatementInterface
{
    /** @var \Fridge\DBAL\Driver\Statement\DriverStatementInterface */
    private $driverStatement;

    /** @var \Fridge\DBAL\Connection\ConnectionInterface */
    private $connection;

    /** @var string */
    private $sql;

    /**
     * Creates a statement.
     *
     * @param string                                      $sql        The SQL of the statement.
     * @param \Fridge\DBAL\Connection\ConnectionInterface $connection The connection linked to the statement.
     */
    public function __construct($sql, ConnectionInterface $connection)
    {
        $this->sql = $sql;
        $this->connection = $connection;
        $this->driverStatement = $this->connection->getDriverConnection()->prepare($this->sql);
    }

    /**
     * {@inheritdoc}
     */
    public function getDriverStatement()
    {
        return $this->driverStatement;
    }

    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * {@inheritdoc}
     */
    public function getSQL()
    {
        return $this->sql;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->getDriverStatement();
    }

    /**
     * {@inheritdoc}
     *
     * This method only suppports PDO type.
     */
    public function bindParam($parameter, &$variable, $type = \PDO::PARAM_STR)
    {
        return $this->getDriverStatement()->bindParam($parameter, $variable, $type);
    }

    /**
     * {@inheritdoc}
     *
     * This method supports PDO or DBAL type.
     */
    public function bindValue($parameter, $value, $type = \PDO::PARAM_STR)
    {
        list($value, $type) = TypeUtility::convertToDatabase($value, $type, $this->connection->getPlatform());

        return $this->getDriverStatement()->bindValue($parameter, $value, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function closeCursor()
    {
        return $this->getDriverStatement()->closeCursor();
    }

    /**
     * {@inheritdoc}
     */
    public function columnCount()
    {
        return $this->getDriverStatement()->columnCount();
    }

    /**
     * {@inheritdoc}
     */
    public function errorCode()
    {
        return $this->getDriverStatement()->errorCode();
    }

    /**
     * {@inheritdoc}
     */
    public function errorInfo()
    {
        return $this->getDriverStatement()->errorInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function execute($parameters = array())
    {
        return $this->getDriverStatement()->execute($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($fetchMode = \PDO::FETCH_BOTH)
    {
        return $this->getDriverStatement()->fetch($fetchMode);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll($fetchMode = \PDO::FETCH_BOTH)
    {
        return $this->getDriverStatement()->fetchAll($fetchMode);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchColumn($columnIndex = 0)
    {
        return $this->getDriverStatement()->fetchColumn($columnIndex);
    }

    /**
     * {@inheritdoc}
     */
    public function rowCount()
    {
        return $this->getDriverStatement()->rowCount();
    }

    /**
     * {@inheritdoc}
     */
    public function setFetchMode($fetchMode)
    {
        return $this->getDriverStatement()->setFetchMode($fetchMode);
    }
}
