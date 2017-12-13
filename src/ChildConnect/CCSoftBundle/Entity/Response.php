<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Response
 *
 * @ORM\Table(name="response")
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\ResponseRepository")
 */
class Response
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
     * @ORM\Column(name="response_text", type="string", length=255, nullable=true)
     */
    private $responseText;
	
	/**
     * @var string
     *
     * @ORM\Column(name="response_textarea", type="text", nullable=true)
     */
    private $responseTextarea;
	
	/**
     * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\ResponseProposal")
	 * @ORM\JoinColumn(name="response_choice", referencedColumnName="id",nullable=true)
     */
	 private $responseChoice;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="date_response", type="datetime")
     */ 
    private $dateResponse;
	
	/**
     * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\Question", inversedBy="response")
	 * @ORM\JoinColumn( nullable=false)
     */
    private $question;
	
	/**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;
    
	 
	/**
   * 
   * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\EnfantQuiz" , inversedBy="response")
   * @ORM\JoinColumn(name="enfant_quiz_id", referencedColumnName="id",nullable=false)
   */
     private $enfantQuiz;

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
     * Set dateResponse
     *
     * @param \DateTime $dateResponse
     * @return Response
     */
    public function setDateResponse($dateResponse)
    {
        $this->dateResponse = $dateResponse;

        return $this;
    }

    /**
     * Get dateResponse
     *
     * @return \DateTime 
     */
    public function getDateResponse()
    {
        return $this->dateResponse;
    }

    /**
     * Set question
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Question $question
     * @return Response
     */
    public function setQuestion(\ChildConnect\CCSoftBundle\Entity\Question $question )
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
     * Set responseText
     *
     * @param string $responseText
     * @return Response
     */
    public function setResponseText($responseText)
    {
        $this->responseText = $responseText;

        return $this;
    }

    /**
     * Get responseText
     *
     * @return string 
     */
    public function getResponseText()
    {
        return $this->responseText;
    }

    /**
     * Set responseTextarea
     *
     * @param string $responseTextarea
     * @return Response
     */
    public function setResponseTextarea($responseTextarea)
    {
        $this->responseTextarea = $responseTextarea;

        return $this;
    }

    /**
     * Get responseTextarea
     *
     * @return string 
     */
    public function getResponseTextarea()
    {
        return $this->responseTextarea;
    }

 

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Response
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
     * Set responseChoice
     *
     * @param \ChildConnect\CCSoftBundle\Entity\ResponseProposal $responseChoice
     * @return Response
     */
    public function setResponseChoice(\ChildConnect\CCSoftBundle\Entity\ResponseProposal $responseChoice = null)
    {
        $this->responseChoice = $responseChoice;

        return $this;
    }

    /**
     * Get responseChoice
     *
     * @return \ChildConnect\CCSoftBundle\Entity\ResponseProposal 
     */
    public function getResponseChoice()
    {
        return $this->responseChoice;
    }

    /**
     * Set enfantQuiz
     *
     * @param \ChildConnect\CCSoftBundle\Entity\EnfantQuiz $enfantQuiz
     * @return Response
     */
    public function setEnfantQuiz(\ChildConnect\CCSoftBundle\Entity\EnfantQuiz $enfantQuiz)
    {
        $this->enfantQuiz = $enfantQuiz;

        return $this;
    }

    /**
     * Get enfantQuiz
     *
     * @return \ChildConnect\CCSoftBundle\Entity\EnfantQuiz 
     */
    public function getEnfantQuiz()
    {
        return $this->enfantQuiz;
    }
}
