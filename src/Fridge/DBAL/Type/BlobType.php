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
use Fridge\DBAL\Exception\TypeException;
use PDO;

/**
 * Blob type.
 *
 * @author GeLo <geloen.eric@gmail.com>
 * @author Loic Chardonnet <loic.chardonnet@gmail.com>
 */
class BlobType implements TypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(PlatformInterface $platform, array $options = array())
    {
        return $platform->getBlobSQLDeclaration($options);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, PlatformInterface $platform)
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Fridge\DBAL\Exception\TypeException If the database value can not be convert to this PHP value.
     */
    public function convertToPHPValue($value, PlatformInterface $platform)
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            $value = fopen('data://text/plain;base64,'.base64_encode($value), 'r');
        }

        if (!is_resource($value)) {
            throw TypeException::conversionToPHPFailed($value, $this->getName());
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getBindingType()
    {
        return PDO::PARAM_LOB;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Type::BLOB;
    }
}
