<?php

namespace uuf6429\BehatBreakpoint\Breakpoint;

use uuf6429\BehatBreakpoint\Exception\TriggerException;

/**
 * Pauses execution until a connected xdebug client resumes execution.
 */
class XdebugBreakpoint implements Breakpoint
{
    public function trigger()
    {
        if (!$this->isXdebugEnabled()) {
            throw new TriggerException('Xdebug breakpoint function not available. Is Xdebug installed and enabled?');
        }

        $this->triggerBreakpoint();
    }

    /**
     * @return bool
     */
    protected function isXdebugEnabled()
    {
        return function_exists('xdebug_break');
    }

    protected function triggerBreakpoint()
    {
        xdebug_break();
    }
}
