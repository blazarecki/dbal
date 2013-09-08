<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\SchemaManager\Alteration\Schema;

use Fridge\DBAL\Schema\ForeignKey;
use Fridge\DBAL\Type\Type;

/**
 * Abstract MySQL schema alteration test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractMySQLSchemaAlterationTest extends AbstractSchemaAlterationTest
{
    /**
     * @expectedException \Fridge\DBAL\Exception\PlatformException
     */
    public function testCreateSequence()
    {
        parent::testCreateSequence();
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\PlatformException
     */
    public function testRenameSequence()
    {
        parent::testRenameSequence();
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\PlatformException
     */
    public function testAlterSequence()
    {
        parent::testAlterSequence();
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\PlatformException
     */
    public function testDropSequence()
    {
        parent::testDropSequence();
    }

    public function testCreateView()
    {
        $dbName = self::getFixture()->getSetting('dbname');

        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $this->createOldSchema();

        $this->getNewSchema()->createView(
            'vfoo',
            sprintf('select `%s`.`foo`.`foo` AS `foo` from `%s`.`foo`', $dbName, $dbName)
        );

        $this->assertAlteration();
    }

    public function testRenameView()
    {
        $dbName = self::getFixture()->getSetting('dbname');

        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));

        $this->getOldSchema()->createView(
            'vfoo',
            sprintf('select `%s`.`foo`.`foo` AS `foo` from `%s`.`foo`', $dbName, $dbName)
        );

        $this->createOldSchema();

        $this->getNewSchema()->renameView('vfoo', 'vbar');

        $this->assertAlteration();
    }

    public function testAlterView()
    {
        $dbName = self::getFixture()->getSetting('dbname');

        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $table->createColumn('bar', Type::STRING, array('length' => 50));

        $view = $this->getOldSchema()->createView(
            'vfoo',
            sprintf('select `%s`.`foo`.`foo` AS `foo` from `%s`.`foo`', $dbName, $dbName)
        );

        $this->createOldSchema();

        $this->getNewSchema()
            ->getView($view->getName())
            ->setSQL(
                sprintf('select `%s`.`foo`.`bar` AS `foo` from `%s`.`foo`', $dbName, $dbName)
            );

        $this->assertAlteration();
    }

    public function testCreatePrimaryKey()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->createPrimaryKey(array('foo'), 'PRIMARY');

        $this->assertAlteration();
    }

    public function testAlterPrimaryKey()
    {
        $table = $this->getOldSchema()->createTable('foo');
        $table->createColumn('foo', Type::STRING, array('length' => 50));
        $table->createColumn('bar', Type::STRING, array('length' => 50));
        $table->createPrimaryKey(array('foo'), 'PRIMARY');
        $this->createOldSchema();

        $this->getNewSchema()->getTable($table->getName())->dropPrimaryKey();
        $this->getNewSchema()->getTable($table->getName())->createPrimaryKey(array('bar'), 'PRIMARY');

        $this->assertAlteration();
    }

    public function testCreateForeignKey()
    {
        $table1 = $this->getOldSchema()->createTable('foo');
        $table1->createColumn('foo', Type::STRING, array('length' => 50));
        $table1->createPrimaryKey(array('foo'), 'PRIMARY');

        $table2 = $this->getOldSchema()->createTable('bar');
        $table2->createColumn('foo', Type::STRING, array('length' => 50));

        $this->createOldSchema();

        $this->getNewSchema()->getTable($table2->getName())->createForeignKey(
            array('foo'),
            'foo',
            array('foo'),
            ForeignKey::RESTRICT,
            ForeignKey::RESTRICT,
            'fk_foo'
        );

        $this->assertAlteration();
    }

    public function testDropForeignKey()
    {
        $table1 = $this->getOldSchema()->createTable('foo');
        $table1->createColumn('foo', Type::STRING, array('length' => 50));
        $table1->createPrimaryKey(array('foo'), 'PRIMARY');

        $table2 = $this->getOldSchema()->createTable('bar');
        $table2->createColumn('foo', Type::STRING, array('length' => 50));
        $foreignKey = $table2->createForeignKey(
            array('foo'),
            'foo',
            array('foo'),
            ForeignKey::RESTRICT,
            ForeignKey::RESTRICT,
            'fk_foo'
        );

        $this->createOldSchema();

        $this->getNewSchema()->getTable($table2->getName())->dropForeignKey($foreignKey->getName());

        $this->assertAlteration();
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\PlatformException
     */
    public function testCreateCheck()
    {
        parent::testCreateCheck();
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\PlatformException
     */
    public function testRenameCheck()
    {
        parent::testRenameCheck();
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\PlatformException
     */
    public function testAlterCheck()
    {
        parent::testAlterCheck();
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\PlatformException
     */
    public function testDropCheck()
    {
        parent::testDropCheck();
    }
}
