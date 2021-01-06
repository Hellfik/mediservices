<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PretRepository")
 */
class Pret
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remarques;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pretStatus;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commercial;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sentAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $returnAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Materiel", inversedBy="prets")
     */
    private $materiaux;

    

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->materiaux = new ArrayCollection();
    }

    public function isBookableDates(){
        //1) Il faut connaitre le sdates qui sont impossible pour l'annonce

        //2)Il faut comparer les dates choisies avec les dates impossibles

        
        foreach($this->materiaux as $materiel){
            $notAvailableDays = $materiel->getNotAvailableDays();
        }

        $bookingDays = $this->getDays();
        dump($bookingDays);
        dump($notAvailableDays);
        
        $days = array_map(function($day){
            return $day->format('Y-m-d');
        }, $bookingDays);
       

        $notAvailable = array_map(function($day){
              return $day->format('Y-m-d');
        }, $notAvailableDays);
        

        foreach($days as $day){
            if(array_search($day, $notAvailable) !== false){
                return false;
            }
        }

        return true;
    }

    /**
     * Permet de recuperer un tableau des journées qui correspondent  ma reservation
     *
     * @return array Un tableau d'ojets DateTime respresetant les jours de la reservation
     */
    public function getDays(){
        $resultat = range(
            $this->dateDebut->getTimestamp(),
            $this->dateFin->getTimestamp(),
            24 * 60 *60
        );

        $days = array_map(function($dayTimeStamp){
            return new \DateTime(date('Y-m-d', $dayTimeStamp));
        }, $resultat);

        return $days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroPret(): ?int
    {
        return $this->numeroPret;
    }

    public function setNumeroPret(int $numeroPret): self
    {
        $this->numeroPret = $numeroPret;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getRemarques(): ?string
    {
        return $this->remarques;
    }

    public function setRemarques(?string $remarques): self
    {
        $this->remarques = $remarques;

        return $this;
    }

    public function getpretStatus(): ?string
    {
        return $this->pretStatus;
    }

    public function setpretStatus(?string $pretStatus): self
    {
        $this->pretStatus = $pretStatus;

        return $this;
    }

    public function getCommercial(): ?User
    {
        return $this->commercial;
    }

    public function setCommercial(?User $commercial): self
    {
        $this->commercial = $commercial;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(?\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getReturnAt(): ?\DateTimeInterface
    {
        return $this->returnAt;
    }

    public function setReturnAt(?\DateTimeInterface $returnAt): self
    {
        $this->returnAt = $returnAt;

        return $this;
    }

    /**
     * @return Collection|Materiel[]
     */
    public function getMateriaux(): Collection
    {
        return $this->materiaux;
    }

    public function addMateriaux(Materiel $materiaux): self
    {
        if (!$this->materiaux->contains($materiaux)) {
            $this->materiaux[] = $materiaux;
        }

        return $this;
    }

    public function removeMateriaux(Materiel $materiaux): self
    {
        if ($this->materiaux->contains($materiaux)) {
            $this->materiaux->removeElement($materiaux);
        }

        return $this;
    }

}
