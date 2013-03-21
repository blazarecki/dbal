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
use PDO;

/**
 * Boolean type.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class BooleanType implements TypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(PlatformInterface $platform, array $options = array())
    {
        return $platform->getBooleanSQLDeclaration($options);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, PlatformInterface $platform)
    {
        return ($value === null) ? null : (int) (bool) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, PlatformInterface $platform)
    {
        return ($value === null) ? null : (bool) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getBindingType()
    {
        return PDO::PARAM_BOOL;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Type::BOOLEAN;
    }
}
