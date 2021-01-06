<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterielRepository")
 * @ORM\Table(name="materiel")
 * @UniqueEntity(fields="serialNumber", message="Numero de serie utilisé")
 */
class Materiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(min="6", max="6", exactMessage="Le numero de serie doit etre composé de 6 chiffres",)
     * 
     */
    private $serialNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CategoryMateriel", inversedBy="materiels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Pret", mappedBy="materiaux")
     */
    private $prets;

    public function __construct()
    {
        $this->prets = new ArrayCollection();
    }

    /**
     * Permet d'obtenir un tableau des jours qui ne sont pas disponible pour ce materiel
     */

    public function getNotAvailableDays(){
        $notAvailableDays = [];
        //Calculer les jours qui se trouvent entre la date de debut du pret et la fin
        $resultat = [];
        $threeDaysTimeStamp = 3600 * 24 * 3;
            foreach($this->prets as $pret){ 
                
                $resultat = range(
                    $pret->getDateDebut()->getTimestamp(),
                    $pret->getDateFin()->getTimeStamp() + $threeDaysTimeStamp,
                    24 * 60 * 60
                );
            }
        $days = array_map(function($dayTimeStamp){
                return new\DateTime(date('Y-m-d', $dayTimeStamp));
            }, $resultat);
    
            $notAvailableDays = array_merge($notAvailableDays, $days);
    
            return $notAvailableDays;

        }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerialNumber(): ?int
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(int $serialNumber): self
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCategory(): ?CategoryMateriel
    {
        return $this->category;
    }

    public function setCategory(?CategoryMateriel $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Pret[]
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPret(Pret $pret): self
    {
        if (!$this->prets->contains($pret)) {
            $this->prets[] = $pret;
            $pret->addMateriaux($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->contains($pret)) {
            $this->prets->removeElement($pret);
            $pret->removeMateriaux($this);
        }

        return $this;
    }

}
