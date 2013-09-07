<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Platform\Mock;

use Fridge\DBAL\Platform\AbstractPlatform;
use Fridge\DBAL\Schema\Column;
use Fridge\DBAL\Schema\Diff\ColumnDiff;
use Fridge\DBAL\Type\Type;

/**
 * Unsupported inline column comment platform test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedInlineColumnCommentPlatformTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Platform\PlatformInterface */
    private $platform;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->platform = new UnsupportedInlineColumnCommentPlatformMock();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->platform);
    }

    public function testAlterColumnSQLQueries()
    {
        $columnDiff = new ColumnDiff(
            new Column('foo', Type::getType(Type::INTEGER)),
            new Column('bar', Type::getType(Type::INTEGER), array('comment' => 'foo')),
            array()
        );

        $this->assertSame(
            array(
                'ALTER TABLE foo ALTER COLUMN foo bar INT',
                'COMMENT ON COLUMN foo.bar IS \'foo\'',
            ),
            $this->platform->getAlterColumnSQLQueries($columnDiff, 'foo')
        );
    }
}

/**
 * Unsupported inline column comment platform mock.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class UnsupportedInlineColumnCommentPlatformMock extends AbstractPlatform
{
    /**
     * {@inheritdoc}
     */
    public function supportInlineColumnComments()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeMappedTypes()
    {

    }
}
