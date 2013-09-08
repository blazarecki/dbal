<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Connection;

use Fridge\Tests\DBAL\Driver\Connection\AbstractNativeConnectionTestCase;

/**
 * Abstract connection test case.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractConnectionTestCase extends AbstractNativeConnectionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        if ($this->getConnection() !== null) {
            $this->getConnection()->close();
        }

        parent::tearDown();
    }
}
