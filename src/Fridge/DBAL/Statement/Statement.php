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
class Statement implements StatementInterface, \IteratorAggregate
{
    /** @var \Fridge\DBAL\Driver\Statement\NativeStatementInterface */
    private $nativeStatement;

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

        $this->nativeStatement = $this->connection->getNativeConnection()->prepare($this->sql);
    }

    /**
     * {@inheritdoc}
     */
    public function getNativeStatement()
    {
        return $this->nativeStatement;
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
        return $this->nativeStatement;
    }

    /**
     * {@inheritdoc}
     *
     * This method only suppports PDO type.
     */
    public function bindParam($parameter, &$variable, $type = \PDO::PARAM_STR)
    {
        return $this->nativeStatement->bindParam($parameter, $variable, $type);
    }

    /**
     * {@inheritdoc}
     *
     * This method supports PDO or DBAL type.
     */
    public function bindValue($parameter, $value, $type = \PDO::PARAM_STR)
    {
        TypeUtility::bindTypedValue($value, $type, $this->connection->getPlatform());

        return $this->nativeStatement->bindValue($parameter, $value, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function closeCursor()
    {
        return $this->nativeStatement->closeCursor();
    }

    /**
     * {@inheritdoc}
     */
    public function columnCount()
    {
        return $this->nativeStatement->columnCount();
    }

    /**
     * {@inheritdoc}
     */
    public function errorCode()
    {
        return $this->nativeStatement->errorCode();
    }

    /**
     * {@inheritdoc}
     */
    public function errorInfo()
    {
        return $this->nativeStatement->errorInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function execute($parameters = array())
    {
        return $this->nativeStatement->execute($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($fetchMode = \PDO::FETCH_BOTH)
    {
        return $this->nativeStatement->fetch($fetchMode);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll($fetchMode = \PDO::FETCH_BOTH)
    {
        return $this->nativeStatement->fetchAll($fetchMode);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchColumn($columnIndex = 0)
    {
        return $this->nativeStatement->fetchColumn($columnIndex);
    }

    /**
     * {@inheritdoc}
     */
    public function rowCount()
    {
        return $this->nativeStatement->rowCount();
    }

    /**
     * {@inheritdoc}
     */
    public function setFetchMode($fetchMode)
    {
        return $this->nativeStatement->setFetchMode($fetchMode);
    }
}
