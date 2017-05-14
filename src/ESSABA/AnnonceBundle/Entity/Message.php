<?php

namespace ESSABA\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\MessageRepository")
 */
class Message
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
   * @ORM\ManyToOne(targetEntity="ESSABA\AnnonceBundle\Entity\Conversation")
   * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
   * @Assert\Type(type="ESSABA\AnnonceBundle\Entity\Conversation")
   */
    private $conversation;

    /**
   * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur")
   * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
   * @Assert\Type(type="UserBundle\Entity\Utilisateur")
   * @Assert\Valid()
   */
    private $expediteur;
    

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;
    

    /**
    *@ORM\Column(name="est_vu", type="boolean", nullable=true)
    */
    private $vu = false;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;


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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Message
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }


    /**
     * Set vu
     *
     * @param boolean $vu
     *
     * @return Message
     */
    public function setVu($vu)
    {
        $this->vu = $vu;

        return $this;
    }

    /**
     * Get vu
     *
     * @return boolean
     */
    public function getVu()
    {
        return $this->vu;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Message
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }


    /**
     * Set conversation
     *
     * @param \ESSABA\AnnonceBundle\Entity\Conversation $conversation
     *
     * @return Message
     */
    public function setConversation(\ESSABA\AnnonceBundle\Entity\Conversation $conversation)
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * Get conversation
     *
     * @return \ESSABA\AnnonceBundle\Entity\Conversation
     */
    public function getConversation()
    {
        return $this->conversation;
    }

    /**
     * Set expediteur
     *
     * @param \UserBundle\Entity\Utilisateur $expediteur
     *
     * @return Message
     */
    public function setExpediteur(\UserBundle\Entity\Utilisateur $expediteur = null)
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    /**
     * Get expediteur
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getExpediteur()
    {
        return $this->expediteur;
    }
}
