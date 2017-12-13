<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThemeQuestion
 *
 * @ORM\Table(name="themequestion")
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\ThemeQuestionRepository")
 */
class ThemeQuestion
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
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;


	/**
     * @var smallint
     *
     * @ORM\Column(name="ordre", type="smallint", length=3, nullable=true)
     */
    private $ordre;
	
	/**
     * @ORM\OneToMany(targetEntity="ChildConnect\CCSoftBundle\Entity\Question", mappedBy="themeQuestion")
	 * @ORM\JoinColumn(nullable=false)
	 * @ORM\OrderBy({"ordre" = "ASC"})
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
     * @return ThemeQuestion
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
     * Set slug
     *
     * @param string $slug
     * @return ThemeQuestion
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return ThemeQuestion
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
     * Constructor
     */
    public function __construct()
    {
        $this->question = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add question
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Question $question
     * @return ThemeQuestion
     */
    public function addQuestion(\ChildConnect\CCSoftBundle\Entity\Question $question)
    {
        $question->setThemeQuestion($this);
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
}
