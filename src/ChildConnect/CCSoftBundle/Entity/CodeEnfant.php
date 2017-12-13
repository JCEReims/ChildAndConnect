<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CodeEnfant
 *
 * @ORM\Table(name="codeenfant")
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\CodeEnfantRepository")
 */
class CodeEnfant
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var boolean
     *
     * @ORM\Column(name="locked", type="boolean")
     */
    private $locked;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="locked_at", type="datetime", nullable=true)
     */
    private $lockedAt;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
	
	/**
     * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\Association", inversedBy="codeEnfant", cascade={"all"})
	 * @ORM\JoinColumn( nullable=false)
     */
    private $association;
	
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
     * Set code
     *
     * @param string $code
     * @return CodeEnfant
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     * @return CodeEnfant
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean 
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set association
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Association $association
     * @return CodeEnfant
     */
    public function setAssociation(\ChildConnect\CCSoftBundle\Entity\Association $association)
    {
        $this->association = $association;

        return $this;
    }

    /**
     * Get association
     *
     * @return \ChildConnect\CCSoftBundle\Entity\Association 
     */
    public function getAssociation()
    {
        return $this->association;
    }

    /**
     * Set lockedAt
     *
     * @param \DateTime $lockedAt
     * @return CodeEnfant
     */
    public function setLockedAt($lockedAt)
    {
        $this->lockedAt = $lockedAt;

        return $this;
    }

    /**
     * Get lockedAt
     *
     * @return \DateTime 
     */
    public function getLockedAt()
    {
        return $this->lockedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return CodeEnfant
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
     * Constructor
     */
    public function __construct()
    {
	   $this->createdAt = new \Datetime;
	}
}
