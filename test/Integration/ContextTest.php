<?php

namespace uuf6429\BehatBreakpointTest\Integration;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class ContextTest extends TestCase
{
    public function testBehatFeaturesRun()
    {
        $process = new Process(['php', __DIR__ . '/../../vendor/behat/behat/bin/behat'], __DIR__, null, null, null);

        $this->assertSame(0, $process->mustRun()->getExitCode());
    }
}
