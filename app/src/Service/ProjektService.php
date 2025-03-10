<?php

namespace App\Service;

use App\Entity\Projekt;
use App\Repository\ProjektRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProjektService
{
    public function __construct(
        private ProjektRepository $projektRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function createProjekt(string $name, ?string $description = null): Projekt
    {
        $projekt = new Projekt();
        $projekt->setName($name);
        $projekt->setDescription($description);

        $this->entityManager->persist($projekt);
        $this->entityManager->flush();

        return $projekt;
    }

    public function deleteProjekt(int $id): void
    {
        $projekt = $this->projektRepository->find($id);

        if ($projekt) {
            $this->entityManager->remove($projekt);
            $this->entityManager->flush();
        }
    }

    public function getProjektByUserId(int $userId): ?Projekt
    {
        return $this->projektRepository->findByUserId($userId);
    }
}
