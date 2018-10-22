<?php

namespace uuf6429\BehatBreakpoint\Breakpoint;

use uuf6429\BehatBreakpoint\Exception\TriggerException;

/**
 * Displays a message in the current terminal and waits until [enter] is pressed.
 */
class ConsoleBreakpoint implements Breakpoint
{
    /**
     * @var string
     */
    private $message = 'Breakpoint reached! Press [Enter] to continue.';

    /**
     * @var resource
     */
    private $stdout;

    /**
     * @var resource
     */
    private $stdin;

    /**
     * @param string $message A message to show to the operator.
     * @param null|resource $output Output handle (defaults to PHP's STDOUT)
     * @param null|resource $input Input handle (defaults to PHP's STDIN)
     */
    public function __construct($message = null, $output = null, $input = null)
    {
        if (!$output && !($output = $this->getDefaultStdout())) {
            throw new TriggerException('Output handle was not specified and PHP is not attached to a console.');
        }

        if (!$input && !($input = $this->getDefaultStdIn())) {
            throw new TriggerException('Input handle was not specified and PHP is not attached to a console.');
        }

        if ($message !== null) {
            $this->message = $message;
        }
        $this->stdout = $output;
        $this->stdin = $input;
    }

    public function trigger()
    {
        $term = null;
        if (DIRECTORY_SEPARATOR === '/') {
            $term = shell_exec('stty -g');
            system('stty cbreak -echo');
        }

        fwrite($this->stdout, $this->message);
        fgetc($this->stdin);

        if ($term !== null) {
            system("stty '" . $term . "'");
        }
    }

    /**
     * @return null|resource
     */
    protected function getDefaultStdout()
    {
        return (defined('STDOUT') && is_resource(STDOUT)) ? STDOUT : null;
    }

    /**
     * @return null|resource
     */
    protected function getDefaultStdIn()
    {
        return (defined('STDIN') && is_resource(STDIN)) ? STDIN : null;
    }
}
