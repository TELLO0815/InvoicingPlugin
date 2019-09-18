<?php

declare(strict_types=1);

namespace Sylius\InvoicingPlugin\Email;

use Sylius\InvoicingPlugin\Entity\InvoiceInterface;

interface InvoicePaymentReceivedEmailSenderInterface
{
    public function sendInvoiceinvoicePaymentReceivedEmail(InvoiceInterface $invoice, string $customerEmail): void;
}
