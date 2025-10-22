<?php

declare(strict_types=1);

namespace Siel\Acumulus\Tests\OpenCart;

use Siel\Acumulus\Helpers\Container;
use Siel\Acumulus\OpenCart\Helpers\OcHelper;
use Siel\Acumulus\Tests\AcumulusTestUtils as BaseAcumulusTestUtils;

use function dirname;

/**
 * AcumulusTestUtils contains Joomla specific test functionalities.
 */
trait AcumulusTestUtils
{
    use BaseAcumulusTestUtils {
        copyLatestTestSources as protected parentCopyLatestTestSources;
    }

    private static OcHelper $ocHelper;

    protected static function createContainer(): Container
    {
        /** @var \Opencart\System\Engine\Registry $ocRegistry */
        global $ocRegistry;
        // Load autoloader
        require_once dirname(__FILE__, 3) . '/vendor/autoload.php';

        // Load our Container, language will be set by the helper.
        $container = new Container('OpenCart\OpenCart4');
        // Load our OcHelper that contains OC3 and OC4 shared code.
        static::$ocHelper = $container->getInstance('OcHelper', 'Helpers', [$ocRegistry, $container]);
        return $container;
    }

    protected function getTestsPath(): string
    {
        return dirname(__FILE__, 2);
    }

    protected static function getOcHelper(): OcHelper
    {
        if (!isset(static::$ocHelper)) {
            self::getContainer();
        }
        return static::$ocHelper;
    }

    public function copyLatestTestSources(): void
    {
        static $hasRun = false;

        if (!$hasRun) {
            $hasRun = true;
            require_once dirname(__FILE__, 2) . '/bootstrap-acumulus.php';
        }
        $this->parentCopyLatestTestSources();
    }
}
