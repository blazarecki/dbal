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

use DateTime;
use Fridge\DBAL\Exception\TypeException;
use Fridge\DBAL\Platform\PlatformInterface;
use PDO;

/**
 * Date type.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class DateType implements TypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(PlatformInterface $platform, array $options = array())
    {
        return $platform->getDateSQLDeclaration($options);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, PlatformInterface $platform)
    {
        return ($value === null) ? null : $value->format($platform->getDateFormat());
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

        $phpValue = DateTime::createFromFormat('!'.$platform->getDateFormat(), $value);

        if ($phpValue === false) {
            throw TypeException::conversionToPHPFailed($value, $this->getName());
        }

        return $phpValue;
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
        return Type::DATE;
    }
}
