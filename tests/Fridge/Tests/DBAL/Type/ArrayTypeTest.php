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

use Fridge\DBAL\Type\ArrayType;
use Fridge\DBAL\Type\Type;

/**
 * Array type test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class ArrayTypeTest extends AbstractTypeTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUpType()
    {
        return new ArrayType();
    }

    public function testSQLDeclaration()
    {
        $this->getPlatform()
            ->expects($this->once())
            ->method('getClobSQLDeclaration');

        $this->getType()->getSQLDeclaration($this->getPlatform());
    }

    public function testConvertToDatabaseValueWithValidValue()
    {
        $this->assertSame(
            'a:1:{s:3:"foo";s:3:"bar";}',
            $this->getType()->convertToDatabaseValue(array('foo' => 'bar'), $this->getPlatform())
        );
    }

    public function testConvertToDatabaseValueWithNullValue()
    {
        $this->assertNull($this->getType()->convertToDatabaseValue(null, $this->getPlatform()));
    }

    public function testConvertToPHPValueWithValidValue()
    {
        $this->assertSame(
            array('foo' => 'bar'),
            $this->getType()->convertToPHPValue('a:1:{s:3:"foo";s:3:"bar";}', $this->getPlatform())
        );
    }

    /**
     * @expectedException Fridge\DBAL\Exception\TypeException
     * @expectedExceptionMessage The value "foo" can not be converted to the type "array".
     */
    public function testConvertToPHPValueWithInvalidValue()
    {
        error_reporting((E_ALL | E_STRICT) - E_NOTICE);

        $this->getType()->convertToPHPValue('foo', $this->getPlatform());

        error_reporting(-1);
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
        $this->assertSame(Type::TARRAY, $this->getType()->getName());
    }
}
