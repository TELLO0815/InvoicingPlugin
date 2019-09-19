<?php

declare(strict_types=1);

namespace Sylius\InvoicingPlugin\EventListener;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\InvoicingPlugin\Creator\InvoiceCreatorInterface;
use Sylius\InvoicingPlugin\Email\InvoiceEmailSenderInterface;
use Sylius\InvoicingPlugin\Entity\InvoiceInterface;
use Sylius\InvoicingPlugin\Event\OrderPlaced;
use Sylius\InvoicingPlugin\Exception\InvoiceAlreadyGenerated;
use Sylius\InvoicingPlugin\Repository\InvoiceRepository;

final class CreateInvoiceOnOrderPlacedListener
{
    /** @var InvoiceCreatorInterface */
    private $invoiceCreator;

    /** @var InvoiceRepository */
    private $invoiceRepository;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var InvoiceEmailSenderInterface */
    private $invoiceEmailSender;

    public function __construct(
        InvoiceCreatorInterface $invoiceCreator,
        InvoiceRepository $invoiceRepository,
        InvoiceEmailSenderInterface $invoiceEmailSender,
        OrderRepositoryInterface $orderRepository
    )
    {
        $this->invoiceCreator = $invoiceCreator;
        $this->invoiceRepository = $invoiceRepository;
        $this->invoiceEmailSender = $invoiceEmailSender;
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(OrderPlaced $event): void
    {
        try {
            $this->invoiceCreator->__invoke($event->orderNumber(), $event->date());
        } catch (InvoiceAlreadyGenerated $exception) {
            return;
        }
        /** @var InvoiceInterface $invoice */
        $invoice = $this->invoiceRepository->findOneByOrderNumber($event->orderNumber());

        /** @var OrderInterface $order */
        $order = $this->orderRepository->findOneBy(['number' => $event->orderNumber()]);

        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();

        $this->invoiceEmailSender->sendInvoiceEmail($invoice, $customer->getEmail());

    }
}
