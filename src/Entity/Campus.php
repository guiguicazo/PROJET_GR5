<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $nom;


    /**
     * @ORM\OneToMany(targetEntity=Date::class, mappedBy="campus")
     */
    private $campusdate;

    public function __construct()
    {
        $this->campusdate = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }



    /**
     * @return Collection<int, Date>
     */
    public function getCampusdate(): Collection
    {
        return $this->campusdate;
    }

    public function addCampusdate(Date $campusdate): self
    {
        if (!$this->campusdate->contains($campusdate)) {
            $this->campusdate[] = $campusdate;
            $campusdate->setCampus($this);
        }

        return $this;
    }

    public function removeCampusdate(Date $campusdate): self
    {
        if ($this->campusdate->removeElement($campusdate)) {
            // set the owning side to null (unless already changed)
            if ($campusdate->getCampus() === $this) {
                $campusdate->setCampus(null);
            }
        }

        return $this;
    }
}
