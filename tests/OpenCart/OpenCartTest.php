<?php
/**
 * @noinspection PhpStaticAsDynamicMethodCallInspection
 */

declare(strict_types=1);

namespace Siel\Acumulus\Tests\OpenCart;

use OpenCart;
use PHPUnit\Framework\TestCase;
use Siel\Acumulus\Helpers\Container;
use Siel\Acumulus\OpenCart\Helpers\OcHelper;
use Siel\Acumulus\Tests\AcumulusTestUtils;

use function dirname;

/**
 * OpenCartTest is a base class for OpenCart Acumulus integration tests.
 */
class OpenCartTest extends TestCase
{
    use AcumulusTestUtils;

    private static Container $container;
    private static OcHelper $ocHelper;

    protected static function getAcumulusContainer(): Container
    {
        /** @var \Opencart\System\Engine\Registry $ocRegistry */
        global $ocRegistry;
        // Load autoloader
        require_once dirname(__FILE__, 3) . '/vendor/autoload.php';

        if (!isset(static::$container)) {
            // Load our Container, language will be set by the helper.
            static::$container = new Container('OpenCart\OpenCart4');
            // Load our OcHelper that contains OC3 and OC4 shared code.
            static::$ocHelper = static::$container->getInstance('OcHelper', 'Helpers', [$ocRegistry, static::$container]);
        }
        return static::$container;
    }

    protected static function getOcHelper(): OcHelper
    {
        if (!isset(static::$ocHelper)) {
            self::getAcumulusContainer();
        }
        return static::$ocHelper;
    }
}
