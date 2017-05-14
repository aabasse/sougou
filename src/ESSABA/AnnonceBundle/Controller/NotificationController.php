<?php

namespace ESSABA\AnnonceBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use ESSABA\AnnonceBundle\Entity\Notification;


/**
 * Message controller.
 *
 */
class NotificationController extends Controller
{
    /**
     * Lists all Message entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $notification = array();
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $notifRep = $em->getRepository('ESSABAAnnonceBundle:Notification');
            //$notifications = $notifRep->notifications($this->getUser());

            //$notifications = $notifRep->findByUtilisateur($this->getUser());
            $notifications = $notifRep->findBy(array('utilisateur' => $this->getUser() ), array('created' => 'ASC'));
            $em = $this->getDoctrine()->getManager();
            $dateAujord = new \DateTime("now");
            foreach ($notifications as $n) {
                if($n->getVu() && $n->getType() != 'confirme_demande' && $n->getCreated()->diff( $dateAujord )->days > 1  )
                {
                    $em->remove($n);
                }
            }
            $em->flush();

            if(count($notifications) > 0)
            {
                $notifRep->voirNotif($this->getUser());
            }
        }

        return $this->render('ESSABAAnnonceBundle:Notification:index.html.twig', array(
            'notifications' => $notifications
        ));


    }

    public function nbrNotifAjaxAction(Request $request){
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            //$oldNbr = $this->get('request')->request->get('nbr');
            //$oldNbr = $request->request->get('nbr');
            $em = $this->getDoctrine()->getManager();
            //$em->clear();
            $nbrNouveau = $em->getRepository('ESSABAAnnonceBundle:Notification')->nbrNouveau($this->getUser()); 
            $aAetourner['nbr'] = $nbrNouveau;
        }
        $response = new JsonResponse();
        $response->setData($aAetourner);
        return $response; 
    }

}
