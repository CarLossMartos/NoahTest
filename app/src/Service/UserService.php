<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(
        private UserRepository         $userRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function createUser(string $username, string $email): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->find($id);

        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
    }
}
