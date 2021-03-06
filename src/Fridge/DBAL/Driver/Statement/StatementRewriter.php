<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Driver\Statement;

use Fridge\DBAL\Exception\StatementRewriterException;

/**
 * A statement rewriter allows to deal with named placeholder.
 *
 * It rewrites named query to positional query and rewrites each named parameter
 * to its corresponding positional parameters.
 *
 * If the statement is a positional statement, the statement rewriter simply
 * returns the statement and parameters like they are given.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class StatementRewriter
{
    /** @var string */
    private $statement;

    /** @var array */
    private $parameters = array();

    /**
     * Statement rewriter constructor.
     *
     * @param string $statement The statement to rewrite.
     */
    public function __construct($statement)
    {
        $this->statement = $statement;
        $this->rewrite();
    }

    /**
     * Gets the rewrited statement.
     *
     * @return string The rewrited statement.
     */
    public function getRewritedStatement()
    {
        return $this->statement;
    }

    /**
     * Gets the rewrited positional statement parameters according to the named parameter.
     *
     * The metod returns an array because a named parameter can be used multiple times in the statement.
     *
     * @param string $parameter The named parameter.
     *
     * @throws \Fridge\DBAL\Exception\StatementRewriterException If the parameter does not exist.
     *
     * @return array The rewrited positional parameters.
     */
    public function getRewritedParameters($parameter)
    {
        if (is_int($parameter)) {
            return array($parameter);
        }

        if (!isset($this->parameters[$parameter])) {
            throw StatementRewriterException::parameterDoesNotExist($parameter);
        }

        return $this->parameters[$parameter];
    }

    /**
     * Rewrite the named statement and parameters to positional.
     *
     * Example:
     *  - before:
     *    - statement: SELECT * FROM foo WHERE bar = :bar
     *    - parameters: array()
     *  - after:
     *    - statement: SELECT * FROM foo WHERE bar = ?
     *    - parameters: array(':bar' => array(1))
     */
    private function rewrite()
    {
        // Current positional parameter.
        $positionalParameter = 1;

        // TRUE if we are in a literal section else FALSE.
        $literal = false;

        // The statement length.
        $statementLength = strlen($this->statement);

        // Iterate each statement char.
        for ($placeholderPos = 0; $placeholderPos < $statementLength; $placeholderPos++) {

            // Switch the literal flag if the current statement char is a literal delimiter.
            if (in_array($this->statement[$placeholderPos], array('\'', '"'))) {
                $literal = !$literal;
            }

            // Check if we are not in a literal section and the current statement char is a double colon.
            if (!$literal && $this->statement[$placeholderPos] === ':') {

                // Determine placeholder length.
                $placeholderLength = 1;
                while (isset($this->statement[$placeholderPos + $placeholderLength])
                    && $this->isValidPlaceholderCharacter($this->statement[$placeholderPos + $placeholderLength])) {
                    $placeholderLength++;
                }

                // Extract placeholder from the statement.
                $placeholder = substr($this->statement, $placeholderPos, $placeholderLength);

                // Initialize rewrites parameters.
                if (!isset($this->parameters[$placeholder])) {
                    $this->parameters[$placeholder] = array();
                }

                // Rewrites parameter.
                $this->parameters[$placeholder][] = $positionalParameter;

                // Rewrite statement.
                $this->statement = substr($this->statement, 0, $placeholderPos).
                    '?'.
                    substr($this->statement, $placeholderPos + $placeholderLength);

                // Decrement statement length.
                $statementLength = $statementLength - $placeholderLength + 1;

                // Increment position parameter.
                $positionalParameter++;
            }
        }
    }

    /**
     * Checks if the character is a valid placeholder character.
     *
     * @param string $character The character to check.
     *
     * @return boolean TRUE if the character is a valid placeholder character else FALSE.
     */
    private function isValidPlaceholderCharacter($character)
    {
        $asciiCode = ord($character);

        return (($asciiCode >= 48) && ($asciiCode <= 57))
            || (($asciiCode >= 65) && ($asciiCode <= 90))
            || (($asciiCode >= 97) && ($asciiCode <= 122));
    }
}
