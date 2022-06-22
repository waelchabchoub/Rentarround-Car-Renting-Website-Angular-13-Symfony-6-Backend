<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ApiResource(attributes: ["pagination_enabled" => false],
    normalizationContext:['groups'=>['reservation.read']],
    denormalizationContext:['groups'=>['reservation.write']],
    itemOperations:[
        'get',
        'patch',
        'delete',
        'put'=>['denormalization_context'=>['groups'=>['reservation.put']]],
    ],
)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['reservation.read'])]
    private $id;

    #[ORM\Column(type: 'date')]
    #[Groups(['reservation.read','reservation.write'])]
    private $startDate;

    #[ORM\Column(type: 'date')]
    #[Groups(['reservation.read','reservation.write'])]
    private $endDate;

    #[ORM\Column(type: 'float')]
    #[Groups(['reservation.read','reservation.write'])]
    private $totalPrice;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['reservation.read','reservation.write','reservation.put'])]
    private $status;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['reservation.read','reservation.write'])]
    private $user;

    #[ORM\ManyToOne(targetEntity: Vehicle::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['reservation.read','reservation.write'])]
    private $vehicle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }
}
