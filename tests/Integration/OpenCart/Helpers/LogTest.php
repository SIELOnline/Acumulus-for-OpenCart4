<?php

declare(strict_types=1);

namespace Siel\Acumulus\Tests\Integration\OpenCart\Helpers;

use Siel\Acumulus\OpenCart\OpenCart4\Helpers\Log;
use Siel\Acumulus\Tests\OpenCart\TestCase;

use function sprintf;

/**
 * LogTest tests whether the log class logs messages to a log file.
 *
 * This test is mainly used to test if the log feature still works in new versions of the
 * shop.
 */
class LogTest extends TestCase
{
    private function getLogFolder(): string
    {
        return DIR_LOGS;
    }

    protected function getLogPath(): string
    {
        return $this->getLogFolder() . Log::Filename;
    }

    public function testLog(): void
    {
        $this->_testLog();
    }
}
