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

use Fridge\DBAL\Debug\QueryDebugger;
use Symfony\Component\EventDispatcher\Event;

/**
 * Query debug event which wraps the query debugger.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class QueryDebugEvent extends Event
{
    /** @var \Fridge\DBAL\Debug\QueryDebugger */
    private $debugger;

    /**
     * Creates a debug query event.
     *
     * @param \Fridge\DBAL\Debug\QueryDebugger $debugger The query debugger.
     */
    public function __construct(QueryDebugger $debugger)
    {
        $this->debugger = $debugger;
    }

    /**
     * Gets the debugger.
     *
     * @return \Fridge\DBAL\Debug\QueryDebugger The query debugger.
     */
    public function getDebugger()
    {
        return $this->debugger;
    }
}
