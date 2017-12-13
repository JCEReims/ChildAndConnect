<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Type;

/**
 * Association
 *
 * @ORM\Table(name="association")
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\AssociationRepository")
 */
class Association
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
     * @ORM\Column(name="nom", type="string", length=100)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="text")
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=100)
     */
    private $ville;
	
	/**
     * @ORM\OneToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\CodeEnfant", mappedBy="association", cascade={"all"} )
	 * @ORM\JoinColumn(nullable=false)
	 * @Exclude
	 * 
     */
    private $codeEnfant;

	/**
     * @ORM\OneToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\User", mappedBy="association", cascade={"persist"})
	 * @Exclude
	 * 
     */
    private $user;
	/**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100,nullable=true)
     */
    private $email;
	/**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=100,nullable=true)
     */
    private $telephone;
	/**
     * @var string
     *
     * @ORM\Column(name="responsable", type="string", length=100,nullable=true)
     */
    private $responsable;
   /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;
	
	/**
   * @ORM\ManyToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\Enfant",  mappedBy="associations")
    * @ORM\JoinColumn(nullable=false)
	* @ORM\JoinTable(name="enfant_association",
    *      joinColumns={@ORM\JoinColumn(name="association_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="enfant_id", referencedColumnName="id")}
    *      )
	* @Exclude
   */
  private $enfants;
  
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
     * Set nom
     *
     * @param string $nom
     * @return Association
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Association
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
     * Set ville
     *
     * @param string $ville
     * @return Association
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }
   
   

   

    /**
     * Add user
     *
     * @param \ChildConnect\CCSoftBundle\Entity\User $user
     * @return Association
     */
    public function addUser(\ChildConnect\CCSoftBundle\Entity\User $user)
    {
        $user->setAssociation($this);
		$this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \ChildConnect\CCSoftBundle\Entity\User $user
     */
    public function removeUser(\ChildConnect\CCSoftBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }

 

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Association
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif()
    {
        return $this->actif;
    }
  

    /**
     * Add codeEnfant
     *
     * @param \ChildConnect\CCSoftBundle\Entity\CodeEnfant $codeEnfant
     * @return Association
     */
    public function addCodeEnfant(\ChildConnect\CCSoftBundle\Entity\CodeEnfant $codeEnfant)
    {
        
		$codeEnfant->setAssociation($this);
		$this->codeEnfant[] = $codeEnfant;

        return $this;
    }

    /**
     * Remove codeEnfant
     *
     * @param \ChildConnect\CCSoftBundle\Entity\CodeEnfant $codeEnfant
     */
    public function removeCodeEnfant(\ChildConnect\CCSoftBundle\Entity\CodeEnfant $codeEnfant)
    {
        $this->codeEnfant->removeElement($codeEnfant);
    }

    /**
     * Get codeEnfant
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCodeEnfant()
    {
        return $this->codeEnfant;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Association
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Association
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set responsable
     *
     * @param string $responsable
     * @return Association
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return string 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->codeEnfant = new \Doctrine\Common\Collections\ArrayCollection();
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->enfants = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add enfants
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Enfant $enfants
     * @return Association
     */
    public function addEnfant(\ChildConnect\CCSoftBundle\Entity\Enfant $enfants)
    {
        $this->enfants[] = $enfants;

        return $this;
    }

    /**
     * Remove enfants
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Enfant $enfants
     */
    public function removeEnfant(\ChildConnect\CCSoftBundle\Entity\Enfant $enfants)
    {
        $this->enfants->removeElement($enfants);
    }

    /**
     * Get enfants
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEnfants()
    {
        return $this->enfants;
    }
}
