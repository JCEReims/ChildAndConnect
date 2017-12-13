<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Type;

/**
 * Lieu
 *
 * @ORM\Table(name="lieu")
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\LieuRepository")
 * @ExclusionPolicy("none")
 */
class Lieu
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
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;
  

	/**
     * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\Enfant", inversedBy="lieux")
	 * @ORM\JoinColumn(nullable=false)
	 * @Exclude
	 *
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
     * Set latitude
     *
     * @param string $latitude
     * @return Lieu
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
     * @return Lieu
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
     * @return Lieu
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



    /**
     * Set enfant
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Enfant $enfant
     * @return Lieu
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
     * Set commentaire
     *
     * @param string $commentaire
     * @return Lieu
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = str_replace(array("\r\n", "\r", "\n"), "<br />",$commentaire);

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
}
