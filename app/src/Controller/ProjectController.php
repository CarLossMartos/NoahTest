<?php
namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    #[Route('/api/customer/{id}', name: 'get_project', methods: ['GET'])]
    public function getProject(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            return $this->json(['error' => 'Project not found'], 404);
        }

        return $this->json([
            'name' => $project->getName(),
            'id' => $project->getId(),
            'customerid' => $customer ->getCustomer(),
            'description' => $project->getDescription(),
            'tasklist' => $project->getTasklist()
        ]);
    }
}
