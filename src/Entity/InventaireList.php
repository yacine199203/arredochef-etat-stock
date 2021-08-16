<?php

namespace App\Entity;

use App\Repository\InventaireListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InventaireListRepository::class)
 */
class InventaireList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Inventaire::class, inversedBy="inventaireLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $inventaire;

    

    /**
     * @ORM\Column(type="integer")
     */
    private $comptage;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="inventaireLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInventaire(): ?Inventaire
    {
        return $this->inventaire;
    }

    public function setInventaire(?Inventaire $inventaire): self
    {
        $this->inventaire = $inventaire;

        return $this;
    }

    public function getComptage(): ?int
    {
        return $this->comptage;
    }

    public function setComptage(int $comptage): self
    {
        $this->comptage = $comptage;

        return $this;
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
}
