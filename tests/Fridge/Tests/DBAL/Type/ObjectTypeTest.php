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

use Fridge\DBAL\Type\ObjectType;
use Fridge\DBAL\Type\Type;

/**
 * Object type test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class ObjectTypeTest extends AbstractTypeTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUpType()
    {
        return  new ObjectType();
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
            'O:8:"stdClass":1:{s:3:"foo";s:3:"bar";}',
            $this->getType()->convertToDatabaseValue((object) array('foo' => 'bar'), $this->getPlatform())
        );
    }

    public function testConvertToDatabaseValueWithNullValue()
    {
        $this->assertNull($this->getType()->convertToDatabaseValue(null, $this->getPlatform()));
    }

    public function testConvertToPHPValueWithValidValue()
    {
        $this->assertEquals(
            (object) array('foo' => 'bar'),
            $this->getType()->convertToPHPValue('O:8:"stdClass":1:{s:3:"foo";s:3:"bar";}', $this->getPlatform())
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
        $this->assertSame(Type::OBJECT, $this->getType()->getName());
    }
}
