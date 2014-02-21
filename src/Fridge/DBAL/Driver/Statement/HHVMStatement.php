<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Driver\Statement;

/**
 * {@inheritdoc}
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class HHVMStatement extends \PDOStatement implements DriverStatementInterface
{
    /**
     * {@inheritdoc}
     */
    public function bindParam($parameter, &$variable, $type = \PDO::PARAM_STR)
    {
        return parent::bindParam($parameter, $variable, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function bindValue($parameter, $value, $type = \PDO::PARAM_STR)
    {
        return parent::bindValue($parameter, $value, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($parameters = array())
    {
        return parent::execute($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll($fetchMode = \PDO::FETCH_BOTH)
    {
        return parent::fetchAll($fetchMode);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($fetchMode = \PDO::FETCH_BOTH)
    {
        return parent::fetch($fetchMode);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchColumn($columnIndex = 0)
    {
        return parent::fetchColumn($columnIndex);
    }
}
