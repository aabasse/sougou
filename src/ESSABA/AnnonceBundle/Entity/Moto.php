<?php

namespace ESSABA\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Moto
 *
 * @ORM\Table(name="moto")
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\MotoRepository")
 */
class Moto extends Annonce
{

    /**
     * @var string
     *
     * @ORM\Column(name="kilometrage", type="decimal", precision=10, scale=2, nullable=true)
     * @Assert\Regex(pattern = "/^\d+([,|.]\d{1,2})?$/", groups={"Default", "Edit"})
     */
    private $kilometrage;

    /**
     * Set kilometrage
     *
     * @param string $kilometrage
     *
     * @return Moto
     */
    public function setKilometrage($kilometrage)
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    /**
     * Get kilometrage
     *
     * @return string
     */
    public function getKilometrage()
    {
        return $this->kilometrage;
    }
}

