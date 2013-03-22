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

use Exception as BaseException;

/**
 * Fridge exception.
 *
 * All Fridge DBAL exceptions must extend this class.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class Exception extends BaseException
{
    /**
     * Gets the "CLASS NOT FOUND" exception.
     *
     * @param string $class The class name.
     *
     * @return \Fridge\DBAL\Exception\Exception The "CLASS NOT FOUND" exception.
     */
    public static function classNotFound($class)
    {
        return new static(sprintf('The class "%s" can not be found.', $class));
    }

    /**
     * Gets the "METHOD NOT SUPPORTED" exception.
     *
     * @param string $method The method name.
     *
     * @return \Fridge\DBAL\Exception\Exception The "METHOD NOT SUPPORTED" exception.
     */
    public static function methodNotSupported($method)
    {
        return new static(sprintf('The method "%s" is not supported.', $method));
    }
}
