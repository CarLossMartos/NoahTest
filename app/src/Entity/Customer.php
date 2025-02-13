<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $customer;

    #[ORM\Column(length: 255)]
    private string $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): string
    {
        return $this->customer;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
