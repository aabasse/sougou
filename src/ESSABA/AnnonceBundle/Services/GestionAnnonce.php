<?php
namespace ESSABA\AnnonceBundle\Services;

use ESSABA\AnnonceBundle\Entity\Voiture;
use ESSABA\AnnonceBundle\Entity\Moto;
use ESSABA\AnnonceBundle\Entity\VenteImobiliere;
use ESSABA\AnnonceBundle\Entity\Location;
use ESSABA\AnnonceBundle\Entity\Chaussure;
use ESSABA\AnnonceBundle\Entity\Vetement;
use ESSABA\AnnonceBundle\Entity\Emploi;
/**
* 
*/
class GestionAnnonce
{
	public function getClassNameAnnonce($sousCategorieId)
    {
        switch ($sousCategorieId) {
            case 10:
                return "Chaussure";
                break;
            case 11:
                return "Vetement";
                break;
            case 50:
                return "Voiture";
                break;
            case 51:
                return "Moto";
                break;
            case 60:
                return "Location";
                break;
            case 61:
                return "VenteImobiliere";
                break;
            case 80:
                return "Emploi";
                break;
            default:
                return "Annonce";
                break;
        }
    }

    public function getSubmitClassNameAnnonce($request)
    {
        if($request->request->get('chaussure') != null)
        {
            return 'Chaussure';
        }
        if($request->request->get('vetement') != null)
        {
            return 'Vetement';
        }
        if($request->request->get('moto') != null)
        {
            return 'Moto';
        }
        elseif ($request->request->get('voiture') != null) {
            return 'Voiture';
        }
        elseif ($request->request->get('location') != null) {
            return 'Location';
        }
        elseif ($request->request->get('vente_imobiliere') != null) {
            return 'VenteImobiliere';
        }
        elseif ($request->request->get('emploi') != null) {
            return 'Emploi';
        }
        return 'Annonce';
    }

    public function getClassNameByAnnonce($annonce)
    {
        if($annonce instanceof Chaussure){return 'Chaussure';}
        if($annonce instanceof Vetement){return 'Vetement';}
        if($annonce instanceof Voiture){return 'Voiture';}
        if($annonce instanceof Moto){return 'Moto';}
        if($annonce instanceof Location){return 'Location';}
        if($annonce instanceof VenteImobiliere){return 'VenteImobiliere';}
        if($annonce instanceof Emploi){return 'Emploi';}
        return 'Annonce';
    }

    public function getClassNameForFile($className)
    {
        if($className == 'VenteImobiliere')
        {
            return 'vente_imobiliere';
        }
        return lcfirst($className);
    }
}

?>