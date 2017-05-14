<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UtilisateurRepository")
 */
class Utilisateur extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var dateTime
     *
     * @ORM\Column(name="date_deco", type="datetime", nullable=true)
     */
    private $dateDeco;

    /**
   * @ORM\ManyToMany(targetEntity="ESSABA\AnnonceBundle\Entity\Annonce")
   * @ORM\JoinTable(name="coeurs")
   */
    private $coeurs;

    /**
   * @ORM\OneToMany(targetEntity="ESSABA\AnnonceBundle\Entity\Annonce", mappedBy="utilisateur")
   * @ORM\JoinTable(name="coeurs")
   */
    private $annonces;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=10, nullable=true)
     * @Assert\Regex(pattern="/^0[1-68]([-. ]?[0-9]{2}){4}$/", message="Le numéro de téléphone n'est pas valide.",  groups={"Registration", "Profile"})
     */
    private $tel;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrAnnonce", type="integer", nullable=true)
     */
    protected $nbrAnnonce = 0; 

    /**
     * @var int
     *
     * @ORM\Column(name="nbrVendu", type="integer", nullable=true)
     */
    protected $nbrVendu = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrBon", type="integer", nullable=true)
     */
    protected $nbrBon = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrMauvais", type="integer", nullable=true)
     */
    protected $nbrMauvais = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrAcheter", type="integer", nullable=true)
     */
    protected $nbrAcheter = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=50, nullable=true)
     */
    private $image;

    public function isCoeur($annonce)
    {
        foreach ($this->getCoeurs() as $ano) {
            if($ano == $annonce)
            {
                return true;
            }
        }
        return false;
    }

    public function isAnnonceur($annonce)
    {
        return $annonce->getUtilisateur() == $this;
    }

    public function estConnecte()
    {
        return ($this->dateDeco == null && $this->lastLogin != null ) || ( $this->dateDeco != null && $this->lastLogin > $this->dateDeco ) ;
    }


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
     * Set tel
     *
     * @param string $tel
     *
     * @return Utilisateur
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Utilisateur
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add coeur
     *
     * @param \ESSABA\AnnonceBundle\Entity\Annonce $coeur
     *
     * @return Utilisateur
     */
    public function addCoeur(\ESSABA\AnnonceBundle\Entity\Annonce $coeur)
    {
        $this->coeurs[] = $coeur;

        return $this;
    }

    /**
     * Remove coeur
     *
     * @param \ESSABA\AnnonceBundle\Entity\Annonce $coeur
     */
    public function removeCoeur(\ESSABA\AnnonceBundle\Entity\Annonce $coeur)
    {
        $this->coeurs->removeElement($coeur);
    }

    /**
     * Get coeurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoeurs()
    {
        return $this->coeurs;
    }

    /**
     * Set nbrVendu
     *
     * @param integer $nbrVendu
     *
     * @return Utilisateur
     */
    public function setNbrVendu($nbrVendu)
    {
        $this->nbrVendu = $nbrVendu;

        return $this;
    }

    /**
     * Get nbrVendu
     *
     * @return integer
     */
    public function getNbrVendu()
    {
        return $this->nbrVendu;
    }

    public function incrementeNbrAnnonce()
    {
        $this->nbrAnnonce = $this->nbrAnnonce + 1;
        return $this;
    }

    public function incrementeNbrVendu()
    {
        $this->nbrVendu = $this->nbrVendu + 1;
        return $this;
    }

    public function incrementeNbrAcheter()
    {
        $this->nbrAcheter = $this->nbrAcheter + 1;
        return $this;
    }

    public function incrementeNbrbon()
    {
        $this->nbrBon = $this->nbrBon + 1;
        return $this;
    }

    public function incrementeNbrMauvais()
    {
        $this->nbrMauvais = $this->nbrMauvais + 1;
        return $this;
    }

    /**
     * Set nbrAcheter
     *
     * @param integer $nbrAcheter
     *
     * @return Utilisateur
     */
    public function setNbrAcheter($nbrAcheter)
    {
        $this->nbrAcheter = $nbrAcheter;

        return $this;
    }

    /**
     * Get nbrAcheter
     *
     * @return integer
     */
    public function getNbrAcheter()
    {
        return $this->nbrAcheter;
    }

    /**
     * Set nbrBon
     *
     * @param integer $nbrBon
     *
     * @return Utilisateur
     */
    public function setNbrBon($nbrBon)
    {
        $this->nbrBon = $nbrBon;

        return $this;
    }

    /**
     * Get nbrBon
     *
     * @return integer
     */
    public function getNbrBon()
    {
        return $this->nbrBon;
    }

    /**
     * Set nbrMauvais
     *
     * @param integer $nbrMauvais
     *
     * @return Utilisateur
     */
    public function setNbrMauvais($nbrMauvais)
    {
        $this->nbrMauvais = $nbrMauvais;

        return $this;
    }

    /**
     * Get nbrMauvais
     *
     * @return integer
     */
    public function getNbrMauvais()
    {
        return $this->nbrMauvais;
    }

    /**
     * Add annonce
     *
     * @param \ESSABA\AnnonceBundle\Entity\Annonce $annonce
     *
     * @return Utilisateur
     */
    public function addAnnonce(\ESSABA\AnnonceBundle\Entity\Annonce $annonce)
    {
        $this->annonces[] = $annonce;

        return $this;
    }

    /**
     * Remove annonce
     *
     * @param \ESSABA\AnnonceBundle\Entity\Annonce $annonce
     */
    public function removeAnnonce(\ESSABA\AnnonceBundle\Entity\Annonce $annonce)
    {
        $this->annonces->removeElement($annonce);
    }

    /**
     * Get annonces
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnnonces()
    {
        return $this->annonces;
    }

    /**
     * Set nbrAnnonce
     *
     * @param integer $nbrAnnonce
     *
     * @return Utilisateur
     */
    public function setNbrAnnonce($nbrAnnonce)
    {
        $this->nbrAnnonce = $nbrAnnonce;

        return $this;
    }

    /**
     * Get nbrAnnonce
     *
     * @return integer
     */
    public function getNbrAnnonce()
    {
        return $this->nbrAnnonce;
    }

    /**
     * Set dateDeco
     *
     * @param \DateTime $dateDeco
     *
     * @return Utilisateur
     */
    public function setDateDeco($dateDeco)
    {
        $this->dateDeco = $dateDeco;

        return $this;
    }

    /**
     * Get dateDeco
     *
     * @return \DateTime
     */
    public function getDateDeco()
    {
        return $this->dateDeco;
    }
}
