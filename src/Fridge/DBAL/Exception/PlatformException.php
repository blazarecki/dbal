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
 * Platform exception.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PlatformException extends Exception
{
    /**
     * Gets the "CONSTRAINT NOT SUPPORTED" exception.
     *
     * @param string $constraint The constraint class name.
     *
     * @return \Fridge\DBAL\Exception\PlatformException The "CONSTRAINT NOT SUPPORTED" exception.
     */
    public static function constraintNotSupported($constraint)
    {
        return new self(sprintf('The constraint "%s" is not supported.', $constraint));
    }

    /**
     * Gets the "INVALID VARCHAR FIXED FLAG" exception.
     *
     * @return \Fridge\DBAL\Exception\PlatformException The "INVALID VARCHAR FIXED FLAG" exception.
     */
    public static function invalidVarcharFixedFlag()
    {
        return new self('The varchar fixed flag must be a boolean.');
    }

    /**
     * Gets the "INVALID VARCHAR LENGTH" exception.
     *
     * @return \Fridge\DBAL\Exception\PlatformException The "INVALID VARCHAR LENGTH" exception.
     */
    public static function invalidVarcharLength()
    {
        return new self('The varchar length must be a positive integer.');
    }

    /**
     * Gets the "INVALID STRING TYPE PREFIX LENGTH" exception.
     *
     * @return \Fridge\DBAL\Exception\PlatformException The "INVALID STRING TYPE PREFIX LENGTH" exception.
     */
    public static function invalidStringTypePrefixLength()
    {
        return new self('The string type prefix length must be a strict positive integer.');
    }

    /**
     * Gets the "MAPPED TYPE ALREADY EXISTS" exception.
     *
     * @param string $type The mapped type.
     *
     * @return \Fridge\DBAL\Exception\PlatformException The "MAPPED TYPE ALREADY EXISTS" exception.
     */
    public static function mappedTypeAlreadyExists($type)
    {
        return new self(sprintf('The mapped type "%s" already exists.', $type));
    }

    /**
     * Gets the "MAPPED TYPE DOES NOT EXIST" exception.
     *
     * @param string $type The mapped type.
     *
     * @return \Fridge\DBAL\Exception\PlatformException The "MAPPED TYPE DOES NOT EXIST" exception.
     */
    public static function mappedTypeDoesNotExist($type)
    {
        return new self(sprintf('The mapped type "%s" does not exist.', $type));
    }

    /**
     * Gets the "CUSTOM TYPE ALREADY EXISTS" exception.
     *
     * @param string $type The custom type.
     *
     * @return \Fridge\DBAL\Exception\PlatformException The "CUSTOM TYPE ALREADY EXISTS" exception.
     */
    public static function customTypeAlreadyExists($type)
    {
        return new self(sprintf('The custom type "%s" already exists.', $type));
    }

    /**
     * Gets the "CUSTOM TYPE DOES NOT EXIST" exception.
     *
     * @param string $type The custom type.
     *
     * @return \Fridge\DBAL\Exception\PlatformException The "CUSTOM TYPE DOES NOT EXIST" exception.
     */
    public static function customTypeDoesNotExist($type)
    {
        return new self(sprintf('The custom type "%s" does not exist.', $type));
    }

    /**
     * Gets the "TRANSACTION ISOLATION DOES NOT EXIST" exception.
     *
     * @param string $isolation The transaction isolation.
     *
     * @return \Fridge\DBAL\Exception\PlatformException The "TRANSACTION ISOLATION DOES NOT EXIST" exception.
     */
    public static function transactionIsolationDoesNotExist($isolation)
    {
        return new self(sprintf('The transaction isolation "%s" does not exist.', $isolation));
    }
}
