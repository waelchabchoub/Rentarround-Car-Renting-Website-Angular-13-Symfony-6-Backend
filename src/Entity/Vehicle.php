<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
#[ApiResource(
    denormalizationContext:['groups'=>['vehicle.write']],
    collectionOperations:[
        'get',
        'post' => ['security'=>'is_granted("ROLE_ADMIN")'],
       
        
    ],
    itemOperations: [
        'get',
        'put'=>['denormalization_context'=>['groups'=>['reservation.put']]],
        'patch',
        'delete'=>['security'=>'is_granted("ROLE_ADMIN")'],
    ]
),
    ApiFilter(
        SearchFilter::class,
        properties:[
        'model'=>SearchFilter::STRATEGY_PARTIAL,
        'region'=>SearchFilter::STRATEGY_PARTIAL,
        'availability'=> SearchFilter::STRATEGY_PARTIAL
    ]
    ),
    ]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['reservation.read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['reservation.read','vehicle.write'])]
    private $model;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['vehicle.write'])]
    private $color;

    #[ORM\Column(type: 'float')]
    #[Groups(['vehicle.write'])]
    private $pricePerDay;

    #[ORM\Column(type: 'integer')]
    #[Groups(['vehicle.write'])]
    private $placeNb;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['vehicle.write'])]
    private $region;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['vehicle.write','reservation.read','reservation.put'])]
    private $availability;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['vehicle.write'])]
    private $image;

    #[ORM\OneToMany(mappedBy: 'vehicle', targetEntity: Reservation::class, orphanRemoval: true)]
    private $reservations;

    #[ORM\OneToMany(mappedBy: 'vehicle', targetEntity: Comment::class, orphanRemoval: true)]
    #[ApiSubresource()]
    private $comments;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getPricePerDay(): ?float
    {
        return $this->pricePerDay;
    }

    public function setPricePerDay(float $pricePerDay): self
    {
        $this->pricePerDay = $pricePerDay;

        return $this;
    }

    public function getPlaceNb(): ?int
    {
        return $this->placeNb;
    }

    public function setPlaceNb(int $placeNb): self
    {
        $this->placeNb = $placeNb;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function isAvailability(): ?bool
    {
        return $this->availability;
    }

    public function setAvailability(bool $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setVehicle($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getVehicle() === $this) {
                $reservation->setVehicle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setVehicle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getVehicle() === $this) {
                $comment->setVehicle(null);
            }
        }

        return $this;
    }

  
    
}
