<?php
/**
 * @noinspection PhpStaticAsDynamicMethodCallInspection
 */

declare(strict_types=1);

namespace Siel\Acumulus\Tests\Integration\OpenCart;

use Siel\Acumulus\Invoice\Source;
use Siel\Acumulus\Tests\OpenCart\OpenCartTest;

/**
 * InvoiceCreateTest tests the process of creating an {@see Invoice}.
 *
 * @todo: add tests for other fees (OtherLineCollector).
 * @todo: add a margin scheme invoice.
 */
class InvoiceCreateTest extends OpenCartTest
{
    public static function InvoiceDataProvider(): array
    {
        return [
            'NL consument' => [Source::Order, 7,],
            'BE consument, voucher' => [Source::Order, 8,],
            'FR consument' => [Source::Order, 6,],
            'FR bedrijf, EU VAT' => [Source::Order, 10,],
        ];
    }

    /**
     * Tests the Creation process, i.e. collecting and completing an
     * {@see \Siel\Acumulus\Data\Invoice}.
     *
     * @dataProvider InvoiceDataProvider
     * @throws \JsonException
     */
    public function testCreate(string $type, int $id, array $excludeFields = []): void
    {
        $this->_testCreate($type, $id, $excludeFields);
    }
}
