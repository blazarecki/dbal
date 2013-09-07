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

use Fridge\DBAL\Exception\PlatformException;

/**
 * Base MySQL alteration test case.
 *
 * All MySQL alteration test must extend this class.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractMySQLTableAlterationTest extends AbstractTableAlterationTest
{
    public function testCreatePrimaryKey()
    {
        $this->setUpColumns();
        $this->getNewTable()->createPrimaryKey(array('foo'), 'PRIMARY');

        $this->assertAlteration();
    }

    public function testAlterPrimaryKey()
    {
        $this->setUpPrimaryKey();

        $this->getNewTable()->dropPrimaryKey();
        $this->getNewTable()->createPrimaryKey(array('bar'), 'PRIMARY');

        $this->assertAlteration();
    }

    public function testCreateCheck()
    {
        try {
            parent::testCreateCheck();

            $this->fail();
        } catch (PlatformException $e) {
            $this->resetNewTable();
        }
    }

    public function testAlterCheck()
    {
        try {
            parent::testAlterCheck();

            $this->fail();
        } catch (PlatformException $e) {
            $this->resetNewTable();
        }
    }

    public function testDropCheck()
    {
        try {
            parent::testDropCheck();

            $this->fail();
        } catch (PlatformException $e) {
            $this->resetNewTable();
        }
    }
}
