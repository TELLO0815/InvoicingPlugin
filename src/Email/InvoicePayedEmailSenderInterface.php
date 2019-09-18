<?php

declare(strict_types=1);

namespace Sylius\InvoicingPlugin\Email;

use Sylius\InvoicingPlugin\Entity\InvoiceInterface;

interface InvoicePayedEmailSenderInterface
{
    public function sendInvoicePayedEmail(InvoiceInterface $invoice, string $customerEmail): void;
}
