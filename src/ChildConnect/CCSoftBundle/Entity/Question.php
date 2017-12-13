<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\QuestionRepository")
 */
class Question
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
     * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\Quiz", inversedBy="question")
	 * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;
	
	
	/**
     * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\ResponseType")
	 * 
     */
    private $responseType;

	/**
     * @ORM\OneToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\ResponseProposal", mappedBy="question" ,cascade={"all"} , orphanRemoval=true )
	 * @ORM\JoinColumn(nullable=true)
	 * 
     */
    private $responseProposal;
	
	/**
     * @ORM\OneToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\Response", mappedBy="question", cascade={"all"} )
	 * @ORM\JoinColumn(nullable=true)
	 * 
     */
    private $response;
	
	/**
     * @var smallint
     *
     * @ORM\Column(name="ordre", type="smallint", length=3, nullable=true)
     */
    private $ordre;
	
	 /**
     * @var boolean
     *
     * @ORM\Column(name="active_stats", type="boolean", nullable=true)
     */
    private $activeStats;
	
	 /**
     * @var boolean
     *
     * @ORM\Column(name="active_integration", type="boolean", nullable=true)
     */
    private $activeIntegration;
	
	
	/**
     * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\ThemeQuestion", inversedBy="question")
	 * @ORM\JoinColumn(nullable=false)
	 * @ORM\OrderBy({"ordre" = "ASC"})
     */
    private $themeQuestion;
	
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
     * @return Question
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
     * Set responseType
     *
     * @param \ChildConnect\CCSoftBundle\Entity\ResponseType $responseType
     * @return Question
     */
    public function setResponseType(\ChildConnect\CCSoftBundle\Entity\ResponseType $responseType = null)
    {
        $this->responseType = $responseType;

        return $this;
    }

    /**
     * Get responseType
     *
     * @return \ChildConnect\CCSoftBundle\Entity\ResponseType 
     */
    public function getResponseType()
    {
        return $this->responseType;
    }

    /**
     * Add responseProposal
     *
     * @param \ChildConnect\CCSoftBundle\Entity\ResponseProposal $responseProposal
     * @return Question
     */
    public function addResponseProposal(\ChildConnect\CCSoftBundle\Entity\ResponseProposal $responseProposal)
    {
       $responseProposal->setQuestion($this);
	    $this->responseProposal[] = $responseProposal;

        return $this;
    }

    /**
     * Remove responseProposal
     *
     * @param \ChildConnect\CCSoftBundle\Entity\ResponseProposal $responseProposal
     */
    public function removeResponseProposal(\ChildConnect\CCSoftBundle\Entity\ResponseProposal $responseProposal)
    {
        $this->responseProposal->removeElement($responseProposal);
    }

    /**
     * Get responseProposal
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResponseProposal()
    {
        return $this->responseProposal;
    }

    /**
     * Set quiz
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Quiz $quiz
     * @return Question
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
     * Add response
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Response $response
     * @return Question
     */
    public function addResponse(\ChildConnect\CCSoftBundle\Entity\Response $response)
    {
        $response->setQuestion($this);
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
     * Constructor
     */
    public function __construct()
    {
        $this->responseProposal = new \Doctrine\Common\Collections\ArrayCollection();
        $this->response = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return Question
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

    

    /**
     * Set activeStats
     *
     * @param boolean $activeStats
     * @return Question
     */
    public function setActiveStats($activeStats)
    {
        $this->activeStats = $activeStats;

        return $this;
    }

    /**
     * Get activeStats
     *
     * @return boolean 
     */
    public function getActiveStats()
    {
        return $this->activeStats;
    }

 

    /**
     * Set themeQuestion
     *
     * @param \ChildConnect\CCSoftBundle\Entity\ThemeQuestion $themeQuestion
     * @return Question
     */
    public function setThemeQuestion(\ChildConnect\CCSoftBundle\Entity\ThemeQuestion $themeQuestion = null)
    {
        $this->themeQuestion = $themeQuestion;

        return $this;
    }

    /**
     * Get themeQuestion
     *
     * @return \ChildConnect\CCSoftBundle\Entity\ThemeQuestion 
     */
    public function getThemeQuestion()
    {
        return $this->themeQuestion;
    }

    /**
     * Set activeIntegration
     *
     * @param boolean $activeIntegration
     * @return Question
     */
    public function setActiveIntegration($activeIntegration)
    {
        $this->activeIntegration = $activeIntegration;

        return $this;
    }

    /**
     * Get activeIntegration
     *
     * @return boolean 
     */
    public function getActiveIntegration()
    {
        return $this->activeIntegration;
    }
}
