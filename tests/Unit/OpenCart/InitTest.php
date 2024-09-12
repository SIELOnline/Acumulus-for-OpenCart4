<?php
/**
 * @noinspection PhpStaticAsDynamicMethodCallInspection
 */

declare(strict_types=1);

namespace Siel\Acumulus\Tests\Unit\OpenCart;

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
     * 1 Access to our Container.
     * 2 OpenCart has been initialized
     * 3 Access to the database.
     */
    public function testInit(): void
    {
        // 1.
        $environmentInfo = $this->getAcumulusContainer()->getEnvironment()->get();
        // 2.
        $this->assertMatchesRegularExpression('|\d+\.\d+\.\d+\.\d+|', $environmentInfo['shopVersion']);
        // 3.
        $this->assertNotEquals(Environment::Unknown, $environmentInfo['dbVersion']);
        // 2+3.
        /** @var \Opencart\System\Engine\Registry $ocRegistry */
        global $ocRegistry;
        /** @var \Siel\Acumulus\OpenCart\Helpers\Registry $registry */
        $registry = $this->getAcumulusContainer()->getInstance('Registry', 'Helpers', [$ocRegistry]);
        $this->assertNotEmpty($registry->getOrderModel()->getOrder(1));
    }
}
