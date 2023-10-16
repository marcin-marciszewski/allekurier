<?php

namespace App\Core\Invoice\UserInterface\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Core\Invoice\Domain\Exception\InvoiceException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\Invoice\Application\Command\CreateInvoice\CreateInvoiceCommand;

#[AsCommand(
    name: 'app:invoice:create',
    description: 'Dodawanie nowej faktury'
)]
class CreateInvoice extends Command
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly UserRepositoryInterface $userRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->bus->dispatch(new CreateInvoiceCommand(
            $input->getArgument('email'),
            $input->getArgument('amount')
        ));

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED);
        $this->addArgument('amount', InputArgument::REQUIRED);
    }
}
