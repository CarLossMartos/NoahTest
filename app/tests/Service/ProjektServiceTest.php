<?php
namespace App\Tests\Service;

use App\Entity\Projekt;
use App\Repository\ProjektRepository;
use App\Repository\TaskRepository;
use App\Service\ProjektService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ProjektServiceTest extends TestCase
{
    private ProjektRepository $projektRepository;
    private TaskRepository $taskRepository;
    private EntityManagerInterface $entityManager;
    private ProjektService $projektService;

    protected function setUp(): void
    {
        $this->projektRepository = $this->createMock(ProjektRepository::class);
        $this->taskRepository = $this->createMock(TaskRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->projektService = new ProjektService(
            $this->projektRepository,
            $this->taskRepository,
            $this->entityManager
        );
    }

    public function testGetAllProjects(): void
    {
        $projekt1 = new Projekt();
        $projekt1->setName("Testprojekt 1");
        $projekt1->setColor("#FF5733");

        $projekt2 = new Projekt();
        $projekt2->setName("Testprojekt 2");
        $projekt2->setColor("#4287f5");

        $this->projektRepository
            ->method('findAll')
            ->willReturn([$projekt1, $projekt2]);

        $result = $this->projektService->getAllProjects();
        $this->assertCount(2, $result);
        $this->assertEquals("Testprojekt 1", $result[0]->getName());
        $this->assertEquals("#FF5733", $result[0]->getColor());
    }

    public function testCreateProject(): void
    {
        $this->entityManager
            ->expects($this->once())
            ->method('persist');

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $projekt = $this->projektService->createProject("Neues Projekt", "#ffcc00");

        $this->assertEquals("Neues Projekt", $projekt->getName());
        $this->assertEquals("#ffcc00", $projekt->getColor());
    }

    public function testUpdateProject(): void
    {
        $projekt = new Projekt();
        $projekt->setName("Altes Projekt");
        $projekt->setColor("#123456");

        $this->projektRepository
            ->method('find')
            ->willReturn($projekt);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $updatedProjekt = $this->projektService->updateProject(1, "Neuer Name", "#654321");

        $this->assertEquals("Neuer Name", $updatedProjekt->getName());
        $this->assertEquals("#654321", $updatedProjekt->getColor());
    }

    public function testDeleteProject(): void
    {
        $projekt = new Projekt();
        $this->projektRepository
            ->method('find')
            ->willReturn($projekt);

        $this->entityManager
            ->expects($this->once())
            ->method('remove')
            ->with($projekt);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $result = $this->projektService->deleteProject(1);
        $this->assertTrue($result);
    }
}
