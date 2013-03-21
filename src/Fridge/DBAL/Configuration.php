<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Describes the connection configuration. It wraps a powerfull logger (Monolog)
 * and an event dispatcher (Symfony2 EventDispatcher component).
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class Configuration
{
    /** @var boolean */
    protected $debug;

    /** @var \Symfony\Component\EventDispatcher\EventDispatcher */
    protected $eventDispatcher;

    /**
     * Creates a configuration.
     *
     * @param boolean                                            $debug           The debug flag.
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher The event dispatcher.
     */
    public function __construct($debug = false, EventDispatcher $eventDispatcher = null)
    {
        if ($eventDispatcher === null) {
            $eventDispatcher = new EventDispatcher();
        }

        $this->setDebug($debug);
        $this->setEventDispatcher($eventDispatcher);
    }

    /**
     * Gets the configuration debug flag.
     *
     * @return boolean TRUE if the connection is debugged else FALSE.
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Sets the configuration debug flag.
     *
     * @param boolean $debug TRUE if the connection is debugged else FALSE.
     */
    public function setDebug($debug)
    {
        $this->debug = (bool) $debug;
    }

    /**
     * Gets the event dispatcher.
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcher The event dispatcher.
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * Sets the event dispatcher.
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher The event dispatcher.
     */
    public function setEventDispatcher(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}
