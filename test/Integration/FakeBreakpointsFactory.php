<?php

namespace uuf6429\BehatBreakpointTest\Integration;

use uuf6429\BehatBreakpoint\Factory;

class FakeBreakpointsFactory extends Factory
{
    public function createAlertBreakpoint(\WebDriver\Session $session, $message = null)
    {
        return new FakeBreakpoint();
    }

    public function createConsoleBreakpoint($message = null, $output = null, $input = null)
    {
        return new FakeBreakpoint();
    }

    public function createPopupBreakpoint(
        \Behat\Mink\Session $session,
        $popupHtml,
        $popupWidth = 500,
        $popupHeight = 300,
        $popupIsScrollable = false,
        $popupIsResizeable = false
    )
    {
        return new FakeBreakpoint();
    }

    public function createXdebugBreakpoint()
    {
        return new FakeBreakpoint();
    }
}
