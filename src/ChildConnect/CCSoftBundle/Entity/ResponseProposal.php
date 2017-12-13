<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResponseProposal
 *
 * @ORM\Table(name="responseproposal")
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\ResponseProposalRepository")
 */
class ResponseProposal
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
     * @ORM\Column(name="response", type="string", length=255)
     */
    private $response;
	
	/**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255,nullable=true)
     */
    private $value;
	
	/**
     * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\Question", inversedBy="responseProposal")
	 * 
     */
    private $question;
	
	/**
     * @var smallint
     *
     * @ORM\Column(name="ordre", type="smallint",  nullable=true)
     */
    private $ordre;
	
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
     * Set response
     *
     * @param string $response
     * @return ResponseProposal
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return string 
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set question
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Question $question
     * @return ResponseProposal
     */
    public function setQuestion(\ChildConnect\CCSoftBundle\Entity\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \ChildConnect\CCSoftBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }



    /**
     * Set value
     *
     * @param string $value
     * @return ResponseProposal
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return ResponseProposal
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }
}
