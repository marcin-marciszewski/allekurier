<?php

namespace App\Core\Invoice\Application\Command\CreateInvoice;

use App\Core\Invoice\Domain\Invoice;
use App\Core\Invoice\Domain\Exception\InvoiceException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;

#[AsMessageHandler]
class CreateInvoiceHandler
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(CreateInvoiceCommand $command): void
    {
        $userForInvoice = $this->userRepository->getByEmail($command->email);

        if (!$userForInvoice->isActive()) {
            throw new InvoiceException('Użytkownik dla którego prójesz wystawić fakture nie jest aktywny.');
        }

        $this->invoiceRepository->save(new Invoice(
            $userForInvoice,
            $command->amount
        ));

        $this->invoiceRepository->flush();
    }
}
