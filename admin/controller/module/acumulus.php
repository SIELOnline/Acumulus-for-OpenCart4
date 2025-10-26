<?php
/**
 * @noinspection PhpIllegalPsrClassPathInspection
 */

declare(strict_types=1);

namespace Opencart\Admin\Controller\Extension\Acumulus\Module;

use Opencart\System\Engine\Controller;
use Opencart\System\Engine\Registry;
use Siel\Acumulus\Helpers\Container;
use Siel\Acumulus\OpenCart\OpenCart4\Helpers\OcHelper;

use function dirname;

/**
 * This is the Acumulus admin side controller.
 *
 * @property \Opencart\System\Library\Cart\User $user;
 */
class Acumulus extends Controller
{
    private static OcHelper $ocHelper;
    private static Container $acumulusContainer;

    /**
     * Constructor.
     *
     * @param \Opencart\System\Engine\Registry $registry
     */
    public function __construct(Registry $registry)
    {
        parent::__construct($registry);
        if (!isset(static::$ocHelper)) {
            // Load autoloader.
            require_once dirname(__FILE__, 4) . '/vendor/autoload.php';

            // Load our Container, language will be set by the helper.
            static::$acumulusContainer = new Container('OpenCart\OpenCart4');
            // Load our OcHelper that contains OC3 and OC4 shared code.
            /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
            static::$ocHelper = static::$acumulusContainer->getInstance(
                'OcHelper',
                'Helpers',
                [$this->registry, static::$acumulusContainer]
            );
        }
    }

    /**
     * Returns our Container.
     *
     * To be used by other modules, like acumulus_customise_invoice.
     */
    public static function getAcumulusContainer(): Container
    {
        return self::$acumulusContainer;
    }

    /**
     * Returns our OcHelper.
     *
     * To be used by other modules, like acumulus_customise_invoice.
     */
    public static function getOcHelper(): OcHelper
    {
        return self::$ocHelper;
    }

    /**
     * Returns the location of the extension's files.
     *
     * @return string
     *   The location of the extension's files.
     */
    protected function getRoute(): string
    {
        return \Siel\Acumulus\OpenCart\Helpers\Registry::getInstance()->getRoute('');
    }

    /**
     * Install controller action, called when the module is installed.
     *
     * @throws \Exception
     */
    public function install(): void
    {
        static::$ocHelper->install();
    }

    /**
     * Uninstall function, called when the module is uninstalled by an admin.
     *
     * @throws \Exception
     */
    public function uninstall(): void
    {
        static::$ocHelper->uninstall();
    }

    /**
     * Main controller action: the settings form.
     *
     * @throws \Throwable
     */
    public function index(): void
    {
        $this->settings();
    }

    /**
     * Controller action: show/process the basic settings form.
     *
     * @throws \Throwable
     */
    public function settings(): void
    {
        static::$ocHelper->settings();
    }

    /**
     * Controller action: show/process the mappings form.
     *
     * @throws \Throwable
     */
    public function mappings(): void
    {
        static::$ocHelper->mappings();
    }

    /**
     * Controller action: show/process the batch form.
     *
     * @throws \Throwable
     */
    public function batch(): void
    {
        static::$ocHelper->batch();
    }

    /**
     * Controller action: show/process the "Activate pro-support" form.
     *
     * @throws \Throwable
     */
    public function activate(): void
    {
        static::$ocHelper->activate();
    }

    /**
     * Controller action: show/process the register form.
     *
     * @throws \Throwable
     */
    public function register(): void
    {
        static::$ocHelper->register();
    }

    /**
     * Controller action: show/process the invoice status overview form.
     *
     * @throws \Throwable
     */
    public function invoice(): void
    {
        static::$ocHelper->invoice();
    }

    /**
     * Event handler that executes on the creation or update of an order.
     *
     * The arguments passed in depend on the version of OC (and possibly if it
     * is OC self or another plugin that triggered the event).
     *
     * Note: in admin it can only be another plugin as OC self redirects to the
     * catalog part to update an order.
     *
     * @noinspection PhpUnused event handler
     */
    public function eventOrderUpdate(...$args): void
    {
        $order_id = static::$ocHelper->extractOrderId($args);
        static::$ocHelper->eventOrderUpdate($order_id);
    }

    /**
     * Adds our menu-items to the admin menu.
     *
     * @param string $route
     *   The current route (common/column_left).
     * @param array $data
     *   The data as will be passed to the view.
     *
     * @noinspection PhpUnused : event handler
     */
    public function eventViewColumnLeft(/** @noinspection PhpUnusedParameterInspection */ string $route, array &$data): void
    {
        if ($this->user->hasPermission('access', $this->getRoute())) {
            static::$ocHelper->eventViewColumnLeft($data['menus']);
        }
    }

    /**
     * Adds our stylesheet and javascript to the page header.
     *
     * Param string $route
     *   The current route (common/column_left).
     * Param array $data
     *   The data as will be passed to the view.
     * Param string $code
     *
     * @noinspection PhpUnused : event handler
     */
    public function eventControllerSaleOrderInfo(): void
    {
        if ($this->user->hasPermission('access', $this->getRoute())) {
            static::$ocHelper->eventControllerSaleOrderInfo();
        }
    }

    /**
     * Adds our status overview as a tab to the order info view.
     *
     * @param string $route
     *   The current route (common/column_left).
     * @param array $data
     *   The data as will be passed to the view: (extended) order data.
     * @param string $code
     *
     * @throws \Throwable
     *
     * @noinspection PhpUnused Event handler
     * @noinspection PhpUnusedParameterInspection
     */
    public function eventViewSaleOrderInfo(string $route, array &$data, string &$code): void
    {
        if ($this->user->hasPermission('access', $this->getRoute())) {
            static::$ocHelper->eventViewSaleOrderInfo((int) $data['order_id'], $data['tabs']);
        }
    }
}
