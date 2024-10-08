<?php
/**
 * @noinspection PhpStaticAsDynamicMethodCallInspection
 */

declare(strict_types=1);

namespace Siel\Acumulus\Tests\Integration\OpenCart;

use Siel\Acumulus\Invoice\Source;
use Siel\Acumulus\Tests\OpenCart\OpenCartTest;

use function dirname;

/**
 * InvoiceCreateTest tests the process of creating an {@see Invoice}.
 *
 * @todo: add tests for other fees (OtherLineCollector).
 * @todo: add a margin scheme invoice.
 */
class InvoiceCreateTest extends OpenCartTest
{
    public function InvoiceDataProvider(): array
    {
        $dataPath = dirname(__FILE__, 1) . '/Data';
        return [
            'NL consument' => [$dataPath, Source::Order, 7,],
            'BE consument, voucher' => [$dataPath, Source::Order, 8,],
            'FR consument' => [$dataPath, Source::Order, 6,],
            'FR bedrijf, EU VAT' => [$dataPath, Source::Order, 10,],
        ];
    }

    /**
     * Tests the Creation process, i.e. collecting and completing an
     * {@see \Siel\Acumulus\Data\Invoice}.
     *
     * @dataProvider InvoiceDataProvider
     * @throws \JsonException
     */
    public function testCreate(string $dataPath, string $type, int $id, array $excludeFields = []): void
    {
        $this->_testCreate($dataPath, $type, $id, $excludeFields);
    }
}
