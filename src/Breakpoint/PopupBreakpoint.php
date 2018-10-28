<?php

namespace uuf6429\BehatBreakpoint\Breakpoint;

use Behat\Mink\Session;

/**
 * Displays a new window with some HTML and waits until it is closed by the user.
 */
class PopupBreakpoint implements Breakpoint, ActivatableBreakpoint
{
    use WaitUntilDeactivationTrait;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var string
     */
    private $popupHtml;

    /**
     * @var null|string
     */
    private $popupName;

    /**
     * @var int
     */
    private $popupWidth;

    /**
     * @var int
     */
    private $popupHeight;

    /**
     * @var bool
     */
    private $popupIsScrollable;

    /**
     * @var bool
     */
    private $popupIsResizeable;

    /**
     * @param Session $session The Mink session to work with. It must support javascript.
     * @param string $popupHtml The HTML of the popup page *body*.
     * @param int $popupWidth The popup's default width.
     * @param int $popupHeight The popup's default height.
     * @param bool $popupIsScrollable Enables scrollbars (and scrolling) within the popup.
     * @param bool $popupIsResizeable Allows the popup to be resizeable.
     */
    public function __construct(Session $session, $popupHtml, $popupWidth = 500, $popupHeight = 300, $popupIsScrollable = false, $popupIsResizeable = false)
    {
        $this->session = $session;
        $this->popupHtml = $popupHtml;
        $this->popupWidth = (int)$popupWidth;
        $this->popupHeight = (int)$popupHeight;
        $this->popupIsScrollable = (bool)$popupIsScrollable;
        $this->popupIsResizeable = (bool)$popupIsResizeable;
    }

    public function isActive()
    {
        return $this->popupName && \in_array($this->popupName, $this->session->getWindowNames(), true);
    }

    public function activate()
    {
        if ($this->isActive()) {
            $this->deactivate();
        }

        $options = sprintf(
            '"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=%s,'
            . 'resizable=%s,width=%s,height=%s,left=" + ((screen.width-%s) / 2) + ",top=" + ((screen.height-%s) / 2)',
            $this->popupIsScrollable ? 'yes' : 'no',
            $this->popupIsResizeable ? 'yes' : 'no',
            $this->popupWidth,
            $this->popupHeight,
            $this->popupWidth,
            $this->popupHeight
        );

        $oldWindows = $this->session->getWindowNames();

        // create, set up and open popup
        $this->session->executeScript(
            sprintf(
                '(newWindow = window.open("", "", %s)).document.body.innerHTML = %s',
                $options,
                json_encode($this->popupHtml)
            )
        );

        // detect new popup's name
        $this->popupName = array_values(array_diff($this->session->getWindowNames(), $oldWindows))[0];

        // keep track of popup
        $this->session->executeScript("window['{$this->popupName}'] = newWindow");
    }

    public function deactivate()
    {
        if (!$this->isActive()) {
            return;
        }

        $this->session->executeScript("window['{$this->popupName}'].close()");
    }
}
