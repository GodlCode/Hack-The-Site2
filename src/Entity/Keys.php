<?php

namespace App\Entity;

use App\Repository\KeysRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KeysRepository::class)]
#[ORM\Table(name: '`keys`')]
class Keys
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $key_value = null;

    #[ORM\Column]
    private ?bool $is_sold = null;

    #[ORM\ManyToOne(inversedBy: 'productkey')]
    private ?Games $games = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKeyValue(): ?string
    {
        return $this->key_value;
    }

    public function setKeyValue(string $key_value): static
    {
        $this->key_value = $key_value;

        return $this;
    }

    public function isSold(): ?bool
    {
        return $this->is_sold;
    }

    public function setIsSold(bool $is_sold): static
    {
        $this->is_sold = $is_sold;

        return $this;
    }

    public function getGames(): ?Games
    {
        return $this->games;
    }

    public function setGames(?Games $games): static
    {
        $this->games = $games;

        return $this;
    }
}
