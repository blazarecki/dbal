<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Exception;

/**
 * Mysqli exception.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class MysqliException extends Exception
{
    /**
     * Gets the "MAPPED TYPE DOES NOT EXIST" exception
     *
     * @param integer $type The type.
     *
     * @return \Fridge\DBAL\Exception\MysqliException The "MAPPED TYPE DOES NOT EXIST" exception.
     */
    public static function mappedTypeDoesNotExist($type)
    {
        return new static(sprintf('The mapped type "%s" does not exist.', $type));
    }

    /**
     * Gets the "FETCH MODE NOT SUPPORTED" exception.
     *
     * @param string $fetchMode The fetch mode.
     *
     * @return \Fridge\DBAL\Exception\MysqliException The "FETCH MODE NOT SUPPORTED" exception.
     */
    public static function fetchModeNotSupported($fetchMode)
    {
        return new static(sprintf('The fetch mode "%s" is not supported.', $fetchMode));
    }
}
