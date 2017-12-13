<?php

namespace ChildConnect\CCSoftBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="groups")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="ChildConnect\CCSoftBundle\Entity\GroupRepository")
 */
class Group extends BaseGroup
{
	 /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
}
?>