<?php

namespace uuf6429\BehatBreakpoint\Breakpoint;

use WebDriver\Exception\NoAlertOpenError;
use WebDriver\Session;

/**
 * Shows a javascript alert in the specified browser session and waits until it is closed.
 */
class AlertBreakpoint implements Breakpoint, ActivatableBreakpoint
{
    use WaitUntilDeactivationTrait;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var string
     */
    private $message;

    /**
     * @param Session $session The WebDriver session to work with.
     * @param string $message A message to show to the operator.
     */
    public function __construct($session, $message = 'Breakpoint reached! Press [OK] to continue.')
    {
        $this->session = $session;
        $this->message = $message;
    }

    public function isActive()
    {
        try {
            $this->session->getAlert_text();
            return true;
        } catch (NoAlertOpenError $e) {
            return false;
        }
    }

    public function activate()
    {
        if ($this->isActive()) {
            $this->deactivate();
        }

        $this->session->execute_async(sprintf('window.alert(%s);', json_encode($this->message)));
    }

    public function deactivate()
    {
        if (!$this->isActive()) {
            return;
        }

        $this->session->dismiss_alert();
    }
}
