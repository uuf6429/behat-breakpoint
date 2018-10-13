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
     * @param Session $session
     * @param string $popupHtml
     * @param int $popupWidth
     * @param int $popupHeight
     * @param bool $popupIsScrollable
     * @param bool $popupIsResizeable
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

        $this->popupName = uniqid('behatPopup_', true);

        $options = sprintf(
            '"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=%s,'
            . 'resizable=%s,width=%s,height=%s,left=" + (screen.width-%s) + ",top=" + (screen.height-%s)',
            $this->popupIsScrollable ? 'yes' : 'no',
            $this->popupIsResizeable ? 'yes' : 'no',
            $this->popupWidth,
            $this->popupHeight,
            $this->popupWidth / 2,
            $this->popupHeight / 2
        );

        // create and open popup
        $this->session->executeScript(
            sprintf(
                'window["%s"] = window.open("", "%s", %s))',
                $this->popupName,
                $this->popupName,
                $options
            )
        );

        // set popup body content
        $this->session->executeScript(
            sprintf(
                'window["%s"].document.body.innerHTML = %s',
                $this->popupName,
                json_encode($this->popupHtml)
            )
        );
    }

    public function deactivate()
    {
        if (!$this->isActive()) {
            return;
        }

        $this->session->executeScript(sprintf('window["%s"].close()', $this->popupName));
    }
}
