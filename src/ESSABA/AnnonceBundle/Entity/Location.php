<?php

namespace ESSABA\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\LocationRepository")
 */
class Location extends Annonce
{
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, columnDefinition="enum('maison', 'appartement', 'terrain', 'parking', 'autre')")
     * @Assert\NotBlank(groups={"Default", "Edit"})
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="surface", type="decimal", precision=10, scale=2, nullable=true)
     * @Assert\Regex(pattern = "/^\d+([,|.]\d{1,2})?$/", groups={"Default", "Edit"})
     */
    private $surface;

    /**
     * @var string
     *
     * @ORM\Column(name="meuble", type="string", length=255, columnDefinition="enum('meublé', 'non meublé')")
     * @Assert\NotBlank(groups={"Default", "Edit"})
     */
    private $meuble;

    /**
     * @var int
     *
     * @ORM\Column(name="piece", type="integer", nullable=true)
     * @Assert\Regex(pattern = "/^\d+$/", groups={"Default", "Edit"})
     */
    private $piece;

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Location
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

    /**
     * Set surface
     *
     * @param string $surface
     *
     * @return Location
     */
    public function setSurface($surface)
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * Get surface
     *
     * @return string
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
     * @return Location
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

    public static function getTypes()
    {
        return array('maison', 'appartement', 'terrain', 'parking', 'autre');
    }

    /**
     * Set meuble
     *
     * @param string $meuble
     *
     * @return Location
     */
    public function setMeuble($meuble)
    {
        $this->meuble = $meuble;

        return $this;
    }

    /**
     * Get meuble
     *
     * @return string
     */
    public function getMeuble()
    {
        return $this->meuble;
    }
}
