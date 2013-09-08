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

use Fridge\DBAL\Type\DateTimeType;
use Fridge\DBAL\Type\Type;

/**
 * DateTime type test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class DateTimeTypeTest extends AbstractTypeTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUpType()
    {
        return new DateTimeType();
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->getPlatform()
            ->expects($this->any())
            ->method('getDateTimeFormat')
            ->will($this->returnValue('Y-m-d H:i:s'));
    }

    public function testSQLDeclaration()
    {
        $this->getPlatform()
            ->expects($this->once())
            ->method('getDateTimeSQLDeclaration');

        $this->getType()->getSQLDeclaration($this->getPlatform());
    }

    public function testConvertToDatabaseValueWithValidValue()
    {
        $this->assertSame(
            '2012-01-01 01:23:45',
            $this->getType()->convertToDatabaseValue(new \DateTime('2012-01-01 01:23:45'), $this->getPlatform())
        );
    }

    public function testConvertToDatabaseValueWithNullValue()
    {
        $this->assertNull($this->getType()->convertToDatabaseValue(null, $this->getPlatform()));
    }

    public function testConvertToPHPValueWithValidValue()
    {
        $this->assertEquals(
            new \DateTime('2012-01-01 01:23:45'),
            $this->getType()->convertToPHPValue('2012-01-01 01:23:45', $this->getPlatform())
        );
    }

    /**
     * @expectedException Fridge\DBAL\Exception\TypeException
     */
    public function testConvertToPHPValueWithInvalidValue()
    {
        $this->getType()->convertToPHPValue('foo', $this->getPlatform());
    }

    public function testConvertToPHPValueWithNullValue()
    {
        $this->assertNull($this->getType()->convertToPHPValue(null, $this->getPlatform()));
    }

    public function testBindingType()
    {
        $this->assertSame(\PDO::PARAM_STR, $this->getType()->getBindingType());
    }

    public function testName()
    {
        $this->assertSame(Type::DATETIME, $this->getType()->getName());
    }
}
