<?php

namespace ESSABA\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\NotificationRepository")
 */
class Notification
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
   * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur")
   * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
   */
    private $utilisateur;

    /**
       * @ORM\OneToMany(targetEntity="ESSABA\AnnonceBundle\Entity\NotificationMeta", mappedBy="notification", cascade={"persist"})
       */
      private $notificationMetas;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var string
     *
     * @ORM\Column(name="lien", type="string", length=255, nullable=true)
     */
    private $lien;

    /**
    *@ORM\Column(name="est_vu", type="boolean")
    */
    private $vu = false;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true, columnDefinition="enum('message', 'trophe', 'confirme_demande')")
     */
    private $type;

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
     * @return Notification
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
     * Set lien
     *
     * @param string $lien
     *
     * @return Notification
     */
    public function setLien($lien)
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return string
     */
    public function getLien()
    {
        return $this->lien;
    }

    /**
     * Set vu
     *
     * @param boolean $vu
     *
     * @return Notification
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
     * Set utilisateur
     *
     * @param \UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Notification
     */
    public function setUtilisateur(\UserBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Notification
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
     * Constructor
     */
    public function __construct()
    {
        $this->notificationMetas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add notificationMeta
     *
     * @param \ESSABA\AnnonceBundle\Entity\NotificationMeta $notificationMeta
     *
     * @return Notification
     */
    public function addNotificationMeta(\ESSABA\AnnonceBundle\Entity\NotificationMeta $notificationMeta)
    {
        $this->notificationMetas[] = $notificationMeta;

        return $this;
    }

    /**
     * Remove notificationMeta
     *
     * @param \ESSABA\AnnonceBundle\Entity\NotificationMeta $notificationMeta
     */
    public function removeNotificationMeta(\ESSABA\AnnonceBundle\Entity\NotificationMeta $notificationMeta)
    {
        $this->notificationMetas->removeElement($notificationMeta);
    }

    /**
     * Get notificationMetas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotificationMetas()
    {
        return $this->notificationMetas;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
