<?php

declare(strict_types=1);

namespace Opencart\Admin\Controller\Extension\Acumulus\Module;

use Opencart\System\Engine\Controller;
use Opencart\System\Engine\Registry;
use Siel\Acumulus\Helpers\Container;
use Siel\Acumulus\OpenCart\OpenCart4\Helpers\OcHelper;
use SielAcumulusAutoloader;

/**
 * This is the Acumulus admin side controller.
 *
 * @property \Opencart\System\Library\Cart\User $user;
 */
class Acumulus extends Controller
{
    private static OcHelper $staticOcHelper;
    private OcHelper $ocHelper;

    /**
     * Constructor.
     *
     * @param \Opencart\System\Engine\Registry $registry
     */
    public function __construct(Registry $registry)
    {
        /** @noinspection DuplicatedCode */
        parent::__construct($registry);
        if (!isset($this->ocHelper)) {
            if (!isset(static::$staticOcHelper)) {
                // Load autoloader, container and then our helper that contains
                // OC3 and OC4 shared code.
                require_once(DIR_EXTENSION . 'acumulus/system/library/siel/acumulus/SielAcumulusAutoloader.php');
                SielAcumulusAutoloader::register();
                // Language will be set by the helper.
                $container = new Container('OpenCart\OpenCart4');
                /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
                static::$staticOcHelper = $container->getInstance('OcHelper', 'Helpers', [$this->registry, $container]);
            }
            $this->ocHelper = static::$staticOcHelper;
        }
    }

    /**
     * Returns the location of the extension's files.
     *
     * @return string
     *   The location of the extension's files.
     */
    protected function getLocation(): string
    {
        return 'extension/acumulus';
    }

    /**
     * Install controller action, called when the module is installed.
     *
     * @throws \Exception
     */
    public function install(): void
    {
        $this->ocHelper->install();
    }

    /**
     * Uninstall function, called when the module is uninstalled by an admin.
     *
     * @throws \Exception
     */
    public function uninstall(): void
    {
        $this->ocHelper->uninstall();
    }

    /**
     * Main controller action: show/process the basic settings form.
     *
     * @throws \Throwable
     */
    public function index(): void
    {
        $this->ocHelper->config();
    }

    /**
     * Controller action: show/process the advanced settings form.
     *
     * @throws \Throwable
     */
    public function advanced(): void
    {
        $this->ocHelper->advancedConfig();
    }

    /**
     * Controller action: show/process the batch form.
     *
     * @throws \Throwable
     */
    public function batch(): void
    {
        $this->ocHelper->batch();
    }

    /**
     * Controller action: show/process the "Activate pro-support" form.
     *
     * @throws \Throwable
     */
    public function activate(): void
    {
        $this->ocHelper->activate();
    }

    /**
     * Controller action: show/process the register form.
     *
     * @throws \Throwable
     */
    public function register(): void
    {
        $this->ocHelper->register();
    }

    /**
     * Controller action: show/process the invoice status overview form.
     *
     * @throws \Throwable
     */
    public function invoice(): void
    {
        $this->ocHelper->invoice();
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
        $order_id = $this->ocHelper->extractOrderId($args);
        $this->ocHelper->eventOrderUpdate($order_id);
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
    public function eventViewColumnLeft(/** @noinspection PhpUnusedParameterInspection */string $route, array &$data): void
    {
        if ($this->user->hasPermission('access', $this->getLocation())) {
            $this->ocHelper->eventViewColumnLeft($data['menus']);
        }
    }

    /**
     * Adds our menu-items to the admin menu.
     *
     * param string $route
     *   The current route (common/column_left).
     * param array $data
     *   The data as will be passed to the view.
     * param string $code
     *
     * @noinspection PhpUnused : event handler
     */
    public function eventControllerSaleOrderInfo(): void
    {
        if ($this->user->hasPermission('access', $this->getLocation())) {
            $this->ocHelper->eventControllerSaleOrderInfo();
        }
    }

    /**
     * Adds our menu-items to the admin menu.
     *
     * @param string $route
     *   The current route (common/column_left).
     * @param array $data
     *   The data as will be passed to the view.
     * @param string $code
     *
     * @throws \Throwable
     *
     * @noinspection PhpUnused : event handler
     */
    public function eventViewSaleOrderInfo(
        /** @noinspection PhpUnusedParameterInspection */ string $route,
        array &$data,
        /** @noinspection PhpUnusedParameterInspection */ string &$code
    ): void {
        if ($this->user->hasPermission('access', $this->getLocation())) {
            $this->ocHelper->eventViewSaleOrderInfo($data['order_id'], $data['tabs']);
        }
    }
}
