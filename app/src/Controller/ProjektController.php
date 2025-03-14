<?php

namespace App\Controller;

use App\Service\ProjektService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ProjektController extends AbstractController
{
    // Injectiert den ProjektService für die Verarbeitung von Projektdaten
    public function __construct(private ProjektService $projektService) {}

    // Gibt alle Projekte zurück
    #[Route('/projects', name: 'get_all_projects', methods: ['GET'])]
    public function getAllProjects(): JsonResponse
    {
        $projects = $this->projektService->getAllProjects();

        $data = [];
        foreach ($projects as $project) {
            $data[] = [
                'id'         => $project->getId(),
                'name'       => $project->getName(),
                'color'      => $project->getColor(),
                'created_at' => $project->getCreatedAt()->format('c'),
                'updated_at' => $project->getUpdatedAt()->format('c'),
            ];
        }

        return $this->json([
            'projects' => $data
        ]);
    }

    // Holt ein Projekt anhand der ID
    #[Route('/projects/{id}', name: 'get_project_by_id', methods: ['GET'])]
    public function getProjectById(int $id): JsonResponse
    {
        $project = $this->projektService->getProjectById($id);

        if (!$project) {
            return $this->json(['error' => 'Project not found'], 404);
        }

        $data = [
            'id'         => $project->getId(),
            'name'       => $project->getName(),
            'color'      => $project->getColor(),
            'created_at' => $project->getCreatedAt()->format('c'),
            'updated_at' => $project->getUpdatedAt()->format('c'),
        ];

        return $this->json($data);
    }

    // Erstellt ein neues Projekt
    #[Route('/projects', name: 'create_project', methods: ['POST'])]
    public function createProject(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['color'])) {
            return $this->json(['error' => 'Name and color are required'], 400);
        }

        $project = $this->projektService->createProject($data['name'], $data['color']);

        return $this->json([
            'id'         => $project->getId(),
            'name'       => $project->getName(),
            'color'      => $project->getColor(),
            'created_at' => $project->getCreatedAt()->format('c'),
            'updated_at' => $project->getUpdatedAt()->format('c'),
        ], 201);
    }

    // Aktualisiert ein bestehendes Projekt
    #[Route('/projects/{id}', name: 'update_project', methods: ['PUT'])]
    public function updateProject(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $project = $this->projektService->updateProject(
            $id,
            $data['name'] ?? null,
            $data['color'] ?? null
        );

        if (!$project) {
            return $this->json(['error' => 'Project not found'], 404);
        }

        return $this->json([
            'id'         => $project->getId(),
            'name'       => $project->getName(),
            'color'      => $project->getColor(),
            'created_at' => $project->getCreatedAt()->format('c'),
            'updated_at' => $project->getUpdatedAt()->format('c'),
        ]);
    }

    // Löscht ein Projekt anhand der ID
    #[Route('/projects/{id}', name: 'delete_project', methods: ['DELETE'])]
    public function deleteProject(int $id): JsonResponse
    {
        if (!$this->projektService->deleteProject($id)) {
            return $this->json(['error' => 'Project not found'], 404);
        }

        return $this->json(['message' => 'Project deleted successfully'], 200);
    }
}
