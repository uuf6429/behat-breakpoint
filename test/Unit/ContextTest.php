<?php

namespace uuf6429\BehatBreakpointTest\Unit;

use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use uuf6429\BehatBreakpoint\Breakpoint\Breakpoint;
use uuf6429\BehatBreakpoint\Context;
use uuf6429\BehatBreakpoint\Factory;

class ContextTest extends TestCase
{
    public function testContextTriggersBreakpoints()
    {
        /** @var MockObject|Breakpoint $mockFactory */
        $mockBreakpoint = $this->getMockBuilder(Breakpoint::class)
            ->getMock();

        /** @var MockObject|Factory $mockFactory */
        $mockFactory = $this->getMockBuilder(Factory::class)
            ->getMock();
        $mockFactory->expects($this->once())->method('createAlertBreakpoint')->willReturn($mockBreakpoint);
        $mockFactory->expects($this->once())->method('createConsoleBreakpoint')->willReturn($mockBreakpoint);
        $mockFactory->expects($this->once())->method('createPopupBreakpoint')->willReturn($mockBreakpoint);
        $mockFactory->expects($this->once())->method('createXdebugBreakpoint')->willReturn($mockBreakpoint);

        /** @var MockObject|\WebDriver\Session $mockWdSession */
        $mockWdSession = $this->getMockBuilder(\WebDriver\Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var MockObject|Selenium2Driver $mockDriver */
        $mockDriver = $this->getMockBuilder(Selenium2Driver::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockDriver->method('getWebDriverSession')->willReturn($mockWdSession);

        /** @var MockObject|Session $mockSession */
        $mockSession = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockSession->method('getDriver')->willReturn($mockDriver);

        /** @var MockObject|Mink $mockMink */
        $mockMink = $this->getMockBuilder(Mink::class)
            ->getMock();
        $mockMink->method('getSession')->willReturn($mockSession);


        $context = new Context($mockFactory);
        $context->setMink($mockMink);

        $context->anAlertBreakpointIsTriggered();
        $context->aConsoleBreakpointIsTriggered();
        $context->aPopupBreakpointIsTriggered();
        $context->anXdebugBreakpointIsTriggered();
    }
}
