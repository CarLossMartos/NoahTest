<?php

namespace App\Controller;

use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class TaskController extends AbstractController
{
    public function __construct(private TaskService $taskService) {}

    #[Route('/tasks/{id}', name: 'get_task_by_id', methods: ['GET'])]
    public function getTaskById(int $id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);

        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        return $this->json($task);
    }

    #[Route('/tasks', name: 'create_task', methods: ['POST'])]
    public function createTask(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title'], $data['description'], $data['status'], $data['project_id'])) {
            return $this->json(['error' => 'All fields (title, description, status, project_id) are required'], 400);
        }

        $task = $this->taskService->createTask(
            $data['title'],
            $data['description'],
            $data['status'],
            $data['project_id']
        );

        if (!$task) {
            return $this->json(['error' => 'Project not found'], 404);
        }

        return $this->json([
            'id' => $task->getId(),
            'title' => $task->getTaskName(),
            'description' => $task->getTaskDescription(),
            'status' => $task->getStatus(),
            'project_id' => $task->getProjekt()->getId(),
            'project_name' => $task->getProjekt()->getName(),
            'project_color' => $task->getProjekt()->getColor(),
            'created_at' => $task->getCreatedAt()->format('c'),
            'updated_at' => $task->getUpdatedAt()->format('c'),
        ], 201);
    }

    #[Route('/tasks/{id}', name: 'update_task', methods: ['PUT'])]
    public function updateTask(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = $this->taskService->updateTask(
            $id,
            $data['title'] ?? null,
            $data['description'] ?? null,
            $data['status'] ?? null,
            $data['project_id'] ?? null
        );

        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        return $this->json([
            'id' => $task->getId(),
            'title' => $task->getTaskName(),
            'description' => $task->getTaskDescription(),
            'status' => $task->getStatus(),
            'project_id' => $task->getProjekt()->getId(),
            'project_name' => $task->getProjekt()->getName(),
            'project_color' => $task->getProjekt()->getColor(),
            'created_at' => $task->getCreatedAt()->format('c'),
            'updated_at' => $task->getUpdatedAt()->format('c'),
        ]);
    }
}
