<?php

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function createTask(string $taskName, ?string $taskDescription, int $status, int $projektId): Task
    {
        $task = new Task();
        $task->setTaskName($taskName);
        $task->setTaskDescription($taskDescription);
        $task->setStatus($status);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function deleteTask(int $id): void
    {
        $task = $this->taskRepository->find($id);

        if ($task) {
            $this->entityManager->remove($task);
            $this->entityManager->flush();
        }
    }

    public function getTasksByProjektId(int $projektId): array
    {
        return $this->taskRepository->findByProjektId($projektId);
    }

    public function getTasksByUserId(int $userId): array
    {
        return $this->taskRepository->findByUserId($userId);
    }
}
