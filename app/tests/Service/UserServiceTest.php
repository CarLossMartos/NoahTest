<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private $userRepository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->userService = new UserService(
            $this->userRepository,
            $this->entityManager
        );
    }

    public function testGetUserById(): void
    {
        $user = new User();
        $user->setUsername('TestUser');
        $user->setEmail('test@example.com');

        $this->userRepository->method('find')->willReturn($user);

        $result = $this->userService->getUserById(1);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('TestUser', $result->getUsername());
    }

    public function testGetAllUsers(): void
    {
        $user1 = new User();
        $user1->setUsername('User1');
        $user1->setEmail('user1@example.com');

        $user2 = new User();
        $user2->setUsername('User2');
        $user2->setEmail('user2@example.com');

        $this->userRepository->method('findAll')->willReturn([$user1, $user2]);

        $result = $this->userService->getAllUsers();

        $this->assertCount(2, $result);
    }

    public function testCreateUser(): void
    {
        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');

        $result = $this->userService->createUser('NewUser', 'new@example.com');

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('NewUser', $result->getUsername());
    }

    public function testDeleteUser(): void
    {
        $user = new User();

        $this->userRepository->method('find')->willReturn($user);
        $this->entityManager->expects($this->once())->method('remove');
        $this->entityManager->expects($this->once())->method('flush');

        $this->userService->deleteUser(1);

        $this->assertTrue(true); // Bestätigung, dass kein Fehler auftritt
    }
}
