<?php

namespace ESSABA\AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Emploi
 *
 * @ORM\Table(name="emploi")
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\EmploiRepository")
 */
class Emploi extends Annonce
{
    /*public function __construct()
    {
        $this->setPrix(0);
    }*/

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, columnDefinition="enum('CDI', 'CDD', 'stage', 'apprentissage / Alternance', 'temps plein', 'temps partiel', 'intérim', 'freelance / Indépendant')")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="metier", type="string", length=255)
     */
    private $metier;

    /**
     * @var string
     *
     * @ORM\Column(name="experience", type="text", nullable=true)
     */
    private $experience;

    /**
     * @var string
     *
     * @ORM\Column(name="profil", type="text", nullable=true)
     */
    private $profil;

    /**
     * @var string
     *
     * @ORM\Column(name="salaire", type="text", nullable=true)
     */
    private $salaire;

    /**
     * @var string
     *
     * @ORM\Column(name="formation", type="text", nullable=true)
     */
    private $formation;

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Emploi
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
     * Set metier
     *
     * @param string $metier
     *
     * @return Emploi
     */
    public function setMetier($metier)
    {
        $this->metier = $metier;

        return $this;
    }

    /**
     * Get metier
     *
     * @return string
     */
    public function getMetier()
    {
        return $this->metier;
    }

    /**
     * Set experience
     *
     * @param string $experience
     *
     * @return Emploi
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return string
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set profil
     *
     * @param string $profil
     *
     * @return Emploi
     */
    public function setProfil($profil)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil
     *
     * @return string
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * Set salaire
     *
     * @param string $salaire
     *
     * @return Emploi
     */
    public function setSalaire($salaire)
    {
        $this->salaire = $salaire;

        return $this;
    }

    /**
     * Get salaire
     *
     * @return string
     */
    public function getSalaire()
    {
        return $this->salaire;
    }

    /**
     * Set formation
     *
     * @param string $formation
     *
     * @return Emploi
     */
    public function setFormation($formation)
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * Get formation
     *
     * @return string
     */
    public function getFormation()
    {
        return $this->formation;
    }

    public static function getListTypes()
    {
        return array('CDI', 'CDD', 'stage', 'apprentissage / Alternance', 'temps plein', 'temps partiel', 'intérim', 'freelance / Indépendant');
    }

    public static function getListMetiers()
    {
        return array('Achats, Logistique',
                    'Administratif, Secrétariat',
                    'Agriculture, Agro, Pêche',
                    'Art, Design, Photo',
                    'Artisanat',
                    'Commercial, Ventes',
                    'Direction, Dirigeant',
                    'Enseignement, Formation',
                    'Gestion, Comptabilité, Audit',
                    'Grande Distribution',
                    'Immobilier, Construction',
                    'Industries, Production',
                    'Informatique, Internet',
                    'Ingénierie',
                    'Juridique, Conseil',
                    'Magasin, Petit Commerce',
                    'Marketing, Communication',
                    'Ressources humaines',
                    'Santé, Social',
                    'Sciences, Recherches',
                    'Secteur public',
                    'Service Clientèle, Centre d’Appel',
                    'Technologies, Télécoms',
                    'Tourisme, Restauration, Hôtellerie',
                    'Autres'
                );
    }
}

