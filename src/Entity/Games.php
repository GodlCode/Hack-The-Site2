<?php

namespace App\Entity;

use App\Repository\GamesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GamesRepository::class)]
class Games
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    /**
     * @var Collection<int, keys>
     */
    #[ORM\OneToMany(targetEntity: keys::class, mappedBy: 'games')]
    private Collection $productkey;

    public function __construct()
    {
        $this->productkey = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, keys>
     */
    public function getProductkey(): Collection
    {
        return $this->productkey;
    }

    public function addProductkey(keys $productkey): static
    {
        if (!$this->productkey->contains($productkey)) {
            $this->productkey->add($productkey);
            $productkey->setGames($this);
        }

        return $this;
    }

    public function removeProductkey(keys $productkey): static
    {
        if ($this->productkey->removeElement($productkey)) {
            // set the owning side to null (unless already changed)
            if ($productkey->getGames() === $this) {
                $productkey->setGames(null);
            }
        }

        return $this;
    }
}
