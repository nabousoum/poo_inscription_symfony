<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: ClasseRepository::class)]
// #[UniqueEntity(fields:'libelle',message:'le libelle doit etre unique')]
class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:'le libelle ne doit pas etre vide')]
    private $libelle;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBLank(message:'veuillez choisir une filiere')]
    private $filiere;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:'veuillez choisir un niveau')]
    private $niveau;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Inscription::class)]
    private $inscriptions;

    #[ORM\ManyToMany(targetEntity: Professeur::class, inversedBy: 'classes')]
    private $professeurs;

    public static $niveaux = ['Choisir un niveau'=>'','licence 1'=>'licence 1','licence 2'=>'licence 2','licence 3'=>'licence 3','master 1'=>'master 1','master 2'=>'master 2','doctorat'=>'doctorat'];

    public static $filieres = ['Choisir une filiere'=>'','Dev web'=>'Dev web','gestion'=>'gestion','dev web mobile'=>'dev web mobile','compta'=>'compta','anglais'=>'anglais'];
    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->professeurs = new ArrayCollection();
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
        $this->libelle = $libelle;

        return $this;
    }

    public function getFiliere(): ?string
    {
        return $this->filiere;
    }

    public function setFiliere(string $filiere): self
    {
        $this->filiere = $filiere;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setClasse($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getClasse() === $this) {
                $inscription->setClasse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Professeur>
     */
    public function getProfesseurs(): Collection
    {
        return $this->professeurs;
    }

    public function addProfesseur(Professeur $professeur): self
    {
        if (!$this->professeurs->contains($professeur)) {
            $this->professeurs[] = $professeur;
        }

        return $this;
    }

    public function removeProfesseur(Professeur $professeur): self
    {
        $this->professeurs->removeElement($professeur);

        return $this;
    }

   
}
