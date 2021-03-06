<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Schema\Comparator;

use Fridge\DBAL\Schema\Check;
use Fridge\DBAL\Schema\Diff\TableDiff;
use Fridge\DBAL\Schema\ForeignKey;
use Fridge\DBAL\Schema\Index;
use Fridge\DBAL\Schema\PrimaryKey;
use Fridge\DBAL\Schema\Table;

/**
 * Table comparator.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class TableComparator
{
    /** @var \Fridge\DBAL\Schema\Comparator\ColumnComparator */
    private $columnComparator;

    /**
     * Table comparator constructor.
     */
    public function __construct()
    {
        $this->columnComparator = new ColumnComparator();
    }

    /**
     * Compares two tables.
     *
     * @param \Fridge\DBAL\Schema\Table $oldTable The old table.
     * @param \Fridge\DBAL\Schema\Table $newTable The new table.
     *
     * @return \Fridge\DBAL\Schema\Diff\TableDiff The difference between the two tables.
     */
    public function compare(Table $oldTable, Table $newTable)
    {
        $createdColumns = $this->getCreatedColumns($oldTable, $newTable);
        $alteredColumns = $this->getAlteredColumns($oldTable, $newTable);
        $droppedColumns = $this->getDroppedColumns($oldTable, $newTable);

        $this->detectRenamedColumns($createdColumns, $droppedColumns, $alteredColumns);

        return new TableDiff(
            $oldTable,
            $newTable,
            $createdColumns,
            $alteredColumns,
            $droppedColumns,
            $this->getCreatedPrimaryKey($oldTable, $newTable),
            $this->getDroppedPrimaryKey($oldTable, $newTable),
            $this->getCreatedForeignKeys($oldTable, $newTable),
            $this->getDroppedForeignKeys($oldTable, $newTable),
            $this->getCreatedIndexes($oldTable, $newTable),
            $this->getDroppedIndexes($oldTable, $newTable),
            $this->getCreatedChecks($oldTable, $newTable),
            $this->getDroppedChecks($oldTable, $newTable)
        );
    }

    /**
     * Compares two primary keys.
     *
     * @param \Fridge\DBAL\Schema\PrimaryKey $oldPrimaryKey The old primary key.
     * @param \Fridge\DBAL\Schema\PrimaryKey $newPrimaryKey The new primary key.
     *
     * @return boolean TRUE if the primary keys are different else FALSE.
     */
    public function comparePrimaryKeys(PrimaryKey $oldPrimaryKey, PrimaryKey $newPrimaryKey)
    {
        return ($oldPrimaryKey->getName() !== $newPrimaryKey->getName())
            || ($oldPrimaryKey->getColumnNames() !== $newPrimaryKey->getColumnNames());
    }

    /**
     * Compares two foreign keys.
     *
     * @param \Fridge\DBAL\Schema\ForeignKey $oldForeignKey The old foreign key.
     * @param \Fridge\DBAL\Schema\ForeignKey $newForeignKey The new foreign key.
     *
     * @return boolean TRUE if foreign keys are different else FALSE.
     */
    public function compareForeignKeys(ForeignKey $oldForeignKey, ForeignKey $newForeignKey)
    {
        return ($oldForeignKey->getName() !== $newForeignKey->getName())
            || ($oldForeignKey->getLocalColumnNames() !== $newForeignKey->getLocalColumnNames())
            || ($oldForeignKey->getForeignTableName() !== $newForeignKey->getForeignTableName())
            || ($oldForeignKey->getForeignColumnNames() !== $newForeignKey->getForeignColumnNames())
            || ($oldForeignKey->getOnDelete() !== $newForeignKey->getOnDelete())
            || ($oldForeignKey->getOnUpdate() !== $newForeignKey->getOnUpdate());
    }

    /**
     * Compares two indexes.
     *
     * @param \Fridge\DBAL\Schema\Index $oldIndex The old index.
     * @param \Fridge\DBAL\Schema\Index $newIndex The new index.
     *
     * @return boolean TRUE if indexes are different else FALSE.
     */
    public function compareIndexes(Index $oldIndex, Index $newIndex)
    {
        return ($oldIndex->getName() !== $newIndex->getName())
            || ($oldIndex->getColumnNames() !== $newIndex->getColumnNames())
            || ($oldIndex->isUnique() !== $newIndex->isUnique());
    }

    /**
     * Compares two checks.
     *
     * @param \Fridge\DBAL\Schema\Check $oldCheck The old check.
     * @param \Fridge\DBAL\Schema\Check $newCheck The new check.
     *
     * @return boolean TRUE if checks are different else FALSE.
     */
    public function compareChecks(Check $oldCheck, Check $newCheck)
    {
        return ($oldCheck->getName() !== $newCheck->getName())
            || ($oldCheck->getDefinition() !== $newCheck->getDefinition());
    }

    /**
     * Gets the created columns according to the old/new tables.
     *
     * @param \Fridge\DBAL\Schema\Table $oldTable The old table.
     * @param \Fridge\DBAL\Schema\Table $newTable The new tabme.
     *
     * @return array The created columns.
     */
    private function getCreatedColumns(Table $oldTable, Table $newTable)
    {
        $createdColumns = array();

        foreach ($newTable->getColumns() as $column) {
            if (!$oldTable->hasColumn($column->getName())) {
                $createdColumns[] = $column;
            }
        }

        return $createdColumns;
    }

    /**
     * Gets the altered columns according to the old/new tables.
     *
     * @param \Fridge\DBAL\Schema\Table $oldTable The old table.
     * @param \Fridge\DBAL\Schema\Table $newTable The new table.
     *
     * @return array The altered columns
     */
    private function getAlteredColumns(Table $oldTable, Table $newTable)
    {
        $alteredColumns = array();

        foreach ($newTable->getColumns() as $column) {
            if ($oldTable->hasColumn($column->getName())) {
                $columnDiff = $this->columnComparator->compare($oldTable->getColumn($column->getName()), $column);

                if ($columnDiff->hasDifference()) {
                    $alteredColumns[] = $columnDiff;
                }
            }
        }

        return $alteredColumns;
    }

    /**
     * Gets the dropped columns according to the old/new tables.
     *
     * @param \Fridge\DBAL\Schema\Table $oldTable The old table.
     * @param \Fridge\DBAL\Schema\Table $newTable The new table.
     *
     * @return array The dropped columns.
     */
    private function getDroppedColumns(Table $oldTable, Table $newTable)
    {
        $droppedColumns = array();

        foreach ($oldTable->getColumns() as $column) {
            if (!$newTable->hasColumn($column->getName())) {
                $droppedColumns[] = $column;
            }
        }

        return $droppedColumns;
    }

    /**
     * Detects and rewrites renamed columns.
     *
     * @param array &$createdColumns The create columns.
     * @param array &$droppedColumns The dropped columns.
     * @param array &$alteredColumns The altered columns.
     */
    private function detectRenamedColumns(array &$createdColumns, array &$droppedColumns, array &$alteredColumns)
    {
        foreach ($createdColumns as $createdIndex => $createdColumn) {
            foreach ($droppedColumns as $droppedIndex => $droppedColumn) {
                $columnDiff = $this->columnComparator->compare($droppedColumn, $createdColumn);

                if ($columnDiff->hasNameDifferenceOnly()) {
                    $alteredColumns[] = $columnDiff;

                    unset($createdColumns[$createdIndex]);
                    unset($droppedColumns[$droppedIndex]);

                    break;
                }
            }
        }
    }

    /**
     * Gets the created primary key according to the old/new tables.
     *
     * @param \Fridge\DBAL\Schema\Table $oldTable The old table.
     * @param \Fridge\DBAL\Schema\Table $newTable The new table.
     *
     * @return \Fridge\DBAL\Schema\PrimaryKey|null The created primary key.
     */
    private function getCreatedPrimaryKey(Table $oldTable, Table $newTable)
    {
        if ((!$oldTable->hasPrimaryKey() && $newTable->hasPrimaryKey())
            || ($oldTable->hasPrimaryKey() && $newTable->hasPrimaryKey()
            && $this->comparePrimaryKeys($oldTable->getPrimaryKey(), $newTable->getPrimaryKey()))) {
            return $newTable->getPrimaryKey();
        }
    }

    /**
     * Gets the dropped primary key according to the old/new tables.
     *
     * @param \Fridge\DBAL\Schema\Table $oldTable The old table.
     * @param \Fridge\DBAL\Schema\Table $newTable The new table.
     *
     * @return \Fridge\DBAL\Schema\PrimaryKey|null The dropped primary key.
     */
    private function getDroppedPrimaryKey(Table $oldTable, Table $newTable)
    {
        if (($oldTable->hasPrimaryKey() && !$newTable->hasPrimaryKey())
            || ($oldTable->hasPrimaryKey() && $newTable->hasPrimaryKey()
            && $this->comparePrimaryKeys($oldTable->getPrimaryKey(), $newTable->getPrimaryKey()))) {
            return $oldTable->getPrimaryKey();
        }
    }

    /**
     * Gets the created foreign keys according to the old/new tables.
     *
     * @param \Fridge\DBAL\Schema\Table $oldTable The old table.
     * @param \Fridge\DBAL\Schema\Table $newTable The new table.
     *
     * @return array The created foreign keys.
     */
    private function getCreatedForeignKeys(Table $oldTable, Table $newTable)
    {
        $createdForeignKeys = array();

        foreach ($newTable->getForeignKeys() as $foreignKey) {
            if (!$oldTable->hasForeignKey($foreignKey->getName())
                || $this->compareForeignKeys($oldTable->getForeignKey($foreignKey->getName()), $foreignKey)) {
                $createdForeignKeys[] = $foreignKey;
            }
        }

        return $createdForeignKeys;
    }

    /**
     * Gets the dropped foreign keys according to the old/new tables.
     *
     * @param \Fridge\DBAL\Schema\Table $oldTable The old table.
     * @param \Fridge\DBAL\Schema\Table $newTable The new table.
     *
     * @return array The dropped foreign keys.
     */
    private function getDroppedForeignKeys(Table $oldTable, Table $newTable)
    {
        $droppedForeignKeys = array();

        foreach ($oldTable->getForeignKeys() as $foreignKey) {
            if (!$newTable->hasForeignKey($foreignKey->getName())
                || $this->compareForeignKeys($foreignKey, $newTable->getForeignKey($foreignKey->getName()))) {
                $droppedForeignKeys[] = $foreignKey;
            }
        }

        return $droppedForeignKeys;
    }

    /**
     * Gets the created indexes according to the old/new tables.
     *
     * @param \Fridge\DBAL\Schema\Table $oldTable The old table.
     * @param \Fridge\DBAL\Schema\Table $newTable The new table.
     *
     * @return array The crated indexes.
     */
    private function getCreatedIndexes(Table $oldTable, Table $newTable)
    {
        $createdIndexes = array();

        foreach ($newTable->getFilteredIndexes() as $index) {
            if (!$oldTable->hasIndex($index->getName())
                || $this->compareIndexes($oldTable->getIndex($index->getName()), $index)) {
                $createdIndexes[] = $index;
            }
        }

        return $createdIndexes;
    }

    /**
     * Gets the dropped indexes according to the old/new tables.
     *
     * @param \Fridge\DBAL\Schema\Table $oldTable The old table.
     * @param \Fridge\DBAL\Schema\Table $newTable The new table.
     *
     * @return array The dropped indexes.
     */
    private function getDroppedIndexes(Table $oldTable, Table $newTable)
    {
        $droppedIndexes = array();

        foreach ($oldTable->getFilteredIndexes() as $index) {
            if (!$newTable->hasIndex($index->getName())
                || $this->compareIndexes($index, $newTable->getIndex($index->getName()))) {
                $droppedIndexes[] = $index;
            }
        }

        return $droppedIndexes;
    }

    /**
     * Gets the created checks according to the old/new tables.
     *
     * @param \Fridge\DBAL\Schema\Table $oldTable The old table.
     * @param \Fridge\DBAL\Schema\Table $newTable The new table.
     *
     * @return array The crated checks.
     */
    private function getCreatedChecks(Table $oldTable, Table $newTable)
    {
        $createdChecks = array();

        foreach ($newTable->getChecks() as $check) {
            if (!$oldTable->hasCheck($check->getName())
                || $this->compareChecks($oldTable->getCheck($check->getName()), $check)) {
                $createdChecks[] = $check;
            }
        }

        return $createdChecks;
    }

    /**
     * Gets the dropped checks according to the old/new tables.
     *
     * @param \Fridge\DBAL\Schema\Table $oldTable The old table.
     * @param \Fridge\DBAL\Schema\Table $newTable The new table.
     *
     * @return array The dropped checks.
     */
    private function getDroppedChecks(Table $oldTable, Table $newTable)
    {
        $droppedChecks = array();

        foreach ($oldTable->getChecks() as $check) {
            if (!$newTable->hasCheck($check->getName())
                || $this->compareChecks($check, $newTable->getCheck($check->getName()))) {
                $droppedChecks[] = $check;
            }
        }

        return $droppedChecks;
    }
}
