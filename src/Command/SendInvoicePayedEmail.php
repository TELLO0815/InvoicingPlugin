<?php

declare(strict_types=1);

namespace Sylius\InvoicingPlugin\Command;

final class SendInvoicePayedEmail
{
    /** @var string */
    private $orderNumber;

    public function __construct(string $orderNumber)
    {
        $this->orderNumber = $orderNumber;
    }

    public function orderNumber(): string
    {
        return $this->orderNumber;
    }
}
