<?php

namespace ESSABA\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NotificationMeta
 *
 * @ORM\Table(name="notification_meta")
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\NotificationMetaRepository")
 */
class NotificationMeta
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
   * @ORM\ManyToOne(targetEntity="ESSABA\AnnonceBundle\Entity\Notification", inversedBy="notificationMetas", cascade={"persist"})
   * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
   */
    private $notification;

    /**
     * @var string
     *
     * @ORM\Column(name="cle", type="string", length=255)
     */
    private $cle;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur", type="text")
     */
    private $valeur;


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
     * Set cle
     *
     * @param string $cle
     *
     * @return NotificationMeta
     */
    public function setCle($cle)
    {
        $this->cle = $cle;

        return $this;
    }

    /**
     * Get cle
     *
     * @return string
     */
    public function getCle()
    {
        return $this->cle;
    }

    /**
     * Set valeur
     *
     * @param string $valeur
     *
     * @return NotificationMeta
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set notification
     *
     * @param \ESSABA\AnnonceBundle\Entity\Notification $notification
     *
     * @return NotificationMeta
     */
    public function setNotification(\ESSABA\AnnonceBundle\Entity\Notification $notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return \ESSABA\AnnonceBundle\Entity\Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }
}
