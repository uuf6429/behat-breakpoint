<?php

namespace uuf6429\BehatBreakpointTest\Unit\Breakpoint;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use uuf6429\BehatBreakpoint\Breakpoint\AlertBreakpoint;
use uuf6429\BehatBreakpoint\Exception\TimeoutException;
use WebDriver\Exception\NoAlertOpenError;
use WebDriver\Session;

class AlertBreakpointTest extends TestCase
{
    /**
     * @dataProvider triggerCallDataProvider
     *
     * @param null|string $customMessage
     * @param bool $alertStaysOpen
     * @param string[] $expectedCode
     * @param null|\Throwable $expectedException
     */
    public function testTriggerCall($customMessage, $alertStaysOpen, $expectedCode, $expectedException)
    {
        if (!class_exists(\DG\BypassFinals::class)) {
            $this->markTestSkipped('This test requires \DG\BypassFinals.');
        }

        /** @var MockObject|Session $session */
        /** @noinspection ClassMockingCorrectnessInspection */
        $session = $this->getMockBuilder(Session::class)
            ->setMethods(['getAlert_text', 'execute_async', 'dismiss_alert'])
            ->getMock();

        if (!$alertStaysOpen) {
            $session->method('getAlert_text')
                ->willThrowException(new NoAlertOpenError());
        }

        $actualCode = [];

        $session->method('execute_async')
            ->willReturnCallback(function ($code) use (&$actualCode) {
                $actualCode[] = $code;
            });

        $sut = $customMessage
            ? new AlertBreakpoint($session, $customMessage)
            : new AlertBreakpoint($session);

        $sut->setTimeout(0.1);

        if ($expectedException) {
            $this->expectException(\get_class($expectedException));
            $this->expectExceptionMessage($expectedException->getMessage());
        }

        $sut->trigger();

        $this->assertSame($expectedCode, $actualCode);
    }

    /**
     * @return array
     */
    public function triggerCallDataProvider()
    {
        return [
            'no custom message, alert not shown' => [
                '$customMessage' => null,
                '$alertStaysOpen' => false,
                '$expectedCode' => [
                    [
                        'script' => 'window.alert("Breakpoint reached! Press [OK] to continue.");',
                        'args' => [],
                    ]
                ],
                '$expectedException' => null,
            ],
            'custom message, alert not shown' => [
                '$customMessage' => 'hello world',
                '$alertStaysOpen' => false,
                '$expectedCode' => [
                    [
                        'script' => 'window.alert("hello world");',
                        'args' => [],
                    ]
                ],
                '$expectedException' => null,
            ],
            'no custom message, alert is shown' => [
                '$customMessage' => null,
                '$alertStaysOpen' => true,
                '$expectedCode' => [],
                '$expectedException' => new TimeoutException('Gave up waiting for breakpoint.'),
            ],
            'custom message, alert is shown' => [
                '$customMessage' => 'hello world',
                '$alertStaysOpen' => true,
                '$expectedCode' => [],
                '$expectedException' => new TimeoutException('Gave up waiting for breakpoint.'),
            ],
        ];
    }
}
