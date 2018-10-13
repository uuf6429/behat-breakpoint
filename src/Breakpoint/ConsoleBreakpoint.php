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
    private $message;

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
     * @param null|bool|resource $output Output handle (defaults to PHP's STDOUT)
     * @param null|bool|resource $input Input handle (defaults to PHP's STDIN)
     */
    public function __construct($message = 'Breakpoint reached! Press [Enter] to continue.', $output = null, $input = null)
    {
        if (!$output && defined('STDOUT') && is_resource(STDOUT)) {
            $output = STDOUT;
        }

        if (!$input && defined('STDIN') && is_resource(STDIN)) {
            $input = STDIN;
        }

        if ($output) {
            throw new TriggerException('Output handle was not specified and PHP is not attached to a console.');
        }

        if ($output) {
            throw new TriggerException('Input handle was not specified and PHP is not attached to a console.');
        }

        $this->message = $message;
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
}
