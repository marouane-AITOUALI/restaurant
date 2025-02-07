<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Plat>
     */
    #[ORM\ManyToMany(targetEntity: Plat::class, mappedBy: 'menus')]
    private Collection $plats;

    /**
     * @var Collection<int, Promotion>
     */
    #[ORM\OneToMany(targetEntity: Promotion::class, mappedBy: 'menu')]
    private Collection $promotions;

    #[ORM\Column]
    private ?int $nombrePersonne = null;

    public function __construct()
    {
        $this->plats = new ArrayCollection();
        $this->promotions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getPlats(): Collection
    {
        return $this->plats;
    }

    public function addPlat(Plat $plat): static
    {
        if (!$this->plats->contains($plat)) {
            $this->plats->add($plat);
            $plat->addMenu($this);
        }


        return $this;
    }

    public function removePlat(Plat $plat): static
    {
        if ($this->plats->removeElement($plat)) {
            if ($plat->getMenus()->contains($this)) {
                $plat->removeMenu($this);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Promotion>
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): static
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions->add($promotion);
            $promotion->setMenu($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): static
    {
        if ($this->promotions->removeElement($promotion)) {
            if ($promotion->getMenu() === $this) {
                $promotion->setMenu(null);
            }
        }

        return $this;
    }

    public function getNombrePersonne(): ?int
    {
        return $this->nombrePersonne;
    }

    public function setNombrePersonne(int $nombrePersonne): static
    {
        $this->nombrePersonne = $nombrePersonne;

        return $this;
    }

    public function getPriceAllPlats(): float
    {
        $price = 0;
        foreach ($this->plats as $plat) {
            $price += $plat->getPrice();
        }

        return $price;
    }
}

