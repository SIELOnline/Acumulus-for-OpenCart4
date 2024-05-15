<?php
/**
 * @noinspection PhpStaticAsDynamicMethodCallInspection
 */

declare(strict_types=1);

namespace Siel\Acumulus\Tests\OpenCart\Unit;

use Siel\Acumulus\Config\Environment;
use Siel\Acumulus\Tests\OpenCart\OpenCartTest;

/**
 * Tests that WooCommerce and Acumulus have been initialized.
 */
class InitTest extends OpenCartTest
{
    /**
     * A single test to see if the test framework (including the plugins) has been
     * initialized correctly:
     * 1 We have access to the Container.
     * 2 OpenCart and the database have been initialized.
     */
    public function testInit(): void
    {
        // 1.
        $environmentInfo = $this->getAcumulusContainer()->getEnvironment()->get();
        // 2.
        $this->assertMatchesRegularExpression('|\d+\.\d+\.\d+\.\d+|', $environmentInfo['shopVersion']);
        $this->assertNotEquals(Environment::Unknown, $environmentInfo['dbVersion']);
    }
}
