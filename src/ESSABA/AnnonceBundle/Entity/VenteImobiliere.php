<?php

namespace ESSABA\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * VenteImobiliere
 *
 * @ORM\Table(name="vente_imobiliere")
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\VenteImobiliereRepository")
 */
class VenteImobiliere extends Annonce
{
    /**
     * @var int
     *
     * @ORM\Column(name="surface", type="decimal", precision=10, scale=2, nullable=true)
     * @Assert\Regex(pattern = "/^\d+([,|.]\d{1,2})?$/", groups={"Default", "Edit"})
     */
    private $surface;

    /**
     * @var int
     *
     * @ORM\Column(name="piece", type="integer", nullable=true)
     * @Assert\Regex(pattern = "/^\d+$/", groups={"Default", "Edit"})
     */
    private $piece;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, columnDefinition="enum('maison', 'appartement', 'terrain', 'parking', 'autre')")
     * @Assert\NotBlank(groups={"Default", "Edit"})
     */
    private $type;

    /**
     * Set surface
     *
     * @param integer $surface
     *
     * @return VenteImobiliere
     */
    public function setSurface($surface)
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * Get surface
     *
     * @return int
     */
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * Set piece
     *
     * @param integer $piece
     *
     * @return VenteImobiliere
     */
    public function setPiece($piece)
    {
        $this->piece = $piece;

        return $this;
    }

    /**
     * Get piece
     *
     * @return int
     */
    public function getPiece()
    {
        return $this->piece;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return VenteImobiliere
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

    public static function getTypes()
    {
        return array('maison', 'appartement', 'terrain', 'parking', 'autre');
    }
}

