<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Schema;

use Fridge\DBAL\Schema\PrimaryKey;

/**
 * Primary key test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class PrimaryKeyTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Schema\PrimaryKey */
    private $primaryKey;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->primaryKey = new PrimaryKey('foo');
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->primaryKey);
    }

    public function testInitialState()
    {
        $this->assertSame('foo', $this->primaryKey->getName());
        $this->assertEmpty($this->primaryKey->getColumnNames());
    }

    public function testGeneratedName()
    {
        $primaryKey = new PrimaryKey(null);
        $this->assertRegExp('/^pk_[a-z0-9]{17}$/', $primaryKey->getName());
    }

    public function testColumnNamesWithValidValues()
    {
        $columnNames = array('foo', 'bar');
        $this->primaryKey->setColumnNames($columnNames);

        $this->assertSame($columnNames, $this->primaryKey->getColumnNames());
    }

    /**
     * @expectedException Fridge\DBAL\Exception\SchemaException
     * @expectedExceptionMessage The column name of the primary key "foo" must be a string.
     */
    public function testColumnNamesWithInvalidValues()
    {
        $this->primaryKey->setColumnNames(array(true));
    }
}
