<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private Customer $customerID;

    #[ORM\Column(length: 255)]
    private string $description;

    #[ORM\Column(length: 255)]
    private string $description;
    
    public function getName(): string
    {
        return $this->name;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getCustomerID(): string
    {
        return $this->customerID;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getTasklist(): string //string is temp, hier überleitung zu task
    {
        return $this->tasklist;
    }
}

/*

'name' => $project->getName(),
            'id' => $project->getId(),
            'customerid' => $customer ->getId(),
            'description' => $project->getDescription(),
            'tasklist' => $project->getTasklist()