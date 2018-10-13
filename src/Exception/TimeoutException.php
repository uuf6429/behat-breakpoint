<?php

namespace uuf6429\BehatBreakpoint\Exception;

use Throwable;

class TimeoutException extends TriggerException
{
    public function __construct($message = 'Gave up waiting for breakpoint.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
