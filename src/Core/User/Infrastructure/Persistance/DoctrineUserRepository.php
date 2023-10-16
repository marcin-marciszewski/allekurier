<?php

namespace App\Core\User\Infrastructure\Persistance;

use App\Core\User\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use App\Core\User\Domain\Exception\UserException;
use Psr\EventDispatcher\EventDispatcherInterface;
use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getByEmail(string $email): User
    {
        $user = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :user_email')
            ->setParameter(':user_email', $email)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            throw new UserNotFoundException('Użytkownik nie istnieje');
        }

        return $user;
    }

    public function getInactiveUsers(): array
    {
        $users = $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.isActive = :is_active')
            ->setParameter(':is_active', 0)
            ->getQuery()
            ->getResult();

        if (null === $users) {
            throw new UserNotFoundException('Brak użytkowników spełniających kryteria.');
        }

        return $users;
    }

    public function save(User $user): void
    {
        if ($this->entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()])) {
            throw new UserException('Użytkownik o tym adresie email już istnieje.');
        }

        $this->entityManager->persist($user);

        $events = $user->pullEvents();
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
