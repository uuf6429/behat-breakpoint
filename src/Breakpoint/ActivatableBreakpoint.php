<?php

namespace uuf6429\BehatBreakpoint\Breakpoint;

interface ActivatableBreakpoint
{
    /**
     * Returns if the breakpoint is currently active or not.
     *
     * @return bool
     */
    public function isActive();

    /**
     * Activates the breakpoint.
     */
    public function activate();

    /**
     * Deactivates the breakpoint.
     */
    public function deactivate();
}
