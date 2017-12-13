<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EnfantQuiz
 *
 * @ORM\Table(name="enfant_quiz")
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\EnfantQuizRepository")
 */
class EnfantQuiz
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
   * 
   * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\Enfant", inversedBy="enfantQuizs")
   * @ORM\JoinColumn(nullable=false)
   */
  private $enfant;

  /**
   * 
   * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\Quiz", inversedBy="enfantQuizs")
   * @ORM\JoinColumn(nullable=false)
   */
  private $quiz;

   /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_responded", type="datetime",nullable=true)
     */ 
    private $dateResponded;
	
	/**
     * @ORM\OneToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\Response", mappedBy="enfantQuiz", cascade={"all"} )
	 * @ORM\JoinColumn(nullable=false)
	 * 
     */
    private $response;
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
     * Set enfant
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Enfant $enfant
     * @return EnfantQuiz
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
     * Set quiz
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Quiz $quiz
     * @return EnfantQuiz
     */
    public function setQuiz(\ChildConnect\CCSoftBundle\Entity\Quiz $quiz)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \ChildConnect\CCSoftBundle\Entity\Quiz 
     */
    public function getQuiz()
    {
        return $this->quiz;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->response = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add response
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Response $response
     * @return EnfantQuiz
     */
    public function addResponse(\ChildConnect\CCSoftBundle\Entity\Response $response)
    {
        $response->setEnfantQuiz($this);
		$this->response[] = $response;

        return $this;
    }

    /**
     * Remove response
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Response $response
     */
    public function removeResponse(\ChildConnect\CCSoftBundle\Entity\Response $response)
    {
        $this->response->removeElement($response);
    }

    /**
     * Get response
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set dateResponded
     *
     * @param \DateTime $dateResponded
     * @return EnfantQuiz
     */
    public function setDateResponded($dateResponded)
    {
        $this->dateResponded = $dateResponded;

        return $this;
    }

    /**
     * Get dateResponded
     *
     * @return \DateTime 
     */
    public function getDateResponded()
    {
        return $this->dateResponded;
    }
}
