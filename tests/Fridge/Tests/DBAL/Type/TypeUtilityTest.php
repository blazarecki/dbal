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

use Fridge\DBAL\Type\Type;
use Fridge\DBAL\Type\TypeUtility;

/**
 * Type utility test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class TypeUtilityTest extends \PHPUnit_Framework_TestCase
{
    public function testConvertToDatabaseWithDBALType()
    {
        $platformMock = $this->getMock('Fridge\DBAL\Platform\PlatformInterface');

        list($value, $type) = TypeUtility::convertToDatabase(true, Type::BOOLEAN, $platformMock);

        $this->assertSame(1, $value);
        $this->assertSame(\PDO::PARAM_BOOL, $type);
    }

    public function testConvertToDatabaseWithPDOType()
    {
        $platformMock = $this->getMock('Fridge\DBAL\Platform\PlatformInterface');

        list($value, $type) = TypeUtility::convertToDatabase(true, \PDO::PARAM_BOOL, $platformMock);

        $this->assertTrue($value);
        $this->assertSame(\PDO::PARAM_BOOL, $type);
    }
}
