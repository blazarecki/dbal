<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Schema\Diff;

use Fridge\DBAL\Schema\Schema;

/**
 * Describes the difference between two schemas.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class SchemaDiff extends AbstractAssetDiff
{
    /** @var array */
    private $createdTables;

    /** @var array */
    private $alteredTables;

    /** @var array */
    private $droppedTables;

    /** @var array */
    private $createdSequences;

    /** @var array */
    private $droppedSequences;

    /** @var array */
    private $createdViews;

    /** @var array */
    private $droppedViews;

    /**
     * Schema diff constructor.
     *
     * @param \Fridge\DBAL\Schema\Schema $oldSchema        The old schema.
     * @param \Fridge\DBAL\Schema\Schema $newSchema        The new schema.
     * @param array                      $createdTables    The created tables.
     * @param array                      $alteredTables    The altered tables.
     * @param array                      $droppedTables    The dropped tables.
     * @param array                      $createdSequences The created sequences.
     * @param array                      $droppedSequences The dropped sequences.
     * @param array                      $createdViews     The created views.
     * @param array                      $droppedViews     The dropped views.
     */
    public function __construct(
        Schema $oldSchema,
        Schema $newSchema,
        array $createdTables = array(),
        array $alteredTables = array(),
        array $droppedTables = array(),
        array $createdSequences = array(),
        array $droppedSequences = array(),
        array $createdViews = array(),
        array $droppedViews = array()
    ) {
        parent::__construct($oldSchema, $newSchema);

        $this->createdTables = $createdTables;
        $this->alteredTables = $alteredTables;
        $this->droppedTables = $droppedTables;

        $this->createdSequences = $createdSequences;
        $this->droppedSequences = $droppedSequences;

        $this->createdViews = $createdViews;
        $this->droppedViews = $droppedViews;
    }

    /**
     * Gets the created tables.
     *
     * @return array The created tables.
     */
    public function getCreatedTables()
    {
        return $this->createdTables;
    }

    /**
     * Gets the altered tables.
     *
     * @return array The altered tables.
     */
    public function getAlteredTables()
    {
        return $this->alteredTables;
    }

    /**
     * Gets the dropped tables.
     *
     * @return array The dropped tables.
     */
    public function getDroppedTables()
    {
        return $this->droppedTables;
    }

    /**
     * Gets the created sequences.
     *
     * @return array The created sequences.
     */
    public function getCreatedSequences()
    {
        return $this->createdSequences;
    }

    /**
     * Gets the dropped sequences.
     *
     * @return array The dropped sequences.
     */
    public function getDroppedSequences()
    {
        return $this->droppedSequences;
    }

    /**
     * Gets the created views.
     *
     * @return array The created views.
     */
    public function getCreatedViews()
    {
        return $this->createdViews;
    }

    /**
     * Gets the dropped views.
     *
     * @return array The dropped views.
     */
    public function getDroppedViews()
    {
        return $this->droppedViews;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDifference()
    {
        return parent::hasDifference()
            || $this->hasTableDifference()
            || $this->hasSequenceDifference()
            || $this->hasViewDifference();
    }

    /**
     * {@inheritdoc}
     */
    public function hasNameDifferenceOnly()
    {
        return parent::hasNameDifferenceOnly()
            && !$this->hasTableDifference()
            && !$this->hasSequenceDifference()
            && !$this->hasViewDifference();
    }

    /**
     * Checks if the schema diff has table difference.
     *
     * @return boolean TRUE if the schema diff has table difference else FALSE.
     */
    private function hasTableDifference()
    {
        return !empty($this->createdTables) || !empty($this->alteredTables) || !empty($this->droppedTables);
    }

    /**
     * Checks if the schema diff has sequence difference.
     *
     * @return boolean TRUE if the schema diff has sequence difference else FALSE.
     */
    private function hasSequenceDifference()
    {
        return !empty($this->createdSequences) || !empty($this->droppedSequences);
    }

    /**
     * Checks if the schema diff has view difference.
     *
     * @return boolean TRUE if the schema diff has view difference else FALSE.
     */
    private function hasViewDifference()
    {
        return !empty($this->createdViews) || !empty($this->droppedViews);
    }
}
