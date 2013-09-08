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

use Fridge\DBAL\Type\BlobType;
use Fridge\DBAL\Type\Type;

/**
 * Blob type test.
 *
 * @author Loic Chardonnet <loic.chardonnet@gmail.com>
 */
class BlobTypeTest extends AbstractTypeTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUpType()
    {
        return new BlobType();
    }

    public function testSQLDeclaration()
    {
        $this->getPlatform()
            ->expects($this->once())
            ->method('getBlobSQLDeclaration');

        $this->getType()->getSQLDeclaration($this->getPlatform());
    }

    public function testConvertToDatabaseValueWithValidValue()
    {
        $this->assertSame('foo', $this->getType()->convertToDatabaseValue('foo', $this->getPlatform()));
    }

    public function testConvertToDatabaseValueWithNullValue()
    {
        $this->assertNull($this->getType()->convertToDatabaseValue(null, $this->getPlatform()));
    }

    public function testConvertToPHPValueWithValidValue()
    {
        $expectedValue = 'foo';
        $resource = $this->getType()->convertToPHPValue($expectedValue, $this->getPlatform());

        $this->assertTrue(is_resource($resource));
        $this->assertSame($expectedValue, fread($resource, strlen($expectedValue)));
    }

    /**
     * @expectedException Fridge\DBAL\Exception\TypeException
     * @expectedExceptionMessage The value "1" can not be converted to the type "blob".
     */
    public function testConvertToPHPValueWithInvalidValue()
    {
        $this->assertTrue(is_resource($this->getType()->convertToPHPValue(1, $this->getPlatform())));
    }

    public function testConvertToPHPValueWithNullValue()
    {
        $this->assertNull($this->getType()->convertToPHPValue(null, $this->getPlatform()));
    }

    public function testBindingType()
    {
        $this->assertSame(\PDO::PARAM_LOB, $this->getType()->getBindingType());
    }

    public function testName()
    {
        $this->assertSame(Type::BLOB, $this->getType()->getName());
    }
}
