<?php

namespace ESSABA\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vetement
 *
 * @ORM\Table(name="vetement")
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\VetementRepository")
 */
class Vetement extends Annonce
{
    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", length=255)
     */
    private $genre;

    /**
     * @var string
     *
     * @ORM\Column(name="taille", type="string", length=255, nullable=true)
     * @Assert\Choice(callback = "getListTaille", groups={"Default", "Edit"})
     */
    private $taille;

    /**
     * @var string
     *
     * @ORM\Column(name="couleur", type="string", length=255, nullable=true)
     * @Assert\Choice(callback = "getListCouleur", groups={"Default", "Edit"})
     */
    private $couleur;

    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return Vetement
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set taille
     *
     * @param string $taille
     *
     * @return Vetement
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Get taille
     *
     * @return string
     */
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * Set couleur
     *
     * @param string $couleur
     *
     * @return Vetement
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * Get couleur
     *
     * @return string
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    public static function getListCouleur()
    {
        return array('noir', 'blanc', 'gris', 'rose', 'jaune', 'marront', 'rouge', 'orange', 'vert', 'bleux', 'violet');
    }

    public static function getListTaille()
    {
        return array('32', '34', '36', '38', '40', '42', '44', '46', '48', '50 et plus',
            'XS', 'S', 'M', 'L', 'XL', 'XXL et plus',
            '3 ans', '4 ans', '5 ans', '6 ans', '7 ans', '8 ans', '10 ans', '12 ans', '14 ans', '16 ans', '18 ans');
    }

    public static function getListGenre()
    {
        return array('femme', 'homme', 'enfant', 'bébé');
    }
}

