<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Type;

/**
 * Enfant
 *
 * @ORM\Table(name="enfant")
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\EnfantRepository")
 * @ExclusionPolicy("none")
 */
class Enfant
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
     * @ORM\Column(name="nom", type="string", length=255,nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_naissance", type="date")
	 * @Type("DateTime<'d-m-Y'>")
     */
    private $dateNaissance;
	
	/**
     * @var string
     *
     * @ORM\Column(name="surnom", type="string", length=255,nullable=true))
     */
    private $surnom;
	
	/**
     * @var string
     *
     * @ORM\Column(name="signe_distinctif",  type="text",nullable=true)
     */
    private $signeDistinctif;
	
	/**
     * @ORM\OneToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\Event", mappedBy="enfant", cascade={"all"} )
	 * @ORM\JoinColumn(nullable=false)
	 * @Exclude
     */
    private $events;
  
  /**
     * @ORM\OneToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\Lieu", mappedBy="enfant", cascade={"all"} )
	 * @ORM\JoinColumn(nullable=false)
	 * @Exclude
     */
    private $lieux;
	
	/**
   * @ORM\OneToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\EnfantQuiz", mappedBy="enfant",cascade={"persist", "remove"}, orphanRemoval=TRUE)
   * @ORM\JoinColumn(nullable=false)
   * @Exclude
   */
  private $enfantQuizs;
 
 
  /**
   * @ORM\ManyToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\Association", cascade={"persist"} , inversedBy="enfants")
    * @ORM\JoinColumn(nullable=false)
	* @ORM\JoinTable(name="enfant_association",
    *      joinColumns={@ORM\JoinColumn(name="enfant_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="association_id", referencedColumnName="id")}
    *      )
	* @Exclude
   */
  private $associations;
  
	/**
     * @ORM\OneToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\CodeEnfant",cascade={"all"} )
	 * @ORM\JoinColumn(nullable=true)
	 * @Exclude
     */
    private $codeEnfant;
	
	 /**
     * @var string
     *
     * @ORM\Column(name="pays_origine", type="string", length=150, nullable=true)
	 * @Type("string")
     */
    private $paysOrigine;
	/**
     * @var string
     *
     * @ORM\Column(name="ville_origine", type="string", length=150, nullable=true)
     */
    private $villeOrigine;
	/**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=1)
     */
    private $sexe;
	
	/**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;
	
	/**
     *
     * @ORM\OneToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\Photo", cascade={"all"}, mappedBy="enfant")
	 * @ORM\JoinColumn(nullable=true)
	 * @ORM\OrderBy({"date" = "DESC"})
	 * @Exclude
     */
    private $photo;
	
	/**
     * @var string
     *
     * @ORM\Column(name="suivi_par", type="text", nullable=true)
     */
    private $suiviPar;
	
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
	 * @Type("DateTime<'d-m-Y H:i:s'>")
     */
    private $createdAt;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="date_entree", type="date")
	 * @Type("DateTime<'d-m-Y'>")
     */
    private $dateEntree;
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="date_sortie", type="date", nullable=true)
	 * @Type("DateTime<'d-m-Y'>")
     */
    private $dateSortie;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime")
	 * @Type("DateTime<'d-m-Y H:i:s'>")
     */
    private $modifiedAt;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="association_base", type="integer")
	 * @Exclude
     */
    private $associationBase;
	
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
     * @return Enfant
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
     * Set prenom
     *
     * @param string $prenom
     * @return Enfant
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return Enfant
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set surnom
     *
     * @param string $surnom
     * @return Enfant
     */
    public function setSurnom($surnom)
    {
        $this->surnom = $surnom;

        return $this;
    }

    /**
     * Get surnom
     *
     * @return string 
     */
    public function getSurnom()
    {
        return $this->surnom;
    }

    /**
     * Set signeDistinctif
     *
     * @param string $signeDistinctif
     * @return Enfant
     */
    public function setSigneDistinctif($signeDistinctif)
    {
        $this->signeDistinctif = $signeDistinctif;

        return $this;
    }

    /**
     * Get signeDistinctif
     *
     * @return string 
     */
    public function getSigneDistinctif()
    {
        return $this->signeDistinctif;
    }
  



    /**
     * Add enfantQuizs
     *
     * @param \ChildConnect\CCSoftBundle\Entity\EnfantQuiz $enfantQuizs
     * @return Enfant
     */
    public function addEnfantQuiz(\ChildConnect\CCSoftBundle\Entity\EnfantQuiz $enfantQuizs)
    {
        $enfantQuizs->setEnfant($this);
		$this->enfantQuizs[] = $enfantQuizs;

        return $this;
    }

    /**
     * Remove enfantQuizs
     *
     * @param \ChildConnect\CCSoftBundle\Entity\EnfantQuiz $enfantQuizs
     */
    public function removeEnfantQuiz(\ChildConnect\CCSoftBundle\Entity\EnfantQuiz $enfantQuizs)
    {
        $this->enfantQuizs->removeElement($enfantQuizs);
    }

    /**
     * Get enfantQuizs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEnfantQuizs()
    {
        return $this->enfantQuizs;
    }
    

    /**
     * Add associations
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Association $associations
     * @return Enfant
     */
    public function addAssociation(\ChildConnect\CCSoftBundle\Entity\Association $associations)
    {
        //$associations->setEnfant($this);
		$this->associations[] = $associations;

        return $this;
    }

    /**
     * Remove associations
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Association $associations
     */
    public function removeAssociation(\ChildConnect\CCSoftBundle\Entity\Association $associations)
    {
        $this->associations->removeElement($associations);
    }

    /**
     * Get associations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssociations()
    {
        return $this->associations;
    }
	
	
	public function getQuizs() {
		$quizs = array();
	
		foreach($this->getEnfantQuizs() as $enfantQuiz)
			$quizs[] = $enfantQuiz->getQuiz();			
		
		return $quizs;
	}



  

    /**
     * Add events
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Event $events
     * @return Enfant
     */
    public function addEvent(\ChildConnect\CCSoftBundle\Entity\Event $events)
    {
        $events->setEnfant($this);
		$this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Event $events
     */
    public function removeEvent(\ChildConnect\CCSoftBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }


    /**
     * Add lieux
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Lieu $lieux
     * @return Enfant
     */
    public function addLieux(\ChildConnect\CCSoftBundle\Entity\Lieu $lieux)
    {
       $lieux->setEnfant($this);
	    $this->lieux[] = $lieux;

        return $this;
    }

    /**
     * Remove lieux
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Lieu $lieux
     */
    public function removeLieux(\ChildConnect\CCSoftBundle\Entity\Lieu $lieux)
    {
        $this->lieux->removeElement($lieux);
    }

    /**
     * Get lieux
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLieux()
    {
        return $this->lieux;
    }

    /**
     * Set codeEnfant
     *
     * @param \ChildConnect\CCSoftBundle\Entity\CodeEnfant $codeEnfant
     * @return Enfant
     */
    public function setCodeEnfant(\ChildConnect\CCSoftBundle\Entity\CodeEnfant $codeEnfant = null)
    {
        $this->codeEnfant = $codeEnfant;

        return $this;
    }

    /**
     * Get codeEnfant
     *
     * @return \ChildConnect\CCSoftBundle\Entity\CodeEnfant 
     */
    public function getCodeEnfant()
    {
        return $this->codeEnfant;
    }

    /**
     * Set paysOrigine
     *
     * @param string $paysOrigine
     * @return Enfant
     */
    public function setPaysOrigine($paysOrigine)
    {
        $this->paysOrigine = $paysOrigine;

        return $this;
    }

    /**
     * Get paysOrigine
     *
     * @return string 
     */
    public function getPaysOrigine()
    {
        return $this->paysOrigine;
    }

    /**
     * Set villeOrigine
     *
     * @param string $villeOrigine
     * @return Enfant
     */
    public function setVilleOrigine($villeOrigine)
    {
        $this->villeOrigine = $villeOrigine;

        return $this;
    }

    /**
     * Get villeOrigine
     *
     * @return string 
     */
    public function getVilleOrigine()
    {
        return $this->villeOrigine;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Enfant
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
     * Set sexe
     *
     * @param string $sexe
     * @return Enfant
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string 
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Enfant
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Enfant
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Add photo
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Photo $photo
     * @return Enfant
     */
    public function addPhoto(\ChildConnect\CCSoftBundle\Entity\Photo $photo)
    {
        $photo->setEnfant($this);
		$this->photo[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Photo $photo
     */
    public function removePhoto(\ChildConnect\CCSoftBundle\Entity\Photo $photo)
    {
        $this->photo->removeElement($photo);
    }

    /**
     * Get photo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

   


    /**
     * Set dateEntree
     *
     * @param \DateTime $dateEntree
     * @return Enfant
     */
    public function setDateEntree($dateEntree)
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    /**
     * Get dateEntree
     *
     * @return \DateTime 
     */
    public function getDateEntree()
    {
        return $this->dateEntree;
    }

    /**
     * Set dateSortie
     *
     * @param \DateTime $dateSortie
     * @return Enfant
     */
    public function setDateSortie($dateSortie)
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    /**
     * Get dateSortie
     *
     * @return \DateTime 
     */
    public function getDateSortie()
    {
        return $this->dateSortie;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     * @return Enfant
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime 
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lieux = new \Doctrine\Common\Collections\ArrayCollection();
        $this->enfantQuizs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->associations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->photo = new \Doctrine\Common\Collections\ArrayCollection();
		$this->createdAt = new \Datetime;
		$this->modifiedAt = new \Datetime;
    }

  
	 public function getLabel()
    {
        return (($this->prenom) ? $this->prenom.' ' : '' ) . (($this->nom) ? $this->nom.' ' : '' ) . (($this->surnom) ? ' / '.$this->surnom : '' )  ;
    }

    /**
     * Set suiviPar
     *
     * @param string $suiviPar
     * @return Enfant
     */
    public function setSuiviPar($suiviPar)
    {
        $this->suiviPar = $suiviPar;

        return $this;
    }

    /**
     * Get suiviPar
     *
     * @return string 
     */
    public function getSuiviPar()
    {
        return $this->suiviPar;
    }

    /**
     * Set associationBase
     *
     * @param integer $associationBase
     * @return Enfant
     */
    public function setAssociationBase($associationBase)
    {
        $this->associationBase = $associationBase;

        return $this;
    }

    /**
     * Get associationBase
     *
     * @return integer 
     */
    public function getAssociationBase()
    {
        return $this->associationBase;
    }
}
