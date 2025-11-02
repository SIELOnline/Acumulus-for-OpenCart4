<?php
/**
 * @noinspection PhpIllegalPsrClassPathInspection
 * @noinspection AutoloadingIssuesInspection
 */

declare(strict_types=1);

/**
 * Class AcumulusTestsBootstrap bootstraps the Acumulus tests.
 *
 * The main task of this class is loading and initializing the shop environment.
 */
class AcumulusTestsBootstrap
{
    protected static AcumulusTestsBootstrap $instance;

    /**
     * Setup the unit testing environment.
     */
    public function __construct()
    {
        ini_set('display_errors', 'on');
        error_reporting(E_ALL);

        // Ensure server variable is set for email functions.
        if (!isset($_SERVER['SERVER_NAME'])) {
            $_SERVER['SERVER_NAME'] = 'localhost';
        }

        // Init OpenCart.
        $this->initOpenCart();
    }

    /**
     * Returns the single class instance, creating one if not yet existing.
     */
    public static function instance(): AcumulusTestsBootstrap
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Returns the root of the webshop.
     *
     * As our code is often symlinked nto test environments, using dirname with a given
     * level will not work, so we need to try to get the root in another way, the
     * --bootstrap option might help here.
     */
    private function getRoot(): string
    {
        $root = dirname(__FILE__, 4);
        // If our plugin is symlinked, we need to redefine root. Try to
        // find it by looking at the --bootstrap option as passed to phpunit.
        global $argv;
        if (is_array($argv)) {
            $i = array_search('--bootstrap', $argv, true);
            // if we found --bootstrap, the value is in the next entry.
            if (is_int($i) && count($argv) > $i + 1) {
                $bootstrapFile = $argv[$i + 1];
            } elseif (count($argv) === 1 && str_contains($argv[0], 'extension')) {
                $bootstrapFile = $argv[0];
            }
            if (isset($bootstrapFile)) {
                $root = substr($bootstrapFile, 0, strpos($bootstrapFile, 'extension') - 1);
            }
        }
        return $root;
    }

    private function initOpenCart(): void
    {
        // Include necessary OpenCart files
        $root = $this->getRoot();
//        $configFile = $root . '/admin1/config.php';
        $contents = file_get_contents($root . '/admin1/index.php');
        preg_match("/define\('VERSION', '((\d)\.(\d)\.(\d)\.(\d))'\)/", $contents, $matches);
        [, $version, $major, $minor] = $matches;
        $frameworkFile = __DIR__ . "/framework$major$minor.php";

        // Proudly copied (and adapted here and there) from index.php.
        // Version
        define('VERSION', $version);

        // Configuration
        require_once($root . '/admin1/config.php');

        // Startup
        require_once(DIR_SYSTEM . 'startup.php');

        // Framework
        require_once($frameworkFile);

        global $ocRegistry;
        $ocRegistry = initOpenCartFramework();
    }
}

AcumulusTestsBootstrap::instance();
