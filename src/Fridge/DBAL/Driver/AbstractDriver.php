<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Driver;

use Fridge\DBAL\Connection\ConnectionInterface;

/**
 * Abstract driver.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractDriver implements DriverInterface
{
    /** @var \Fridge\DBAL\Platform\PlatformInterface */
    private $platform;

    /** @var \Fridge\DBAL\SchemaManager\SchemaManagerInterface */
    private $schemaManager;

    /**
     * Creates a platform.
     *
     * @return \Fridge\DBAL\Platform\PlatformInterface The platform.
     */
    abstract protected function createPlatform();

    /**
     * Creates a schema manager.
     *
     * @param \Fridge\DBAL\Connection\ConnectionInterface The connection.
     *
     * @return \Fridge\DBAL\SchemaManager\SchemaManagerInterface The schema manager.
     */
    abstract protected function createSchemaManager(ConnectionInterface $connection);

    /**
     * {@inheritdoc}
     */
    public function getPlatform()
    {
        if ($this->platform === null) {
            $this->platform = $this->createPlatform();
        }

        return $this->platform;
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(ConnectionInterface $connection)
    {
        if (($this->schemaManager === null) || ($this->schemaManager->getConnection() !== $connection)) {
            $this->schemaManager = $this->createSchemaManager($connection);
        }

        return $this->schemaManager;
    }
}
