<?php

namespace ESSABA\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\PhotoRepository")
 */
class Photo
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
   * @ORM\ManyToOne(targetEntity="ESSABA\AnnonceBundle\Entity\Annonce", inversedBy="photos", cascade={"persist"})
   * @ORM\JoinColumn(nullable=false, name="annonce_id", referencedColumnName="id", onDelete="CASCADE")
   */
    private $annonce;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\File(maxSize = "7M", mimeTypes = {"image/gif", "image/jpeg", "image/pjpeg", "image/png"})
     */
    private $nom;

    /**
     * @var bool
     *
     * @ORM\Column(name="estPrincipal", type="boolean")
     */
    private $estPrincipal = false;


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
     * Set estPrincipal
     *
     * @param boolean $estPrincipal
     *
     * @return Photo
     */
    public function setEstPrincipal($estPrincipal)
    {
        $this->estPrincipal = $estPrincipal;

        return $this;
    }

    /**
     * Get estPrincipal
     *
     * @return bool
     */
    public function getEstPrincipal()
    {
        return $this->estPrincipal;
    }

    /**
     * Set annonce
     *
     * @param \ESSABA\AnnonceBundle\Entity\Annonce $annonce
     *
     * @return Photo
     */
    public function setAnnonce(\ESSABA\AnnonceBundle\Entity\Annonce $annonce)
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Photo
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
}
