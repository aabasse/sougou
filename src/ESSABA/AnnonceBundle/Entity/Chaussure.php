<?php

namespace ESSABA\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Chaussure
 *
 * @ORM\Table(name="chaussure")
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\ChaussureRepository")
 */
class Chaussure extends Annonce
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
     * @ORM\Column(name="pointure", type="string", length=255)
     * @Assert\Choice(callback = "getListPointure", groups={"Default", "Edit"})
     */
    private $pointure;

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
     * @return Chaussure
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
     * Set pointure
     *
     * @param string $pointure
     *
     * @return Chaussure
     */
    public function setPointure($pointure)
    {
        $this->pointure = $pointure;

        return $this;
    }

    /**
     * Get pointure
     *
     * @return string
     */
    public function getPointure()
    {
        return $this->pointure;
    }

    /**
     * Set couleur
     *
     * @param string $couleur
     *
     * @return Chaussure
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

    public static function getListPointure()
    {
        for ($i=16; $i <= 50; $i++) { 
            $pointures[] = $i;
        }
        $pointures[] = "plus de 50";
        return $pointures;
    }
}

