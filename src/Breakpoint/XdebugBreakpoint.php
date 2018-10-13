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
        static $breaker = 'xdebug_break';

        if (!function_exists($breaker)) {
            throw new TriggerException('Xdebug breakpoint function not available. Is Xdebug installed and enabled?');
        }

        if (function_exists('xdebug_is_debugger_active') && !xdebug_is_debugger_active()) {
            throw new TriggerException('Xdebug is not connected to any debuggers. Is your IDE/client accepting connections?');
        }

        $breaker();
    }
}
