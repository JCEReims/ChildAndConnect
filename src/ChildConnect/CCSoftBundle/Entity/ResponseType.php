<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResponseType
 *
 * @ORM\Table(name="responsetype")
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\ResponseTypeRepository")
 */
class ResponseType
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
     * @ORM\Column(name="type_field", type="string", length=255)
     */
    private $typeField;
	 /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
	
	/**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="max_response", type="smallint", length=3)
     */
    private $maxResponse;
	
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
     * Set name
     *
     * @param string $name
     * @return ResponseType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set maxResponse
     *
     * @param integer $maxResponse
     * @return ResponseType
     */
    public function setMaxResponse($maxResponse)
    {
        $this->maxResponse = $maxResponse;

        return $this;
    }

    /**
     * Get maxResponse
     *
     * @return integer 
     */
    public function getMaxResponse()
    {
        return $this->maxResponse;
    }

    /**
     * Set typeField
     *
     * @param string $typeField
     * @return ResponseType
     */
    public function setTypeField($typeField)
    {
        $this->typeField = $typeField;

        return $this;
    }

    /**
     * Get typeField
     *
     * @return string 
     */
    public function getTypeField()
    {
        return $this->typeField;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return ResponseType
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
	public function getLabel() {
		return $this->name.' - ' .$this->type;
	}
}
