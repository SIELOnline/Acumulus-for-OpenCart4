<?php
/**
 * @noinspection PhpStaticAsDynamicMethodCallInspection
 */

declare(strict_types=1);

namespace Siel\Acumulus\Tests\OpenCart;

use OpenCart;
use PHPUnit\Framework\TestCase;

/**
 * OpenCartTest is a base class for OpenCart Acumulus integration tests.
 */
class OpenCartTest extends TestCase
{
    use AcumulusTestUtils;
}
