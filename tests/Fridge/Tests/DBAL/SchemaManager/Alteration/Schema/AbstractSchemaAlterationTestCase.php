<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager\Alteration\Schema;

use Fridge\DBAL\Schema\Comparator\SchemaComparator;
use Fridge\DBAL\Schema\Schema;
use Fridge\Tests\DBAL\SchemaManager\Alteration\AbstractAlterationTestCase;

/**
 * Abstract schema alteration test case.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractSchemaAlterationTestCase extends AbstractAlterationTestCase
{
    /** @var \Fridge\DBAL\Schema\Schema */
    private $oldSchema;

    /** @var \Fridge\DBAL\Schema\Schema */
    private $newSchema;

    /**
     * {@inheritdoc}
     */
    protected function setUpComparator()
    {
        return new SchemaComparator();
    }

    /**
     * Sets up the old schema.
     *
     * @return \Fridge\DBAL\Schema\Schema The old schema.
     */
    protected function setUpOldSchema()
    {
        return new Schema(self::getFixture()->getSetting('dbname'));
    }

    /**
     * Sets up the new schema.
     */
    protected function setUpNewSchema()
    {
        $this->newSchema = clone $this->oldSchema;
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->oldSchema = $this->setUpOldSchema();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->oldSchema);
        unset($this->newSchema);
    }

    /**
     * Gets the old schema.
     *
     * @return \Fridge\DBAL\Schema\Schema The old schema.
     */
    public function getOldSchema()
    {
        return $this->oldSchema;
    }

    /**
     * Gets the new schema.
     *
     * @return \Fridge\DBAL\Schema\Schema The new schema.
     */
    public function getNewSchema()
    {
        return $this->newSchema;
    }
}
