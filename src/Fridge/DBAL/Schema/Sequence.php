<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Schema;

use Fridge\DBAL\Exception\SchemaException;

/**
 * Describes a database sequence.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class Sequence extends AbstractAsset
{
    /** @var integer */
    private $initialValue;

    /** @var integer */
    private $incrementSize;

    /**
     * Creates a sequence.
     *
     * @param string  $name          The sequence name.
     * @param integer $initialValue  The sequence initial value.
     * @param integer $incrementSize The sequence increment size.
     */
    public function __construct($name, $initialValue = 1, $incrementSize = 1)
    {
        parent::__construct($name);

        $this->setInitialValue($initialValue);
        $this->setIncrementSize($incrementSize);
    }

    /**
     * Gets the initial value.
     *
     * @return integer The initial value.
     */
    public function getInitialValue()
    {
        return $this->initialValue;
    }

    /**
     * Sets the initial value.
     *
     * @param integer $initialValue The initial value.
     *
     * @throws \Fridge\DBAL\Exception\SchemaException If the initial value is not a positive integer.
     */
    public function setInitialValue($initialValue)
    {
        if (!is_int($initialValue) || ($initialValue <= 0)) {
            throw SchemaException::invalidSequenceInitialValue($this->getName());
        }

        $this->initialValue = $initialValue;
    }

    /**
     * Gets the increment size.
     *
     * @return integer The increment size.
     */
    public function getIncrementSize()
    {
        return $this->incrementSize;
    }

    /**
     * Sets the increment size.
     *
     * @param integer $incrementSize The increment size.
     *
     * @throws \Fridge\DBAL\Exception\SchemaException If the increment size is not a positive integer.
     */
    public function setIncrementSize($incrementSize)
    {
        if (!is_int($incrementSize) || ($incrementSize <= 0)) {
            throw SchemaException::invalidSequenceIncrementSize($this->getName());
        }

        $this->incrementSize = $incrementSize;
    }
}
