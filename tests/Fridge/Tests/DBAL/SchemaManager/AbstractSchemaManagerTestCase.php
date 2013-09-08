<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager;

use Fridge\Tests\Fixture\AbstractFixtureTestCase;

/**
 * Abstract schema manager test case.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractSchemaManagerTestCase extends AbstractFixtureTestCase
{
    /** @var \Fridge\DBAL\SchemaManager\SchemaManagerInterface */
    private $schemaManager;

    /**
     * Sets up the schema manager.
     *
     * @return \Fridge\DBAL\SchemaManager\SchemaManagerInterface The schema manager.
     */
    abstract protected function setUpSchemaManager();

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->schemaManager = $this->setUpSchemaManager();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        if ($this->schemaManager !== null) {
            $this->schemaManager->getConnection()->close();
        }

        unset($this->schemaManager);
    }

    /**
     * Gets the schema manager.
     *
     * @return \Fridge\DBAL\SchemaManager\SchemaManagerInterface The schema manager.
     */
    public function getSchemaManager()
    {
        return $this->schemaManager;
    }
}
