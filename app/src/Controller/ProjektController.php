<?php

namespace App\Controller;

use App\Entity\Projekt;
use App\Repository\ProjektRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjektController extends AbstractController
{
    #[Route('/api', name: 'api_')]
    public function index(): Response
    {
        return $this->render('projekt/index.html.twig', [
            'controller_name' => 'ProjektController',
        ]);
    }

    #[Route('/projects', name: 'get_all_projects', methods: ['GET'])]
    public function getAllProjects(ProjektRepository $projektRepository): JsonResponse
    {
        $projects = $projektRepository->findAll();

        $data = [];
        foreach ($projects as $project) {
            $data[] = [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'color' => $project->getColor(),
                'created_at' => $project->getCreatedAt()->format('c'),
                'updated_at' => $project->getUpdatedAt()->format('c'),
            ];
        }

        return $this->json(['projects' => $data]);
    }


    #[Route('/projects/{id}', name: 'get_project_by_id', methods: ['GET'])]
    public function getProjectById(int $id, ProjektRepository $projektRepository, TaskRepository $taskRepository): JsonResponse
    {
        $project = $projektRepository->find($id);

        if (!$project) {
            return $this->json(['error' => 'Project not found'], 404);
        }

        $tasks = $taskRepository->findBy(['projekt' => $project]);

        $tasksTotal = count($tasks);
        $tasksInProgress = count(array_filter($tasks, fn($task) => $task->getStatus() === 'in_progress'));
        $tasksCompleted = count(array_filter($tasks, fn($task) => $task->getStatus() === 'completed'));

        $data = [
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

        return $this->json($data);
    }

    #[Route('/projects', name: 'create_project', methods: ['POST'])]
    public function createProject(Request $request, ProjektRepository $projektRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['color'])) {
            return $this->json(['error' => 'Name and color are required'], 400);
        }

        $project = new Projekt();
        $project->setName($data['name']);
        $project->setColor($data['color']);
        $project->setCreatedAt(new \DateTime());
        $project->setUpdatedAt(new \DateTime());

        $entityManager->persist($project);
        $entityManager->flush();

        return $this->json([
            'id' => $project->getId(),
            'name' => $project->getName(),
            'color' => $project->getColor(),
            'created_at' => $project->getCreatedAt()->format('c'),
            'updated_at' => $project->getUpdatedAt()->format('c'),
        ], 201);
    }

    #[Route('/projects/{id}', name: 'update_project', methods: ['PUT'])]
    public function updateProject(int $id, Request $request, ProjektRepository $projektRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $project = $projektRepository->find($id);

        if (!$project) {
            return $this->json(['error' => 'Project not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $project->setName($data['name']);
        }

        if (isset($data['color'])) {
            $project->setColor($data['color']);
        }

        $project->setUpdatedAt(new \DateTime());

        $entityManager->flush();

        return $this->json([
            'id' => $project->getId(),
            'name' => $project->getName(),
            'color' => $project->getColor(),
            'created_at' => $project->getCreatedAt()->format('c'),
            'updated_at' => $project->getUpdatedAt()->format('c'),
        ]);
    }


    #[Route('/projects/{id}', name: 'delete_project', methods: ['DELETE'])]
    public function deleteProject(int $id, ProjektRepository $projektRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $project = $projektRepository->find($id);

        if (!$project) {
            return $this->json(['error' => 'Project not found'], 404);
        }

        $entityManager->remove($project);
        $entityManager->flush();

        return $this->json(['message' => 'Project deleted successfully'], 200);
    }

    #[Route('/projekt/user/{userId}', name: 'get_projekt_by_user', methods: ['GET'])]
    public function getByUserId(int $userId, EntityManagerInterface $entityManager): JsonResponse
    {
        $projekt = $entityManager->getRepository(Projekt::class)->findOneBy(['userId' => $userId]);

        if (!$projekt) {
            return $this->json(['error' => 'No project found for this user'], 404);
        }

        return $this->json([
            'id' => $projekt->getId(),
            'name' => $projekt->getName(),
            'description' => $projekt->getDescription(),
        ], 200);
    }

}
