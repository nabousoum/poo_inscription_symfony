<?php

namespace App\Entity;

use App\Repository\RPRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RPRepository::class)]
class RP extends User
{
    #[ORM\OneToMany(mappedBy: 'rp', targetEntity: Demande::class)]
    private $demandes;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes[] = $demande;
            $demande->setRp($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getRp() === $this) {
                $demande->setRp(null);
            }
        }

        return $this;
    }
}
