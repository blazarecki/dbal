<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Event;

/**
 * Describes the available events.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class Events
{
    /** @const string The post connect event */
    const POST_CONNECT = 'POST_CONNECT';

    /** @const string The query debug event */
    const QUERY_DEBUG = 'QUERY_DEBUG';

    /**
     * Disabled constructor.
     *
     * @codeCoverageIgnore
     */
    final private function __construct()
    {

    }
}
