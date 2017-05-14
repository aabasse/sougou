<?php

namespace ESSABA\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Conversation
 *
 * @ORM\Table(name="conversation")
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\ConversationRepository")
 */
class Conversation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
   * @ORM\ManyToOne(targetEntity="ESSABA\AnnonceBundle\Entity\Annonce")
   * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
   * @Assert\Type(type="ESSABA\AnnonceBundle\Entity\Annonce")
   * @Assert\Valid()
   */
    private $annonce;

    /**
   * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur")
   * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
   * @Assert\Type(type="UserBundle\Entity\Utilisateur")
   * @Assert\Valid()
   */
    private $utilisateur1;

    /**
   * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur")
   * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
   * @Assert\Type(type="UserBundle\Entity\Utilisateur")
   * @Assert\Valid()
   */
    private $utilisateur2;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=20, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=10, nullable=true)
     */
    private $tel;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set email
     *
     * @param string $email
     *
     * @return Conversation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Conversation
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Conversation
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set annonce
     *
     * @param \ESSABA\AnnonceBundle\Entity\Annonce $annonce
     *
     * @return Conversation
     */
    public function setAnnonce(\ESSABA\AnnonceBundle\Entity\Annonce $annonce = null)
    {
        $this->annonce = $annonce;

        return $this;
    }

    /**
     * Get annonce
     *
     * @return \ESSABA\AnnonceBundle\Entity\Annonce
     */
    public function getAnnonce()
    {
        return $this->annonce;
    }


    /**
     * Set utilisateur1
     *
     * @param \UserBundle\Entity\Utilisateur $utilisateur1
     *
     * @return Conversation
     */
    public function setUtilisateur1(\UserBundle\Entity\Utilisateur $utilisateur1 = null)
    {
        $this->utilisateur1 = $utilisateur1;

        return $this;
    }

    /**
     * Get utilisateur1
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getUtilisateur1()
    {
        return $this->utilisateur1;
    }

    /**
     * Set utilisateur2
     *
     * @param \UserBundle\Entity\Utilisateur $utilisateur2
     *
     * @return Conversation
     */
    public function setUtilisateur2(\UserBundle\Entity\Utilisateur $utilisateur2 = null)
    {
        $this->utilisateur2 = $utilisateur2;

        return $this;
    }

    /**
     * Get utilisateur2
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getUtilisateur2()
    {
        return $this->utilisateur2;
    }
}
