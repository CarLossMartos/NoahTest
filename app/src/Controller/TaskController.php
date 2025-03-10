<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Projekt;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/task/create', name: 'create_task', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['taskName'], $data['projektId'])) {
            return $this->json(['error' => 'Task name and project ID are required'], 400);
        }

        $projekt = $entityManager->getRepository(Projekt::class)->find($data['projektId']);

        if (!$projekt) {
            return $this->json(['error' => 'Project not found'], 404);
        }

        $task = new Task();
        $task->setTaskName($data['taskName']);
        $task->setTaskDescription($data['taskDescription'] ?? null);
        $task->setStatus($data['status'] ?? 0);
        $task->setProjekt($projekt);

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->json([
            'message' => 'Task created successfully',
            'task' => [
                'id' => $task->getId(),
                'taskName' => $task->getTaskName(),
                'taskDescription' => $task->getTaskDescription(),
                'status' => $task->getStatus(),
                'projectId' => $projekt->getId(),
            ],
        ], 201);
    }

    #[Route('/task/project/{projektId}', name: 'get_tasks_by_project', methods: ['GET'])]
    public function getTasksByProject(int $projektId, EntityManagerInterface $entityManager): JsonResponse
    {
        $projekt = $entityManager->getRepository(Projekt::class)->find($projektId);

        if (!$projekt) {
            return $this->json(['error' => 'Project not found'], 404);
        }

        $tasks = $projekt->getTasks();
        $taskData = [];

        foreach ($tasks as $task) {
            $taskData[] = [
                'id' => $task->getId(),
                'taskName' => $task->getTaskName(),
                'taskDescription' => $task->getTaskDescription(),
                'status' => $task->getStatus(),
            ];
        }

        return $this->json($taskData);
    }

    #[Route('/task', name: 'get_all_tasks', methods: ['GET'])]
    public function getAllTasks(EntityManagerInterface $entityManager): JsonResponse
    {
        $tasks = $entityManager->getRepository(Task::class)->findAll();
        $taskData = [];

        foreach ($tasks as $task) {
            $taskData[] = [
                'id' => $task->getId(),
                'taskName' => $task->getTaskName(),
                'taskDescription' => $task->getTaskDescription(),
                'status' => $task->getStatus(),
            ];
        }

        return $this->json($taskData);
    }

    #[Route('/task/user/{userId}', name: 'get_tasks_by_user', methods: ['GET'])]
    public function getTasksByUser(int $userId, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $tasks = $user->getTasks();
        $taskData = [];

        foreach ($tasks as $task) {
            $taskData[] = [
                'id' => $task->getId(),
                'taskName' => $task->getTaskName(),
                'taskDescription' => $task->getTaskDescription(),
                'status' => $task->getStatus(),
            ];
        }

        return $this->json($taskData);
    }

    #[Route('/task/delete/{id}', name: 'delete_task', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->json(['message' => 'Task deleted successfully'], 200);
    }
}
