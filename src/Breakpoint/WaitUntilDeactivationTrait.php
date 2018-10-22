<?php

namespace uuf6429\BehatBreakpoint\Breakpoint;

use uuf6429\BehatBreakpoint\Exception\TimeoutException;

trait WaitUntilDeactivationTrait
{
    /**
     * @var float|int
     */
    protected $timeout = 3600.0;

    /**
     * @var float|int
     */
    protected $delay = 0.5;

    /**
     * Sets the limit until we give up waiting (0 to wait indefinitely).
     *
     * @param float|int $timeout Timeout in seconds
     *
     * @return $this
     */
    public function setTimeout($timeout)
    {
        if (!is_numeric($timeout) || $timeout < 0) {
            throw new \InvalidArgumentException('Argument must be a value larger or equal to 0.');
        }

        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Sets the delay between checking if breakpoint is still active.
     *
     * @param float|int $delay Delay in seconds
     *
     * @return $this
     */
    public function setDelay($delay)
    {
        if (!is_numeric($delay) || $delay <= 0) {
            throw new \InvalidArgumentException('Argument must be a value larger than 0.');
        }

        $this->delay = $delay;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * @throws \RuntimeException
     */
    public function trigger()
    {
        $start = microtime(true);

        $this->activate();

        while ($this->isActive()) {
            if ($this->timeout > 0 && microtime(true) - $start >= $this->timeout) {
                throw new TimeoutException();
            }
            usleep($this->delay * 1000000);
        }
    }
}
