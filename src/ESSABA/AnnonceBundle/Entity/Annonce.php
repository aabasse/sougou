<?php

namespace ESSABA\AnnonceBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Annonce
 *
 * @ORM\Table(name="annonce")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"annonce" = "Annonce", "chaussure" = "Chaussure", "vetement" = "Vetement", "voiture" = "Voiture", "moto" = "Moto", "location" = "Location", "venteImobiliere" = "VenteImobiliere", "emploi" = "Emploi"})
 * @ORM\Entity(repositoryClass="ESSABA\AnnonceBundle\Repository\AnnonceRepository")
 */
class Annonce
{
    protected $cheminImage = "uploads/images/annonce/";
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    *@ORM\Column(name="est_offre", type="boolean")
    */
    private $estOffre = true;

    /**
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(length=60, unique=true)
     */
    private $slug;

    /**
   * @ORM\ManyToOne(targetEntity="ESSABA\AnnonceBundle\Entity\Commune")
   * @ORM\JoinColumn(nullable=false)
   */
    private $commune;

    /**
   * @ORM\ManyToOne(targetEntity="ESSABA\AnnonceBundle\Entity\SousCategorie")
   * @ORM\JoinColumn(nullable=false)
   * @Assert\NotBlank(groups={"Default", "Edit"})
   */
    private $sousCategorie;

    /**
   * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur", inversedBy="annonces", cascade={"persist"})
   * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
   * @Assert\Valid()
   */
    private $utilisateur;

    /**
   * @ORM\OneToMany(targetEntity="ESSABA\AnnonceBundle\Entity\Photo", mappedBy="annonce", cascade={"persist"})
   * @Assert\Valid()
   */
    private $photos;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=50)
     * @Assert\Length(min=2, max=50, groups={"Default", "Edit"})
     * @Assert\NotBlank(groups={"Default", "Edit"})
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="text", nullable=true)
     * @Assert\Length(min=10, max=500, groups={"Default", "Edit"})
     */
    private $detail;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=10, scale=2, nullable=true)
     * @Assert\Expression("this.getAutreMoyen() in ['à débattre', 'gratuit', 'me contacter', 'troc/Échange'] or value != '' or this.getSousCategorie().getId() in [80]", message="Saisissez un prix ou sélectionnez une valeur", groups={"Offre", "EditOffre"})
     * @Assert\Regex(pattern = "/^\d+([,|.]\d{1,2})?$/", groups={"Offre", "Edit"}) 
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="autre_moyen", type="string", length=50, nullable=true)
     * @Assert\Expression("value in ['à débattre', 'gratuit', 'me contacter', 'troc/Échange'] or this.getPrix() != '' or this.getSousCategorie().getId() in [80]", message="Sélectionnez une valeur ou saisissez un prix", groups={"Offre", "EditOffre"})
     */
    private $autreMoyen;


    /**
    *@ORM\Column(name="est_valide", type="boolean", nullable=true)
    */
    private $isValide = 0;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;



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
     * Set titre
     *
     * @param string $titre
     *
     * @return Annonce
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set detail
     *
     * @param string $detail
     *
     * @return Annonce
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return Annonce
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set commune
     *
     * @param \ESSABA\AnnonceBundle\Entity\Commune $commune
     *
     * @return Annonce
     */
    public function setCommune(\ESSABA\AnnonceBundle\Entity\Commune $commune = null)
    {
        $this->commune = $commune;

        return $this;
    }

    /**
     * Get commune
     *
     * @return \ESSABA\AnnonceBundle\Entity\Commune
     */
    public function getCommune()
    {
        return $this->commune;
    }

    /**
     * Set sousCategorie
     *
     * @param \ESSABA\AnnonceBundle\Entity\SousCategorie $sousCategorie
     *
     * @return Annonce
     */
    public function setSousCategorie(\ESSABA\AnnonceBundle\Entity\SousCategorie $sousCategorie = null)
    {
        $this->sousCategorie = $sousCategorie;

        return $this;
    }

    /**
     * Get sousCategorie
     *
     * @return \ESSABA\AnnonceBundle\Entity\SousCategorie
     */
    public function getSousCategorie()
    {
        return $this->sousCategorie;
    }



    /**
     * Set utilisateur
     *
     * @param \UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Annonce
     */
    public function setUtilisateur(\UserBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    public function getCheminImage(){
        return $this->cheminImage;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Annonce
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
     * Set isValide
     *
     * @param boolean $isValide
     *
     * @return Annonce
     */
    public function setIsValide($isValide)
    {
        $this->isValide = $isValide;

        return $this;
    }

    /**
     * Get isValide
     *
     * @return boolean
     */
    public function getIsValide()
    {
        return $this->isValide;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Annonce
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add photo
     *
     * @param \ESSABA\AnnonceBundle\Entity\Photo $photo
     *
     * @return Annonce
     */
    public function addPhoto(\ESSABA\AnnonceBundle\Entity\Photo $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \ESSABA\AnnonceBundle\Entity\Photo $photo
     */
    public function removePhoto(\ESSABA\AnnonceBundle\Entity\Photo $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    public function getPhotoPrincipal()
    {
        $photo = null;
        if(count($this->photos) > 0){
            $photo = $this->photos[0];
        }
        return $photo;
    }

    /**
     * Set estOffre
     *
     * @param boolean $estOffre
     *
     * @return Annonce
     */
    public function setEstOffre($estOffre)
    {
        $this->estOffre = $estOffre;

        return $this;
    }

    /**
     * Get estOffre
     *
     * @return boolean
     */
    public function getEstOffre()
    {
        return $this->estOffre;
    }

    public function __toString()
    {
        return $this->titre;
    }

    public static function getListAutreMoyens()
    {
        return array('à débattre', 'gratuit', 'me contacter', 'troc/Échange');
    }

    

    /**
     * Set autreMoyen
     *
     * @param string $autreMoyen
     *
     * @return Annonce
     */
    public function setAutreMoyen($autreMoyen)
    {
        $this->autreMoyen = $autreMoyen;

        return $this;
    }

    /**
     * Get autreMoyen
     *
     * @return string
     */
    public function getAutreMoyen()
    {
        return $this->autreMoyen;
    }
}
