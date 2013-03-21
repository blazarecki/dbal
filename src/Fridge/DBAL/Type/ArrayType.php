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

use Fridge\DBAL\Exception\TypeException;
use Fridge\DBAL\Platform\PlatformInterface;
use PDO;

/**
 * Array type.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class ArrayType implements TypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(PlatformInterface $platform, array $options = array())
    {
        return $platform->getClobSQLDeclaration($options);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, PlatformInterface $platform)
    {
        return ($value === null) ? null : serialize($value);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Fridge\DBAL\Exception\TypeException If the database value can not be convert to his PHP value.
     */
    public function convertToPHPValue($value, PlatformInterface $platform)
    {
        if ($value === null) {
            return null;
        }

        $val = unserialize($value);

        if ($val === false) {
            throw TypeException::conversionToPHPFailed($value, $this->getName());
        }

        return $val;
    }

    /**
     * {@inheritdoc}
     */
    public function getBindingType()
    {
        return PDO::PARAM_STR;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Type::TARRAY;
    }
}
