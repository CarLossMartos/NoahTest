<?php

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\ProjektRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository,
        private ProjektRepository $projektRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Task nach ID abrufen
     */
    public function getTaskById(int $id): ?array
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            return null;
        }

        $project = $task->getProjekt();

        return [
            'id' => $task->getId(),
            'title' => $task->getTaskName(),
            'description' => $task->getTaskDescription(),
            'status' => $task->getStatus(),
            'project_id' => $project ? $project->getId() : null,
            'project_name' => $project ? $project->getName() : null,
            'project_color' => $project ? $project->getColor() : null,
            'created_at' => $task->getCreatedAt()->format('c'),
            'updated_at' => $task->getUpdatedAt()->format('c'),
        ];
    }

    /**
     * Alle Tasks abrufen
     */
    public function getAllTasks(): array
    {
        $tasks = $this->taskRepository->findAll();
        $result = [];

        foreach ($tasks as $task) {
            $project = $task->getProjekt();
            $result[] = [
                'id'             => $task->getId(),
                'title'          => $task->getTaskName(),
                'description'    => $task->getTaskDescription(),
                'status'         => $task->getStatus(),
                'project_id'     => $project ? $project->getId() : null,
                'project_name'   => $project ? $project->getName() : null,
                'project_color'  => $project ? $project->getColor() : null,
                'created_at'     => $task->getCreatedAt()->format('c'),
                'updated_at'     => $task->getUpdatedAt()->format('c'),
            ];
        }

        return $result;
    }

    /**
     * Neuen Task erstellen
     */
    public function createTask(string $title, string $description, string $status, int $projectId): ?Task
    {
        $project = $this->projektRepository->find($projectId);

        if (!$project) {
            return null;
        }

        $task = new Task();
        $task->setTaskName($title);
        $task->setTaskDescription($description);
        $task->setStatus($status);
        $task->setProjekt($project);
        $task->setCreatedAt(new \DateTime());
        $task->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    /**
     * Task aktualisieren
     */
    public function updateTask(int $id, ?string $title, ?string $description, ?string $status, ?int $projectId): ?Task
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            return null;
        }

        if ($projectId !== null) {
            $project = $this->projektRepository->find($projectId);
            if ($project) {
                $task->setProjekt($project);
            }
        }

        if ($title) {
            $task->setTaskName($title);
        }

        if ($description) {
            $task->setTaskDescription($description);
        }

        if ($status) {
            $task->setStatus($status);
        }

        $task->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        return $task;
    }

    public function deleteTask(int $id): bool
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            return false;
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return true;
    }
}
