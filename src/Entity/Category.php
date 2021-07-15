<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=pokemon::class, mappedBy="category")
     */
    private $pokemons;

    public function __construct()
    {
        $this->pokemons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|pokemon[]
     */
    public function getPokemons(): Collection
    {
        return $this->pokemons;
    }

    public function addPokemon(pokemon $pokemon): self
    {
        if (!$this->pokemons->contains($pokemon)) {
            $this->pokemons[] = $pokemon;
            $pokemon->setCategory($this);
        }

        return $this;
    }

    public function removePokemon(pokemon $pokemon): self
    {
        if ($this->pokemons->removeElement($pokemon)) {
            // set the owning side to null (unless already changed)
            if ($pokemon->getCategory() === $this) {
                $pokemon->setCategory(null);
            }
        }

        return $this;
    }
}
