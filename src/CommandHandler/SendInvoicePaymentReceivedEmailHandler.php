<?php

declare(strict_types=1);

namespace Sylius\InvoicingPlugin\CommandHandler;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\InvoicingPlugin\Command\SendInvoicePaymentReceivedEmail;
use Sylius\InvoicingPlugin\Email\InvoicePaymentReceivedEmailSenderInterface;
use Sylius\InvoicingPlugin\Entity\InvoiceInterface;
use Sylius\InvoicingPlugin\Repository\InvoiceRepository;

final class SendInvoicePaymentReceivedEmailHandler
{
    /** @var InvoiceRepository */
    private $invoiceRepository;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var InvoiceEmailSenderInterface */
    private $emailSender;

    public function __construct(
        InvoiceRepository $invoiceRepository,
        OrderRepositoryInterface $orderRepository,
        InvoicePaymentReceivedEmailSenderInterface $emailSender
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->orderRepository = $orderRepository;
        $this->emailSender = $emailSender;
    }

    public function __invoke(SendInvoiceEmail $command): void
    {
        /** @var InvoiceInterface|null $invoice */
        $invoice = $this->invoiceRepository->findOneByOrderNumber($command->orderNumber());

        if (null === $invoice) {
            return;
        }

        /** @var OrderInterface $order */
        $order = $this->orderRepository->findOneByNumber($command->orderNumber());

        if (null === $order->getCustomer()) {
            return;
        }

        $this->emailSender->sendInvoicePayed($invoice, $order->getCustomer()->getEmail());
    }
}
