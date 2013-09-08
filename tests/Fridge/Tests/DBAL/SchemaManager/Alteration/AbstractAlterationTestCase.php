<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager\Alteration;

use Fridge\Tests\DBAL\SchemaManager\AbstractSchemaManagerTestCase;

/**
 * Abstract alteration test case.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractAlterationTestCase extends AbstractSchemaManagerTestCase
{
    /** @var mixed */
    private $comparator;

    /**
     * Sets up the comparator.
     *
     * @retun mixed The comparator.
     */
    abstract protected function setUpComparator();

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->comparator = $this->setUpComparator();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->comparator);
    }

    /**
     * Gets the comparator.
     *
     * @return mixed The comparator.
     */
    public function getComparator()
    {
        return $this->comparator;
    }
}
