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

use DateTime;
use Fridge\DBAL\Type\DateType;
use Fridge\DBAL\Type\Type;
use PDO;

/**
 * Date type test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class DateTypeTest extends AbstractTypeTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->type = new DateType();

        $this->platformMock
            ->expects($this->any())
            ->method('getDateFormat')
            ->will($this->returnValue('Y-m-d'));
    }

    public function testSQLDeclaration()
    {
        $this->platformMock
            ->expects($this->once())
            ->method('getDateSQLDeclaration');

        $this->type->getSQLDeclaration($this->platformMock);
    }

    public function testConvertToDatabaseValueWithValidValue()
    {
        $this->assertSame(
            '2012-01-01',
            $this->type->convertToDatabaseValue(new DateTime('2012-01-01 01:23:45'), $this->platformMock)
        );
    }

    public function testConvertToDatabaseValueWithNullValue()
    {
        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platformMock));
    }

    public function testConvertToPHPValueWithValidValue()
    {
        $this->assertEquals(
            new DateTime('2012-01-01'),
            $this->type->convertToPHPValue('2012-01-01', $this->platformMock)
        );
    }

    /**
     * @expectedException Fridge\DBAL\Exception\TypeException
     * @expectedExceptionMessage The value "foo" can not be converted to the type "date".
     */
    public function testConvertToPHPValueWithInvalidValue()
    {
        $this->type->convertToPHPValue('foo', $this->platformMock);
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
        $this->assertSame(Type::DATE, $this->type->getName());
    }
}
