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

use Fridge\DBAL\Type\BigIntegerType;
use Fridge\DBAL\Type\Type;

/**
 * Big integer type test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class BigIntegerTypeTest extends AbstractTypeTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUpType()
    {
        return new BigIntegerType();
    }

    public function testSQLDeclaration()
    {
        $this->getPlatform()
            ->expects($this->once())
            ->method('getBigIntegerSQLDeclaration');

        $this->getType()->getSQLDeclaration($this->getPlatform());
    }

    public function testConvertToDatabaseValueWithValidValue()
    {
        $this->assertSame('1000000000', $this->getType()->convertToDatabaseValue(1000000000, $this->getPlatform()));
    }

    public function testConvertToDatabaseValueWithNullValue()
    {
        $this->assertNull($this->getType()->convertToDatabaseValue(null, $this->getPlatform()));
    }

    public function testConvertToPHPValueWithValidValue()
    {
        $this->assertSame(1000000000, $this->getType()->convertToPHPValue('1000000000', $this->getPlatform()));
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
        $this->assertSame(Type::BIGINTEGER, $this->getType()->getName());
    }
}
