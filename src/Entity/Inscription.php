<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
class Inscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Classe::class, inversedBy: 'inscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private $classe;

    #[ORM\OneToMany(mappedBy: 'inscription', targetEntity: Demande::class)]
    private $demandes;

    #[ORM\Column(type: 'string', length: 255)]
    private $etat;

    #[ORM\ManyToOne(targetEntity: AC::class, inversedBy: 'inscriptions')]
    private $ac;

    #[ORM\ManyToOne(targetEntity: Etudiant::class, inversedBy: 'inscriptions')]
    private $etudiant;

    #[ORM\ManyToOne(targetEntity: AnneeScolaire::class, inversedBy: 'inscriptions')]
    private $annee;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
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
            $demande->setInscription($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getInscription() === $this) {
                $demande->setInscription(null);
            }
        }

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getAc(): ?AC
    {
        return $this->ac;
    }

    public function setAc(?AC $ac): self
    {
        $this->ac = $ac;

        return $this;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getAnnee(): ?AnneeScolaire
    {
        return $this->annee;
    }

    public function setAnnee(?AnneeScolaire $annee): self
    {
        $this->annee = $annee;

        return $this;
    }
}
