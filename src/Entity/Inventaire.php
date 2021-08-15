<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\InventaireRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InventaireRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Inventaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ est vide")
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=InventaireList::class, mappedBy="inventaire", orphanRemoval=true)
     */
    private $inventaireLists;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="inventaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $traitepar;

    public function __construct()
    {
        $this->inventaireLists = new ArrayCollection();
    }

    /** 
    *@ORM\PrePersist
    *@ORM\PreUpdate
    *@return void 
    */
    public function intialSlug(){
        if(empty($this->slug) || !empty($this->slug)){
            $slugify= new Slugify();
            $this->slug = $slugify->slugify($this->libelle);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = ucfirst(mb_strtoupper($libelle, 'UTF-8'));

        return $this;
    }

    /**
     * @return Collection|InventaireList[]
     */
    public function getInventaireLists(): Collection
    {
        return $this->inventaireLists;
    }

    public function addInventaireList(InventaireList $inventaireList): self
    {
        if (!$this->inventaireLists->contains($inventaireList)) {
            $this->inventaireLists[] = $inventaireList;
            $inventaireList->setInventaire($this);
        }

        return $this;
    }

    public function removeInventaireList(InventaireList $inventaireList): self
    {
        if ($this->inventaireLists->removeElement($inventaireList)) {
            // set the owning side to null (unless already changed)
            if ($inventaireList->getInventaire() === $this) {
                $inventaireList->setInventaire(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTraitepar(): ?User
    {
        return $this->traitepar;
    }

    public function setTraitepar(?User $traitepar): self
    {
        $this->traitepar = $traitepar;

        return $this;
    }
}
