<?php

namespace App\Controller;

use App\Entity\Projekt;
use App\Repository\ProjektRepository;
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


    #[Route('/projekt/create', name: 'create_projekt', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'])) {
            return $this->json(['error' => 'Project name is required'], 400);
        }

        $projekt = new Projekt();
        $projekt->setName($data['name']);
        $projekt->setDescription($data['description'] ?? null);

        $entityManager->persist($projekt);
        $entityManager->flush();

        return $this->json([
            'message' => 'Project created successfully',
            'project' => [
                'id' => $projekt->getId(),
                'name' => $projekt->getName(),
                'description' => $projekt->getDescription(),
            ],
        ], 201);
    }

    #[Route('/projekt/delete/{id}', name: 'delete_projekt', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $projekt = $entityManager->getRepository(Projekt::class)->find($id);

        if (!$projekt) {
            return $this->json(['error' => 'Project not found'], 404);
        }

        $entityManager->remove($projekt);
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
