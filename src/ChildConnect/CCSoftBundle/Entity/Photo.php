<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\PhotoRepository")
 */
class Photo
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
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

	/**
     *
     * @ORM\ManyToOne(targetEntity="ChildConnect\CCSoftBundle\Entity\Enfant", inversedBy="photo" )
	 * @ORM\JoinColumn(nullable=true)
     */
    private $enfant;
	
	/**
	 * @var string $file
     * @Assert\File(maxSize="5000k",mimeTypesMessage = "Merci d'uploader un fichier au format : JPG")
	 * @ORM\Column(name="file", type="string", length=255)
     */
    private $file;
	
	private $docExt = array('jpg');
	private $tmpFile;
	
	 /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
	
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
     * Set tmpFile
	 *
     * @return file
     */
    public function setTmpFile($tmpFile)
    {
        $this->tmpFile = $tmpFile;
    
        return $this;
    }

    /**
     * Get tmpFile
     *
     * @return file 
     */
    public function getTmpFile()
    {
        return $this->tmpFile;
    }
	
	/**
     * @ORM\PrePersist()
     */
    public function preUpload()
    {
       		
	      if (null === $this->file) {
            return false;
      	  }
		
		$extension = $this->get_file_extension($this->file->getClientOriginalName()) ;
	
		
		if (!$extension || !in_array(  $extension  ,$this->docExt)) {
			// l'extension n'a pas été trouvée
			//$extension = 'bin';
			return false;
		}
		
		if (null !== $this->file) {
			
            // faites ce que vous voulez pour générer un nom unique
            $this->path = sha1(uniqid(mt_rand(), true)).'.'.$extension;
			$this->nom = $this->file->getClientOriginalName();
			
        }
		 
		// s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
		 if(!$this->id){
        	$this->file->move($this->getTmpUploadRootDir(), $this->path);
		 } else {
			 $this->file->move($this->getUploadRootDir(), $this->path);
		 }
		 $this->setTmpFile($this->file);
		$this->setFile($this->file->getClientOriginalName());
		
    }

    /**
     * @ORM\PostPersist()
     */
    public function postUpload()
    {

		if (null === $this->tmpFile) {
            return false;
        }
		 

		if(!is_dir( $this->getUploadRootDir().'/'.$this->getEnfant()->getId() )){
            mkdir($this->getUploadRootDir().'/'.$this->getEnfant()->getId() );
        }
		
		
		copy($this->getTmpUploadRootDir().'/'.$this->path, $this->getUploadRootDir().'/'.$this->getEnfant()->getId().'/'.$this->path );
		
		$fullFileName= $this->getUploadRootDir().'/'.$this->getEnfant()->getId().'/'.$this->path;
		$extension = $this->get_file_extension($fullFileName) ;
		$tmpName = $this->path;
			
		$ImageChoisie = imagecreatefromjpeg($fullFileName);
		$TailleImageChoisie = getimagesize($fullFileName);
		$NouvelleLargeur = 250; //Largeur choisie à 300 px mais modifiable

		$NouvelleHauteur = ( ($TailleImageChoisie[1] * (($NouvelleLargeur)/$TailleImageChoisie[0])) );

		$NouvelleImage = imagecreatetruecolor($NouvelleLargeur , $NouvelleHauteur);

		imagecopyresampled($NouvelleImage , $ImageChoisie  , 0,0, 0,0, $NouvelleLargeur, $NouvelleHauteur, $TailleImageChoisie[0],$TailleImageChoisie[1]);
		imagedestroy($ImageChoisie);
		@unlink($fullFileName);	
		imagejpeg($NouvelleImage , $this->getUploadRootDir().'/'.$this->getEnfant()->getId().'/'.$this->path, 100);

       
		unlink($this->getTmpUploadRootDir().'/'.$tmpName);	
        unset($this->file);
		
		return true;
    }

    /**
     * @ORM\PreRemove
     */
    public function removeUpload()
    {
        
		if ($this->file = $this->getAbsolutePath()) {
           	
			if(file_exists($this->file))
				unlink($this->file);
			$path = $this->getUploadRootDir().'/'.$this->getEnfant()->getId();
			if(is_dir($path)) {
				$objects = scandir($path);
				if(count($objects)<=2) {
					reset($objects);
					 rmdir( $this->getUploadRootDir().'/'.$this->getEnfant()->getId());
				}
			}
			
        }
    }
	public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->$this->getEnfant()->getId().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->$this->getEnfant()->getId().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
	
	protected function getTmpUploadRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../../web/uploads/tmp';
    }
	
    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/photos';
    }
	
	public function get_file_extension($file_name) {
	  return strtolower(substr(strrchr($file_name,'.'),1));
	}
	
	  /**
     * Set file
     *
     * @param string $file
     * @return Documents
     */
    public function setFile($file)
    {
        
		$this->file = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Photo
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set enfant
     *
     * @param \ChildConnect\CCSoftBundle\Entity\Enfant $enfant
     * @return Photo
     */
    public function setEnfant(\ChildConnect\CCSoftBundle\Entity\Enfant $enfant = null)
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
     * Set date
     *
     * @param \DateTime $date
     * @return Photo
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
     * Constructor
     */
    public function __construct()
    {
	   $this->date = new \Datetime;
	}
}
