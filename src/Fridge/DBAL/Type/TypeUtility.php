<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Type;

use Fridge\DBAL\Platform\PlatformInterface;

/**
 * This utility class allows to do tasks about type.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class TypeUtility
{
    /**
     * Converts a value/type to its database representation according to a platform.
     *
     * @param mixed                                          $value    The value.
     * @param string|integer|\Fridge\DBAL\Type\TypeInterface $type     The type (PDO or DBAL).
     * @param \Fridge\DBAL\Platform\PlatformInterface        $platform The platform.
     *
     * @return array 0 => The converted value, 1 => The converted type.
     */
    public static function convertToDatabase($value, $type, PlatformInterface $platform)
    {
        if (is_string($type)) {
            $type = Type::getType($type);
        }

        if ($type instanceof TypeInterface) {
            $value = $type->convertToDatabaseValue($value, $platform);
            $type = $type->getBindingType();
        }

        return array($value, $type);
    }
}
