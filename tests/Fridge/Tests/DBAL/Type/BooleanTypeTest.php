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

use Fridge\DBAL\Type\BooleanType;
use Fridge\DBAL\Type\Type;

/**
 * Boolean type test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class BooleanTypeTest extends AbstractTypeTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUpType()
    {
        return new BooleanType();
    }

    public function testSQLDeclaration()
    {
        $this->getPlatform()
            ->expects($this->once())
            ->method('getBooleanSQLDeclaration');

        $this->getType()->getSQLDeclaration($this->getPlatform());
    }

    public function testConvertToDatabaseValueWithValidValue()
    {
        $this->assertSame(1, $this->getType()->convertToDatabaseValue(true, $this->getPlatform()));
        $this->assertSame(0, $this->getType()->convertToDatabaseValue(false, $this->getPlatform()));
    }

    public function testConvertToDatabaseValueWithNullValue()
    {
        $this->assertNull($this->getType()->convertToDatabaseValue(null, $this->getPlatform()));
    }

    public function testConvertToPHPValueWithValidValue()
    {
        $this->assertTrue($this->getType()->convertToPHPValue(1, $this->getPlatform()));
        $this->assertFalse($this->getType()->convertToPHPValue(0, $this->getPlatform()));
    }

    public function testConvertToPHPValueWithNullValue()
    {
        $this->assertNull($this->getType()->convertToPHPValue(null, $this->getPlatform()));
    }

    public function testBindingType()
    {
        $this->assertSame(\PDO::PARAM_BOOL, $this->getType()->getBindingType());
    }

    public function testName()
    {
        $this->assertSame(Type::BOOLEAN, $this->getType()->getName());
    }
}
