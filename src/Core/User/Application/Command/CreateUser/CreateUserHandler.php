<?php

namespace App\Core\User\Application\Command\CreateUser;

use App\Core\User\Domain\User;
use App\Core\User\Domain\Exception\UserException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Core\User\Domain\Repository\UserRepositoryInterface;

#[AsMessageHandler]
class CreateUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {

        if (!filter_var($command->email, FILTER_VALIDATE_EMAIL)) {
            throw new UserException('Proszę wprowadzić prawidłowy adres email.');
        }

        $this->userRepository->save(new User(
            $command->email
        ));

        $this->userRepository->flush();
    }
}
