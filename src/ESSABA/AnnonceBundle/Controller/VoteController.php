<?php

namespace ESSABA\AnnonceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ESSABA\AnnonceBundle\Entity\Vote;
use Symfony\Component\HttpFoundation\JsonResponse;


class VoteController extends Controller
{
    public function voterAction(Request $request)
    {
    	//$aAretourner = array();
        $response = new JsonResponse();
        //$response->setData($aAretourner);
    	if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

    		$idAnnonce = $request->query->get('annonce');
    		$type = $request->query->get('t') == 'bon' || $request->query->get('t') == 'mauvais' || $request->query->get('t') == 'coeur' ? $request->query->get('t') : null;
	    	if($type != null && $idAnnonce != null)
	    	{
	    		$em = $this->getDoctrine()->getManager();
		    	$annonce = $em->getRepository('ESSABAAnnonceBundle:Annonce')->find($idAnnonce);

		    	if($annonce != null && $annonce->getUtilisateur() != $this->getUser())
		    	{

			    	$repVote = $em->getRepository('ESSABAAnnonceBundle:Vote');
			    	if($type == 'coeur')
			    	{
			    		$vote = $repVote->findOneBy(array('type'=>array('coeur'), 'utilisateur' => $this->getUser(), 'annonce' => $annonce));
			    	}
			    	else
			    	{
			    		$vote = $repVote->findOneBy(array('type'=>array('bon', 'mauvais'), 'utilisateur' => $this->getUser(), 'annonce' => $annonce));
			    	}
			    	
			    	//var_dump($vote);
			    	if($vote == null)
			    	{
			    		$vote = new Vote();
				    	$vote->setType($type);
				    	$vote->setUtilisateur($this->getUser());
				    	$vote->setAnnonce($annonce);

			    	}
			    	elseif($vote->getType() == $type)
			    	{
			    		if($type == 'coeur')
			    		{
							$repVote->supprimerCoeur($this->getUser(), $annonce);
			    		}
			    		else
			    		{
			    			$repVote->supprimer($this->getUser(), $annonce);
			    		}
			    		
			    		return $response;
			    	}
			    	else
			    	{
			    		$vote->setType($type);
			    	}

			    	$em->persist($vote);
					$em->flush();
				}
	    	}
	    }
        return $response; 
    }

    public function coupDeCoeurAction(Request $request)
    {
        $response = new JsonResponse();
    	if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

			$idAnnonce = $request->query->get('annonce');
    		$em = $this->getDoctrine()->getManager();
	    	$annonce = $em->getRepository('ESSABAAnnonceBundle:Annonce')->find($idAnnonce);
			
			if($annonce != null && $annonce->getUtilisateur() != $this->getUser())
	    	{
		    	$annonceSuprime = false;
		    	foreach ($this->getUser()->getCoeurs() as $ano) {
		    		if($ano == $annonce)
		    		{
		    			$this->getUser()->removeCoeur($annonce);
		    			$annonceSuprime = true;
		    			break;
		    		}
		    	}

		    	if(!$annonceSuprime)
		    	{
		    		$this->getUser()->addCoeur($annonce);
		    	}
				
				$em->persist($this->getUser());
				$em->flush();
			}
	    }
        return $response; 
    }

}
