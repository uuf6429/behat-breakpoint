<?php

namespace uuf6429\BehatBreakpoint\Breakpoint;

use uuf6429\BehatBreakpoint\Exception\TimeoutException;

trait WaitUntilDeactivationTrait
{
    /**
     * Timeout in seconds until we give up waiting (0 to wait indefinitely).
     *
     * @var float
     */
    protected $timeout = 3600.0;

    /**
     * Delay between checking if breakpoint is still active in seconds.
     *
     * @var float
     */
    protected $delay = 0.5;

    /**
     * @throws \RuntimeException
     */
    public function trigger()
    {
        $start = microtime(true);

        while ($this->isActive()) {
            if ($this->timeout > 0 && microtime(true) - $start >= $this->timeout) {
                throw new TimeoutException();
            }
            usleep($this->delay * 1000000);
        }
    }
}
