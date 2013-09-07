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

use Fridge\DBAL\Type\Type;

/**
 * Abstract column alteration test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractColumnAlterationTest extends AbstractColumnAlterationTestCase
{
    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::getFixture()->createDatabase();
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        if (self::hasFixture()) {
            self::getFixture()->dropDatabase();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        if ($this->getSchemaManager() !== null) {
            $this->getSchemaManager()->dropTable($this->getTable());
        }

        parent::tearDown();
    }

    /**
     * Sets up a string column.
     */
    protected function setUpStringColumn()
    {
        $this->getOldColumn()->setLength(50);

        $this->createOldColumn();
    }

    /**
     * Sets up a decimal column.
     */
    protected function setUpDecimalColumn()
    {
        $this->getOldColumn()->setType(Type::getType(Type::DECIMAL));
        $this->getOldColumn()->setPrecision(10);
        $this->getOldColumn()->setScale(2);

        $this->createOldColumn();
    }

    /**
     * Sets up a column.
     */
    protected function createOldColumn()
    {
        $this->setUpNewColumn();
        $this->getSchemaManager()->createTable($this->getTable());
    }

    public function testRename()
    {
        $this->setUpStringColumn();
        $this->getNewColumn()->setName('bar');

        $this->assertAlteration();
    }

    public function testType()
    {
        $this->setUpStringColumn();

        $this->getNewColumn()->setType(Type::getType(Type::TEXT));
        $this->getNewColumn()->setLength(null);

        $this->assertAlteration();
    }

    public function testMandatoryType()
    {
        $this->setUpStringColumn();

        $this->getNewColumn()->setType(Type::getType(Type::TARRAY));
        $this->getNewColumn()->setLength(null);

        $this->assertAlteration();
    }

    public function testLength()
    {
        $this->setUpStringColumn();
        $this->getNewColumn()->setLength(100);

        $this->assertAlteration();
    }

    public function testPrecision()
    {
        $this->setUpDecimalColumn();
        $this->getNewColumn()->setPrecision(8);

        $this->assertAlteration();
    }

    public function testScale()
    {
        $this->setUpDecimalColumn();
        $this->getNewColumn()->setScale(3);

        $this->assertAlteration();
    }

    public function testSetNotNull()
    {
        $this->setUpStringColumn();
        $this->getNewColumn()->setNotNull(true);

        $this->assertAlteration();
    }

    public function testDropNotNull()
    {
        $this->getOldColumn()->setNotNull(true);
        $this->getOldColumn()->setLength(50);

        $this->createOldColumn();

        $this->getNewColumn()->setNotNull(false);

        $this->assertAlteration();
    }

    public function testSetDefault()
    {
        $this->setUpStringColumn();
        $this->getNewColumn()->setDefault('foo');

        $this->assertAlteration();
    }

    public function testDropDefault()
    {
        $this->getOldColumn()->setDefault('foo');
        $this->getOldColumn()->setLength(50);

        $this->createOldColumn();

        $this->getNewColumn()->setDefault(null);

        $this->assertAlteration();
    }

    public function testSetComment()
    {
        $this->setUpStringColumn();
        $this->getNewColumn()->setComment('foo');

        $this->assertAlteration();
    }

    public function testDropComment()
    {
        $this->getOldColumn()->setComment('foo');
        $this->getOldColumn()->setLength(50);

        $this->createOldColumn();

        $this->getNewColumn()->setComment(null);

        $this->assertAlteration();
    }

    /**
     * Asserts the old column is altered.
     */
    protected function assertAlteration()
    {
        $columnDiff = $this->getComparator()->compare($this->getOldColumn(), $this->getNewColumn());
        $this->getSchemaManager()->alterColumn($columnDiff, $this->getTable()->getName());

        $this->assertEquals(
            array($this->getNewColumn()),
            $this->getSchemaManager()->getColumns($this->getTable()->getName())
        );
    }
}
