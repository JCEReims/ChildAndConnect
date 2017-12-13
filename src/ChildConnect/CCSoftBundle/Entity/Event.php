<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Type;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\EventRepository")
 * @ExclusionPolicy("none")
 */
class Event
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

   /**
     * @var string
     *
     * @ORM\Column(name="evenement", type="text")
     */
    private $evenement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
	 * @Type("DateTime<'d-m-Y'>")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text",nullable=true)
     */
    private $commentaire;

  /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="decimal", nullable=true, precision=24, scale=20)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="decimal", nullable=true,precision=24, scale=20)
     */
    private $longitude;

    
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;
	/**
     * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\Enfant", inversedBy="events")
	 * @ORM\JoinColumn(nullable=false)
	 * @Exclude
     */
    private $enfant;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

 

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return event
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return event
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set enfant
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Enfant $enfant
     * @return Event
     */
    public function setEnfant(\ChildConnect\CCSoftBundle\Entity\Enfant $enfant)
    {
        $this->enfant = $enfant;

        return $this;
    }

    /**
     * Get enfant
     *
     * @return \ChildConnect\CCSoftBundle\Entity\Enfant 
     */
    public function getEnfant()
    {
        return $this->enfant;
    }

    /**
     * Set evenement
     *
     * @param string $evenement
     * @return Event
     */
    public function setEvenement($evenement)
    {
        $this->evenement = $evenement;

        return $this;
    }

    /**
     * Get evenement
     *
     * @return string 
     */
    public function getEvenement()
    {
        return $this->evenement;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Event
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Event
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Event
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }
}
