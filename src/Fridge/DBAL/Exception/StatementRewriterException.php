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
 * Statement rewriter exception.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class StatementRewriterException extends Exception
{
    /**
     * Gets the "PARAMETER DOES NOT EXIST" exception.
     *
     * @param string $parameter The parameter.
     *
     * @return \Fridge\DBAL\Exception\StatementRewriterException The "PARAMETER DOES NOT EXIST" exception.
     */
    public static function parameterDoesNotExist($parameter)
    {
        return new self(sprintf('The parameter "%s" does not exist.', $parameter));
    }
}
