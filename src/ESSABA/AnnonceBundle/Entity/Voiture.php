<?php

namespace ESSABA\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Voiture
 *
 * @ORM\Table(name="voiture")
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\VoitureRepository")
 */
class Voiture extends Annonce
{
    /**
     * @var string
     *
     * @ORM\Column(name="marque", type="string", length=20, nullable=true, columnDefinition="enum('renault', 'peugeot', 'citroen', 'autre')")
     * @Assert\Choice(callback = "getMarques", groups={"Default", "Edit"})
     */
    private $marque;

    /**
     * @var string
     *
     * @ORM\Column(name="boite_vitesse", type="string", length=255, nullable=true, columnDefinition="enum('manuelle', 'automatique')")
     */
    private $boiteVitesse;

    /**
     * @var string
     *
     * @ORM\Column(name="kilometrage", type="decimal", precision=10, scale=2, nullable=true)
     * @Assert\Regex(pattern = "/^\d+([,|.]\d{1,2})?$/", groups={"Default", "Edit"})
     */
    private $kilometrage;

    /**
     * @var string
     *
     * @ORM\Column(name="carburant", type="string", columnDefinition="enum('essence', 'diesel', 'GPL', 'electrique', 'autre')")
     */
    private $carburant;

    /**
     * Set marque
     *
     * @param string $marque
     *
     * @return Voiture
     */
    public function setMarque($marque)
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get marque
     *
     * @return string
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Set boiteVitesse
     *
     * @param string $boiteVitesse
     *
     * @return Voiture
     */
    public function setBoiteVitesse($boiteVitesse)
    {
        $this->boiteVitesse = $boiteVitesse;

        return $this;
    }

    /**
     * Get boiteVitesse
     *
     * @return string
     */
    public function getBoiteVitesse()
    {
        return $this->boiteVitesse;
    }

    /**
     * Set kilometrage
     *
     * @param string $kilometrage
     *
     * @return Voiture
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

    /**
     * Set carburant
     *
     * @param string $carburant
     *
     * @return Voiture
     */
    public function setCarburant($carburant)
    {
        $this->carburant = $carburant;

        return $this;
    }

    /**
     * Get carburant
     *
     * @return string
     */
    public function getCarburant()
    {
        return $this->carburant;
    }

    public static function getMarques()
    {
        return array('renault', 'peugeot', 'citroen', 'autre');
    }

    public static function getListCarburant()
    {
        return array('essence', 'diesel', 'GPL', 'electrique', 'autre');
    }

    public static function getListBoiteVitesse()
    {
        return array('manuelle', 'automatique');
    }
}

