<?php

namespace App\Service;

use App\Entity\Projekt;
use App\Repository\ProjektRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProjektService
{
    public function __construct(
        private ProjektRepository $projektRepository,
        private TaskRepository $taskRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Alle Projekte abrufen
     */
    public function getAllProjects(): array
    {
        return $this->projektRepository->findAll();
    }

    /**
     * Ein Projekt nach ID abrufen + Task-Count
     */
    public function getProjectById(int $id): ?array
    {
        $project = $this->projektRepository->find($id);

        if (!$project) {
            return null;
        }

        // Tasks abrufen und Status zählen
        $tasks = $this->taskRepository->findBy(['projekt' => $project]);
        $tasksTotal = count($tasks);
        $tasksInProgress = count(array_filter($tasks, fn($task) => $task->getStatus() === 'in_progress'));
        $tasksCompleted = count(array_filter($tasks, fn($task) => $task->getStatus() === 'completed'));

        return [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'color' => $project->getColor(),
            'created_at' => $project->getCreatedAt()->format('c'),
            'updated_at' => $project->getUpdatedAt()->format('c'),
            'tasks_count' => [
                'total' => $tasksTotal,
                'in_progress' => $tasksInProgress,
                'completed' => $tasksCompleted,
            ],
        ];
    }

    /**
     * Neues Projekt erstellen
     */
    public function createProject(string $name, string $color): Projekt
    {
        $project = new Projekt();
        $project->setName($name);
        $project->setColor($color);
        $project->setCreatedAt(new \DateTime());
        $project->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }

    /**
     * Projekt aktualisieren
     */
    public function updateProject(int $id, ?string $name, ?string $color): ?Projekt
    {
        $project = $this->projektRepository->find($id);

        if (!$project) {
            return null;
        }

        if ($name) {
            $project->setName($name);
        }

        if ($color) {
            $project->setColor($color);
        }

        $project->setUpdatedAt(new \DateTime());
        $this->entityManager->flush();

        return $project;
    }

    /**
     * Projekt löschen
     */
    public function deleteProject(int $id): bool
    {
        $project = $this->projektRepository->find($id);

        if (!$project) {
            return false;
        }

        $this->entityManager->remove($project);
        $this->entityManager->flush();

        return true;
    }
}
