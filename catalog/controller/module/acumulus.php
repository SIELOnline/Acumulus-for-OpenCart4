<?php

declare(strict_types=1);

namespace Opencart\Catalog\Controller\Extension\PayPal\Module;

use Opencart\System\Engine\Controller;
use Opencart\System\Engine\Registry;
use Siel\Acumulus\Helpers\Container;
use Siel\Acumulus\OpenCart\Helpers\OcHelper;
use SielAcumulusAutoloader;

/**
 * This is the Acumulus controller for the catalog side.
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
     * Event handler that executes on the creation or update of an order.
     *
     * The arguments passed in depend on the version of OC (and possibly if it
     * is OC self or another plugin that triggered the event).
     *
     * Note: in admin it can only be another plugin as OC self redirects to the
     * catalog part to update an order.
     *
     * @noinspection PhpUnused
     */
    public function eventOrderUpdate(...$args): void
    {
        $order_id = $this->ocHelper->extractOrderId($args);
        $this->ocHelper->eventOrderUpdate($order_id);
    }
}
