<?php

namespace App\Tests\Service;

use App\Entity\Task;
use App\Entity\Projekt;
use App\Repository\TaskRepository;
use App\Repository\ProjektRepository;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class TaskServiceTest extends TestCase
{
    private TaskService $taskService;
    private $taskRepository;
    private $projektRepository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->taskRepository = $this->createMock(TaskRepository::class);
        $this->projektRepository = $this->createMock(ProjektRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->taskService = new TaskService(
            $this->taskRepository,
            $this->projektRepository,
            $this->entityManager
        );
    }

    public function testGetTaskById(): void
    {
        $task = new Task();
        $task->setTaskName('Test Task');
        $task->setTaskDescription('Test Description');
        $task->setStatus('open');
        $task->setCreatedAt(new \DateTime());
        $task->setUpdatedAt(new \DateTime());

        $this->taskRepository->method('find')->willReturn($task);

        $result = $this->taskService->getTaskById(1);

        $this->assertIsArray($result);
        $this->assertEquals('Test Task', $result['title']);
    }

    public function testGetAllTasks(): void
    {
        $task1 = new Task();
        $task1->setTaskName('Task 1');
        $task1->setTaskDescription('Description 1');
        $task1->setStatus('open');
        $task1->setCreatedAt(new \DateTime());
        $task1->setUpdatedAt(new \DateTime());

        $task2 = new Task();
        $task2->setTaskName('Task 2');
        $task2->setTaskDescription('Description 2');
        $task2->setStatus('closed');
        $task2->setCreatedAt(new \DateTime());
        $task2->setUpdatedAt(new \DateTime());

        $this->taskRepository->method('findAll')->willReturn([$task1, $task2]);

        $result = $this->taskService->getAllTasks();

        $this->assertCount(2, $result);
    }

    public function testCreateTask(): void
    {
        $projekt = new Projekt();

        $this->projektRepository->method('find')->willReturn($projekt);
        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');

        $result = $this->taskService->createTask('New Task', 'New Description', 'open', 1);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals('New Task', $result->getTaskName());
    }

    public function testUpdateTask(): void
    {
        $task = new Task();
        $task->setTaskName('Old Task');
        $task->setTaskDescription('Old Description');
        $task->setStatus('open');

        $this->taskRepository->method('find')->willReturn($task);

        $result = $this->taskService->updateTask(1, 'Updated Task', 'Updated Description', 'closed', null);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals('Updated Task', $result->getTaskName());
    }

    public function testDeleteTask(): void
    {
        $task = new Task();

        $this->taskRepository->method('find')->willReturn($task);
        $this->entityManager->expects($this->once())->method('remove');
        $this->entityManager->expects($this->once())->method('flush');

        $result = $this->taskService->deleteTask(1);

        $this->assertTrue($result);
    }
}
