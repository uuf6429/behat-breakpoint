<?php

namespace uuf6429\BehatBreakpoint;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Mink\Driver\Selenium2Driver;

class Context extends MinkAwareContextHandler
{
    /**
     * @var Factory
     */
    protected $breakpointFactory;

    /**
     * @param null|Factory $factory
     */
    public function __construct(Factory $factory = null)
    {
        $this->breakpointFactory = $factory ?: new Factory();
    }

    /**
     * Shows a javascript alert in the currently open page and pauses execution until it is closed.
     *
     * @example Then a console breakpoint is triggered with message "Breakpoint reached! Press [OK] to continue..."
     *
     * @Given /^an alert breakpoint is triggered(?: with message "(?P<message>[^"]*)")?$/
     *
     * @param null|string $message
     */
    public function anAlertBreakpointIsTriggered($message = null)
    {
        $driver = $this->getMink()->getSession()->getDriver();
        if (!($driver instanceof Selenium2Driver)) {
            throw new \RuntimeException(
                sprintf('Driver must be an instance of %s, not %s.', Selenium2Driver::class, get_class($driver))
            );
        }

        $this->breakpointFactory
            ->createAlertBreakpoint($driver->getWebDriverSession(), $message)
            ->trigger();
    }

    /**
     * Shows a message in the Behat console and waits for the user to press "enter" before continuing execution.
     *
     * @example Then a console breakpoint is triggered with message "Breakpoint reached! Press [Enter] to continue..."
     *
     * @Given /^a console breakpoint is triggered(?: with message "(?P<message>[^"]*)")?$/
     *
     * @param null|string $message
     */
    public function aConsoleBreakpointIsTriggered($message = null)
    {
        $this->breakpointFactory
            ->createConsoleBreakpoint($message)
            ->trigger();
    }

    /**
     * Shows a browser popup with some HTML and pauses execution until it is closed.
     *
     * @example Then a 300x200 popup breakpoint is triggered with the following content:
     *            """
     *            <h1>Hello world</h1>
     *            """
     *
     * @Given /^a(?: (?P<width>\d+)x(?P<height>\d+))? popup breakpoint is triggered with the following content:$/
     *
     * @param null|string $width
     * @param null|string $height
     * @param null|PyStringNode $content
     */
    public function aPopupBreakpointIsTriggered($width = null, $height = null, PyStringNode $content = null)
    {
        $this->breakpointFactory
            ->createPopupBreakpoint(
                $this->getMink()->getSession(),
                $content ? $content->getRaw() : '',
                $width !== null ? (int)$width : null,
                $height !== null ? (int)$height : null
            )
            ->trigger();
    }

    /**
     * Causes any connected xdebug session to break into a debugging session, pausing execution.
     *
     * @example Then an xdebug breakpoint is triggered
     *
     * @Given /^an xdebug breakpoint is triggered$/
     */
    public function anXdebugBreakpointIsTriggered()
    {
        $this->breakpointFactory
            ->createXdebugBreakpoint()
            ->trigger();
    }
}
