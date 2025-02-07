<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $status = 0;

    #[ORM\ManyToOne(inversedBy: 'reservation')]
    private ?Table $table_id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\Column]
    private ?int $isEvent = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $phone = null; // Nouveau champ pour le téléphone

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $number_of_people = null; // Nouveau champ pour le nombre de personnes

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null; // Nouveau champ pour les notes

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getTableId(): ?Table
    {
        return $this->table_id;
    }

    public function setTableId(?Table $table_id): static
    {
        $this->table_id = $table_id;
        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getIsEvent(): ?int
    {
        return $this->isEvent;
    }

    public function setIsEvent(int $isEvent): static
    {
        $this->isEvent = $isEvent;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getNumberOfPeople(): ?int
    {
        return $this->number_of_people;
    }

    public function setNumberOfPeople(?int $number_of_people): static
    {
        $this->number_of_people = $number_of_people;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;
        return $this;
    }
}
