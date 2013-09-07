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
 * Debug query event which wraps the query debugger.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class DebugQueryEvent extends Event
{
    /** @var \Fridge\DBAL\Debug\QueryDebugger */
    private $queryDebugger;

    /**
     * Creates a debug query event.
     *
     * @param \Fridge\DBAL\Debug\QueryDebugger $queryDebugger The query debugger.
     */
    public function __construct(QueryDebugger $queryDebugger)
    {
        $this->queryDebugger = $queryDebugger;
    }

    /**
     * Gets the debugger.
     *
     * @return \Fridge\DBAL\Debug\QueryDebugger The query debugger.
     */
    public function getQueryDebugger()
    {
        return $this->queryDebugger;
    }
}
