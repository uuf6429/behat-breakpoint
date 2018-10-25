<?php

namespace uuf6429\BehatBreakpoint;

class Factory
{
    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param \WebDriver\Session $session
     * @param null|string $message
     * @return Breakpoint\AlertBreakpoint
     */
    public function createAlertBreakpoint(\WebDriver\Session $session, $message = null)
    {
        return new Breakpoint\AlertBreakpoint($session, $message);
    }

    /**
     * @param null|string $message
     * @param null|resource $output
     * @param null|resource $input
     * @return Breakpoint\ConsoleBreakpoint
     */
    public function createConsoleBreakpoint($message = null, $output = null, $input = null)
    {
        return new Breakpoint\ConsoleBreakpoint($message, $output, $input);
    }

    /**
     * @param \Behat\Mink\Session $session
     * @param string $popupHtml
     * @param int $popupWidth
     * @param int $popupHeight
     * @param bool $popupIsScrollable
     * @param bool $popupIsResizeable
     * @return Breakpoint\PopupBreakpoint
     */
    public function createPopupBreakpoint(
        \Behat\Mink\Session $session,
        $popupHtml,
        $popupWidth = 500,
        $popupHeight = 300,
        $popupIsScrollable = false,
        $popupIsResizeable = false
    )
    {
        return new Breakpoint\PopupBreakpoint($session, $popupHtml, $popupWidth, $popupHeight, $popupIsScrollable, $popupIsResizeable);
    }

    /**
     * @return Breakpoint\XdebugBreakpoint
     */
    public function createXdebugBreakpoint()
    {
        return new Breakpoint\XdebugBreakpoint();
    }
}
