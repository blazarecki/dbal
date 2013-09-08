<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager\Alteration\Table;

use Fridge\DBAL\Schema\Comparator\TableComparator;
use Fridge\DBAL\Schema\Table;
use Fridge\Tests\DBAL\SchemaManager\Alteration\AbstractAlterationTestCase;

/**
 * Abstract table alteration test case.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractTableAlterationTestCase extends AbstractAlterationTestCase
{
    /** @var \Fridge\DBAL\Schema\Table */
    private $oldTable;

    /** @var \Fridge\DBAL\Schema\Table */
    private $newTable;

    /** @var \Fridge\DBAL\Schema\Table */
    private $oldForeignKeyTable;

    /** @var \Fridge\DBAL\Schema\Table */
    private $newForeignKeyTable;

    /**
     * {@inheritdoc}
     */
    protected function setUpComparator()
    {
        return new TableComparator();
    }

    /**
     * Sets up the old table.
     *
     * @retun \Fridge\DBAL\Schema\Table The old table.
     */
    protected function setUpOldTable()
    {
        return new Table('foo');
    }

    /**
     * Sets up the new table.
     */
    protected function setUpNewTable()
    {
        $this->newTable = clone $this->oldTable;
    }

    /**
     * Sets up the old foreign key table.
     *
     * @return \Fridge\DBAL\Schema\Table The old foreign key table.
     */
    protected function setUpOldForeignKeyTable()
    {
        return new Table('bar');
    }

    /**
     * Sets up the new foreign key table.
     */
    protected function setUpNewForeignKeyTable()
    {
        $this->newForeignKeyTable = clone $this->oldForeignKeyTable;
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->oldTable = $this->setUpOldTable();
        $this->oldForeignKeyTable = $this->setUpOldForeignKeyTable();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->oldTable);
        unset($this->newTable);

        unset($this->oldForeignKeyTable);
        unset($this->newForeignKeyTable);
    }

    /**
     * Gets the old table.
     *
     * @return \Fridge\DBAL\Schema\Table The old table.
     */
    protected function getOldTable()
    {
        return $this->oldTable;
    }

    /**
     * Gets the new table.
     *
     * @return \Fridge\DBAL\Schema\Table The new table.
     */
    protected function getNewTable()
    {
        return $this->newTable;
    }

    /**
     * Resets the new table.
     */
    protected function resetNewTable()
    {
        $this->newTable = null;
    }

    /**
     * Gets the old foreign key table.
     *
     * @return \Fridge\DBAL\Schema\Table The old foreign key table.
     */
    protected function getOldForeignKeyTable()
    {
        return $this->oldForeignKeyTable;
    }

    /**
     * Gets the new foreign key table.
     *
     * @return \Fridge\DBAL\Schema\Table The new foreign key table.
     */
    protected function getNewForeignKeyTable()
    {
        return $this->newForeignKeyTable;
    }
}
