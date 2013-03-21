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

use Fridge\DBAL\Type\DecimalType;
use Fridge\DBAL\Type\Type;
use PDO;

/**
 * Decimal type test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class DecimalTypeTest extends AbstractTypeTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->type = new DecimalType();
    }

    public function testSQLDeclaration()
    {
        $this->platformMock
            ->expects($this->once())
            ->method('getDecimalSQLDeclaration');

        $this->type->getSQLDeclaration($this->platformMock);
    }

    public function testConvertToDatabaseValueWithValidValue()
    {
        $this->assertSame('1.12', $this->type->convertToDatabaseValue(1.12, $this->platformMock));
    }

    public function testConvertToDatabaseValueWithNullValue()
    {
        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platformMock));
    }

    public function testConvertToPHPValueWithValidValue()
    {
        $this->assertSame(1.12, $this->type->convertToPHPValue('1.12', $this->platformMock));
    }

    public function testConvertToPHPValueWithNullValue()
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platformMock));
    }

    public function testBindingType()
    {
        $this->assertSame(PDO::PARAM_STR, $this->type->getBindingType());
    }

    public function testName()
    {
        $this->assertSame(Type::DECIMAL, $this->type->getName());
    }
}
