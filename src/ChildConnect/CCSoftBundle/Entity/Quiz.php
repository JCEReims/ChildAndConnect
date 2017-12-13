<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Type;

/**
 * Quiz
 *
 * @ORM\Table(name="quiz")
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\QuizRepository")
 */
class Quiz
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
	
	/**
   * @ORM\OneToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\EnfantQuiz", mappedBy="quiz", cascade={"persist", "remove"}, orphanRemoval=TRUE)
   */
  private $enfantQuizs;

	/**
     * @ORM\OneToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\Question", mappedBy="quiz", cascade={"all"} , orphanRemoval=true )
	 *  @ORM\OrderBy({"ordre" = "ASC", "id" = "ASC"})
     */
    private $question;
	
  
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
     * @return Quiz
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
     * Add question
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Question $question
     * @return Quiz
     */
    public function addQuestion(\ChildConnect\CCSoftBundle\Entity\Question $question)
    {
        $question->setQuiz($this);
		$this->question[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Question $question
     */
    public function removeQuestion(\ChildConnect\CCSoftBundle\Entity\Question $question)
    {
        $this->question->removeElement($question);
    }

    /**
     * Get question
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestion()
    {
        return $this->question;
    }
  




    /**
     * Add enfantQuizs
     *
     * @param \ChildConnect\CCSoftBundle\Entity\EnfantQuiz $enfantQuizs
     * @return Quiz
     */
    public function addEnfantQuiz(\ChildConnect\CCSoftBundle\Entity\EnfantQuiz $enfantQuizs)
    {
        $enfantQuizs->setQuiz($this);
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
     * Constructor
     */
    public function __construct()
    {
        $this->enfantQuizs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->question = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
