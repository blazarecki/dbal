<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager\Alteration\Column;

use Fridge\DBAL\Schema\Column;
use Fridge\DBAL\Schema\Comparator\ColumnComparator;
use Fridge\DBAL\Schema\Table;
use Fridge\DBAL\Type\Type;
use Fridge\Tests\DBAL\SchemaManager\Alteration\AbstractAlterationTestCase;

/**
 * Abstract column alteration test case.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractColumnAlterationTestCase extends AbstractAlterationTestCase
{
    /** @var \Fridge\DBAL\Schema\Table */
    private $table;

    /** @var \Fridge\DBAL\Schema\Column */
    private $oldColumn;

    /** @var \Fridge\DBAL\Schema\Column */
    private $newColumn;

    /**
     * {@inheritdoc}
     */
    protected function setUpComparator()
    {
        return new ColumnComparator();
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->oldColumn = $this->setUpOldColumn();
        $this->table = $this->setUpTable();
    }

    /**
     * Sets up the table.
     *
     * @return \Fridge\DBAL\Schema\Table
     */
    protected function setUpTable()
    {
        return new Table('foo', array($this->oldColumn));
    }

    /**
     * Sets up the old column.
     *
     * @return \Fridge\DBAL\Schema\Column The old column.
     */
    protected function setUpOldColumn()
    {
        return new Column('foo', Type::getType(Type::STRING));
    }

    /**
     * Sets up the new column.
     */
    protected function setUpNewColumn()
    {
        $this->newColumn = clone $this->oldColumn;
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->table);
        unset($this->oldColumn);
        unset($this->newColumn);
    }

    /**
     * Gets the table.
     *
     * @return \Fridge\DBAL\Schema\Table The table.
     */
    protected function getTable()
    {
        return $this->table;
    }

    /**
     * Gets the old column.
     *
     * @return \Fridge\DBAL\Schema\Column The old column.
     */
    protected function getOldColumn()
    {
        return $this->oldColumn;
    }

    /**
     * Gets the new column.
     *
     * @return \Fridge\DBAL\Schema\Column The new column.
     */
    protected function getNewColumn()
    {
        return $this->newColumn;
    }
}
