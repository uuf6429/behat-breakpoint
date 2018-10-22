<?php

namespace uuf6429\BehatBreakpointTest\Unit\Breakpoint;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use uuf6429\BehatBreakpoint\Breakpoint\ConsoleBreakpoint;
use uuf6429\BehatBreakpoint\Exception\TriggerException;

class ConsoleBreakpointTest extends TestCase
{
    /**
     * @dataProvider triggerCallDataProvider
     *
     * @param null|string $customMessage
     * @param bool $useCustomOutput
     * @param bool $useCustomInput
     * @param null|string $expectedOutput
     * @param null|\Throwable $expectedException
     */
    public function testTriggerCall($customMessage, $useCustomOutput, $useCustomInput, $expectedOutput, $expectedException)
    {
        $customIo = fopen('php://temp', 'w+b');

        try {
            if ($expectedException) {
                $this->expectException(\get_class($expectedException));
                $this->expectExceptionMessage($expectedException->getMessage());
            }

            /** @var MockObject|ConsoleBreakpoint $sut */
            $sut = $this->getMockBuilder(ConsoleBreakpoint::class)
                ->setConstructorArgs([
                    $customMessage,
                    $useCustomOutput ? $customIo : null,
                    $useCustomInput ? $customIo : null
                ])
                ->setMethods(['getDefaultStdout', 'getDefaultStdin'])
                ->getMock();

            $sut->trigger();

            rewind($customIo);
            $actualOutput = stream_get_contents($customIo);

            $this->assertSame($expectedOutput, $actualOutput);
        } finally {
            fclose($customIo);
        }
    }

    /**
     * @return array
     */
    public function triggerCallDataProvider()
    {
        return [
            'defaults, no io' => [
                '$customMessage' => null,
                '$useCustomOutput' => false,
                '$useCustomInput' => false,
                '$expectedOutput' => '',
                '$expectedException' => new TriggerException(
                    'Output handle was not specified and PHP is not attached to a console.'
                ),
            ],
            'default message, custom output' => [
                '$customMessage' => null,
                '$useCustomOutput' => true,
                '$useCustomInput' => false,
                '$expectedOutput' => '',
                '$expectedException' => new TriggerException(
                    'Input handle was not specified and PHP is not attached to a console.'
                ),
            ],
            'default message, custom input' => [
                '$customMessage' => null,
                '$useCustomOutput' => false,
                '$useCustomInput' => true,
                '$expectedOutput' => '',
                '$expectedException' => new TriggerException(
                    'Output handle was not specified and PHP is not attached to a console.'
                ),
            ],
            'default message, custom io' => [
                '$customMessage' => null,
                '$useCustomOutput' => true,
                '$useCustomInput' => true,
                '$expectedOutput' => 'Breakpoint reached! Press [Enter] to continue.',
                '$expectedException' => null,
            ],
            'custom message, custom io' => [
                '$customMessage' => 'Break.',
                '$useCustomOutput' => true,
                '$useCustomInput' => true,
                '$expectedOutput' => 'Break.',
                '$expectedException' => null,
            ],
        ];
    }
}
