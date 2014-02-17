<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Driver\Statement;

use Fridge\DBAL\Driver\Statement\DriverStatementInterface;
use Fridge\Tests\DBAL\Driver\Connection\AbstractDriverConnectionTestCase;

/**
 * Abstract driver statement test case.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractDriverStatementTestCase extends AbstractDriverConnectionTestCase
{
    /** @var \Fridge\DBAL\Driver\Statement\DriverStatementInterface */
    private $statement;

    /**
     * Sets up the statement.
     *
     * @return \Fridge\DBAL\Driver\Statement\DriverStatementInterface The statement.
     */
    abstract protected function setUpStatement();

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setStatement($this->setUpStatement());
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->statement);
    }

    /**
     * Gets the statement.
     *
     * @return \Fridge\DBAL\Driver\Statement\DriverStatementInterface The statement.
     */
    protected function getStatement()
    {
        return $this->statement;
    }

    /**
     * Sets the statement.
     *
     * @param \Fridge\DBAL\Driver\Statement\DriverStatementInterface $statement The statement.
     */
    protected function setStatement(DriverStatementInterface $statement)
    {
        $this->statement = $statement;
    }
}
