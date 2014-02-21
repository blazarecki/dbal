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

use PDOStatement as BaseStatement;

/**
 * {@inheritdoc}
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PDOStatement extends BaseStatement implements DriverStatementInterface
{
    /**
     * Disabeld constructor.
     *
     * @codeCoverageIgnore
     */
    protected function __construct()
    {

    }
}
