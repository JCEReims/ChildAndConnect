<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Logs
 *
 * @ORM\Table(name="logs")
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\LogsRepository")
 */
class Logs
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255)
     */
    private $action;
	/**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", length=255)
     */
    private $entity;
	/**
     * @var string
     *
     * @ORM\Column(name="fields", type="text",nullable=true)
     */
    private $fields;

	/**
     * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\User")
	 * 
     */
    private $user;
	/**
     * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\Enfant")
	 * 
     */
    private $Enfant;
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
     * @return Logs
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
     * Set action
     *
     * @param string $action
     * @return Logs
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set user
     *
     * @param \ChildConnect\CCSoftBundle\Entity\User $user
     * @return Logs
     */
    public function setUser(\ChildConnect\CCSoftBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ChildConnect\CCSoftBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set Enfant
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Enfant $enfant
     * @return Logs
     */
    public function setEnfant(\ChildConnect\CCSoftBundle\Entity\Enfant $enfant = null)
    {
        $this->Enfant = $enfant;

        return $this;
    }

    /**
     * Get Enfant
     *
     * @return \ChildConnect\CCSoftBundle\Entity\Enfant 
     */
    public function getEnfant()
    {
        return $this->Enfant;
    }

    /**
     * Set entity
     *
     * @param string $entity
     * @return Logs
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return string 
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set fields
     *
     * @param string $fields
     * @return Logs
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Get fields
     *
     * @return string 
     */
    public function getFields()
    {
        return $this->fields;
    }
}
