<?php

namespace App\Entity;

use App\Repository\LoyaltyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoyaltyRepository::class)]
class Loyalty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'loyalties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $numberPoints = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getNumberPoints(): ?int
    {
        return $this->numberPoints;
    }

    public function setNumberPoints(int $numberPoints): self
    {
        $this->numberPoints = $numberPoints;

        return $this;
    }
}
