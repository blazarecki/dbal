<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Exception;

/**
 * Query rewriter exception.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class QueryRewriterException extends Exception
{
    /**
     * Gets the "NAMED PLACEHOLDER DOES NOT EXIST" exception.
     *
     * @param string $placeholder The placeholder.
     * @param string $query       The query.
     *
     * @return \Fridge\DBAL\Exception\Query\Rewriter\QueryRewriterException The "NAMED PLACEHOLDER DOES NOT EXIST"
     *                                                                      exception.
     */
    public static function namedPlaceholderDoesNotExist($placeholder, $query)
    {
        return new self(sprintf(
            'The named placeholder "%s" does not exist in the query: "%s".',
            $placeholder,
            $query
        ));
    }

    /**
     * Gets the "POSITIONAL PLACEHOLDER DOES NOT EXIST" exception.
     *
     * @param integer $position The placeholder position.
     * @param string  $query    The query.
     *
     * @return \Fridge\DBAL\Exception\Query\Rewriter\QueryRewriterException The "POSITIONAL PLACEHOLDER DOES NOT EXIST"
     *                                                                      exception.
     */
    public static function positionalPlaceholderDoesNotExist($position, $query)
    {
        return new self(sprintf(
            'The positional placeholder (%d) does not exist in the query: "%s".',
            $position,
            $query
        ));
    }
}
