<?php

namespace uuf6429\BehatBreakpoint\Breakpoint;

use uuf6429\BehatBreakpoint\Exception\TriggerException;

interface Breakpoint
{
    /**
     * Trigger breakpoint and wait until it finishes.
     *
     * @throws TriggerException
     */
    public function trigger();
}
