<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\Tests\DBAL\Driver\Statement;

use Fridge\DBAL\Driver\Statement\StatementRewriter;

/**
 * Statement rewriter tests.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class StatementRewriterTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fridge\DBAL\Driver\Statement\StatementRewriter */
    private $statementRewriter;

    /**
     * Sets up the statement rewriter.
     *
     * @param string $statement The statement.
     */
    private function setUpStatementRewriter($statement)
    {
        $this->statementRewriter = new StatementRewriter($statement);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->statementRewriter);
    }

    public function testRewriteWithoutParameter()
    {
        $statement = 'SELECT * FROM foo';
        $this->setUpStatementRewriter($statement);

        $this->assertSame($statement, $this->statementRewriter->getRewritedStatement());
    }

    public function testRewriteWithOneParameter()
    {
        $this->setUpStatementRewriter('SELECT * FROM foo WHERE foo = :foo');

        $this->assertSame('SELECT * FROM foo WHERE foo = ?', $this->statementRewriter->getRewritedStatement());
        $this->assertSame(array(1), $this->statementRewriter->getRewritedParameters(':foo'));
    }

    public function testRewriteWithMultipleParameters()
    {
        $this->setUpStatementRewriter('SELECT * FROM foo WHERE foo = :foo AND bar = :bar');

        $this->assertSame(
            'SELECT * FROM foo WHERE foo = ? AND bar = ?',
            $this->statementRewriter->getRewritedStatement()
        );

        $this->assertSame(array(1), $this->statementRewriter->getRewritedParameters(':foo'));
        $this->assertSame(array(2), $this->statementRewriter->getRewritedParameters(':bar'));
    }

    public function testRewriteWithMultipleSameParameters()
    {
        $this->setUpStatementRewriter('SELECT * FROM foo WHERE foo = :foo AND bar = :bar AND baz = :foo');

        $this->assertSame(
            'SELECT * FROM foo WHERE foo = ? AND bar = ? AND baz = ?',
            $this->statementRewriter->getRewritedStatement()
        );

        $this->assertSame(array(1, 3), $this->statementRewriter->getRewritedParameters(':foo'));
        $this->assertSame(array(2), $this->statementRewriter->getRewritedParameters(':bar'));
    }

    public function testRewriteWithPositionalStatement()
    {
        $this->setUpStatementRewriter('SELECT * FROM foo WHERE foo = ?');

        $this->assertSame('SELECT * FROM foo WHERE foo = ?', $this->statementRewriter->getRewritedStatement());
        $this->assertSame(array(1), $this->statementRewriter->getRewritedParameters(1));
    }

    public function testRewriteWithSimpleQuoteLiteralDelimiter()
    {
        $this->setUpStatementRewriter('SELECT * FROM foo WHERE foo = :foo AND bar = \':bar\' AND baz = :baz');

        $this->assertSame(
            'SELECT * FROM foo WHERE foo = ? AND bar = \':bar\' AND baz = ?',
            $this->statementRewriter->getRewritedStatement()
        );

        $this->assertSame(array(1), $this->statementRewriter->getRewritedParameters(':foo'));
        $this->assertSame(array(2), $this->statementRewriter->getRewritedParameters(':baz'));
    }

    public function testRewriteWithDoubleQuoteLiteralDelimiter()
    {
        $this->setUpStatementRewriter('SELECT * FROM foo WHERE foo = :foo AND bar = ":bar" AND baz = :baz');

        $this->assertSame(
            'SELECT * FROM foo WHERE foo = ? AND bar = ":bar" AND baz = ?',
            $this->statementRewriter->getRewritedStatement()
        );

        $this->assertSame(array(1), $this->statementRewriter->getRewritedParameters(':foo'));
        $this->assertSame(array(2), $this->statementRewriter->getRewritedParameters(':baz'));
    }

    /**
     * @expectedException \Fridge\DBAL\Exception\StatementRewriterException
     * @expectedExceptionMessage The parameter "foo" does not exist.
     */
    public function testGetInvalidParameter()
    {
        $this->setUpStatementRewriter('SELECT * FROM foo');
        $this->statementRewriter->getRewritedParameters('foo');
    }
}
