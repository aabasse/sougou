<?php

namespace ESSABA\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\CategorieRepository")
 */
class Categorie
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
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer", nullable=true)
     */
    private $ordre;

    /**
   * @ORM\OneToMany(targetEntity="ESSABA\AnnonceBundle\Entity\SousCategorie", mappedBy="categorie")
   */
    private $sousCategories;


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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Categorie
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sousCategories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sousCategory
     *
     * @param \ESSABA\AnnonceBundle\Entity\SousCategorie $sousCategory
     *
     * @return Categorie
     */
    public function addSousCategory(\ESSABA\AnnonceBundle\Entity\SousCategorie $sousCategory)
    {
        $this->sousCategories[] = $sousCategory;

        return $this;
    }

    /**
     * Remove sousCategory
     *
     * @param \ESSABA\AnnonceBundle\Entity\SousCategorie $sousCategory
     */
    public function removeSousCategory(\ESSABA\AnnonceBundle\Entity\SousCategorie $sousCategory)
    {
        $this->sousCategories->removeElement($sousCategory);
    }

    /**
     * Get sousCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSousCategories()
    {
        return $this->sousCategories;
    }

    public function __toString()
    {
        return $this->libelle;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Categorie
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
     *
     * @return Categorie
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
}
