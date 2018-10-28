<?php

namespace uuf6429\BehatBreakpointTest\Unit\Breakpoint;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use uuf6429\BehatBreakpoint\Breakpoint\XdebugBreakpoint;
use uuf6429\BehatBreakpoint\Exception\TriggerException;

class XdebugBreakpointTest extends TestCase
{
    /**
     * @dataProvider triggerCallDataProvider
     *
     * @param bool $xdebugIsEnabled
     * @param bool $xdebugIsTriggered
     * @param \Throwable|null $expectedException
     */
    public function testTriggerCall($xdebugIsEnabled, $xdebugIsTriggered, $expectedException)
    {
        /** @var MockObject|XdebugBreakpoint $sut */
        $sut = $this->getMockBuilder(XdebugBreakpoint::class)
            ->setMethods(['isXdebugEnabled', 'isDebuggerAttached', 'triggerBreakpoint'])
            ->getMock();

        $sut->method('isXdebugEnabled')
            ->willReturn($xdebugIsEnabled);

        $sut->expects($xdebugIsTriggered ? $this->once() : $this->never())
            ->method('triggerBreakpoint');

        if ($expectedException) {
            $this->expectException(\get_class($expectedException));
            $this->expectExceptionMessage($expectedException->getMessage());
        }

        $sut->trigger();
    }

    /**
     * @return array
     */
    public function triggerCallDataProvider()
    {
        return [
            'without xdebug' => [
                '$xdebugIsEnabled' => false,
                '$xdebugIsTriggered' => false,
                '$expectedException' => new TriggerException(
                    'Xdebug breakpoint function not available. Is Xdebug installed and enabled?'
                ),
            ],
            'with debugger attached' => [
                '$xdebugIsEnabled' => true,
                '$xdebugIsTriggered' => true,
                '$expectedException' => null,
            ],
        ];
    }
}
