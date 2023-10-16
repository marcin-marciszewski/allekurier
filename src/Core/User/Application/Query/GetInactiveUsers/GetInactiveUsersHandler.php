<?php

namespace App\Core\User\Application\Query\GetInactiveUsers;

use App\Core\User\Domain\User;
use App\Core\User\Application\DTO\UserDTO;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Application\Query\GetInactiveUsers\GetInactiveUsersQuery;

#[AsMessageHandler]
class GetInactiveUsersHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(GetInactiveUsersQuery $query): array
    {
        $users = $this->userRepository->getInactiveUsers();

        return array_map(function (User $user) {
            return new UserDTO(
                $user->getId(),
                $user->getEmail(),
                $user->isActive()
            );
        }, $users);
    }
}
