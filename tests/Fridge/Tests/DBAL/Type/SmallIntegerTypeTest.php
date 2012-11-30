<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Type;

use \PDO;

use Fridge\DBAL\Type\SmallIntegerType,
    Fridge\DBAL\Type\Type;

/**
 * Small Integer type test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class SmallIntegerTypeTest extends AbstractTypeTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->type = new SmallIntegerType();
    }

    public function testSQLDeclaration()
    {
        $this->platformMock
            ->expects($this->once())
            ->method('getSmallIntegerSQLDeclaration');

        $this->type->getSQLDeclaration($this->platformMock);
    }

    public function testConvertToDatabaseValueWithValidValue()
    {
        $this->assertSame(1, $this->type->convertToDatabaseValue(1, $this->platformMock));
    }

    public function testConvertToDatabaseValueWithNullValue()
    {
        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platformMock));
    }

    public function testConvertToPHPValueWithValidValue()
    {
        $this->assertSame(1, $this->type->convertToPHPValue(1, $this->platformMock));
    }

    public function testConvertToPHPValueWithNullValue()
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platformMock));
    }

    public function testBindingType()
    {
        $this->assertSame(PDO::PARAM_INT, $this->type->getBindingType());
    }

    public function testName()
    {
        $this->assertSame(Type::SMALLINTEGER, $this->type->getName());
    }
}
