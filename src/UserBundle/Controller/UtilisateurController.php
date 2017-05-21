<?php

namespace UserBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Validator\Constraints as Assert;
use ESSABA\AnnonceBundle\Entity\Notification;
use ESSABA\AnnonceBundle\Entity\NotificationMeta;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use FOS\UserBundle\Util\LegacyFormHelper;

use UserBundle\Form\UtilisateurType;
use UserBundle\Entity\Utilisateur;


class UtilisateurController extends Controller
{

    public function showAction(Request $request){
        $user = $this->getUser();
        $em = $this->getDoctrine();

        $form = $this->createFormBuilder()
        ->setAction($this->generateUrl('user_profile_modifier_image'))
        ->add('image', FileType::class, Array('label' => 'image',
            'required' => false,
            'constraints' => array(
           new NotBlank(), new Image( array('mimeTypes' => array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png')) ),
       )
            ))
        ->getForm();
        

        return $this->render('UserBundle:Profile:show.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    public function compteAction($pseudo){

        $em = $this->getDoctrine();
        $repo  = $em->getRepository("UserBundle:Utilisateur");
        $user = $repo->findOneByUsername($pseudo);

        if(!$user)
        {
           throw $this->createNotFoundException('Ce compte n\'existe pas ou plus.'); 
        }

        return $this->render('UserBundle:Profile:compte.html.twig', array(
            'user' => $user
        ));
    }

    public function supprimerImageAction(){
        $user = $this->getUser();
        if($user->getImage() != null)
        {
            $gestionImage = $this->get('essaba_annonce.image');
            $gestionImage->supprimerProfil($user->getImage());
            $user->setImage(null);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }
        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }

    public function reglageAction(){
        return $this->render('UserBundle:Profile:reglage.html.twig', array(
        ));
    }

    public function supprimerAction(Request $request){

        $form = $this->createFormBuilder()
        ->add('current_password', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'), array(
            'label' => 'Mot de passe actuel',
            'constraints' => new UserPassword(),
        ))
        ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $user = $this->getUser();
            
            $em = $this->getDoctrine()->getManager();
            // suppression de ses annonces
            $sesAnnonces = $user->getAnnonces();
            foreach ($sesAnnonces as $a) {
               $em->remove($a);
            }
            $em->remove($user);

            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('message', 'Votre compte a bien été supprimé');

            return $this->redirect($this->generateUrl('essaba_annonce_homepage'));
        }

        return $this->render('UserBundle:Profile:supprimer.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function modifierImageAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createFormBuilder()
        ->setAction($this->generateUrl('user_profile_modifier_image'))
        /*
        ->add('image', FileType::class, Array('label' => 'image',
            'required' => false,
            'constraints' => array(
           new NotBlank(), new Image( array('maxSize' => '7M', 'minHeight'=>100, 'minWidth'=> 100, 'maxWidth'=> 2000, 'maxHeight'=> 2000, 'mimeTypes' => array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png')) ),
       )
            ))*/


        ->add('image', FileType::class, Array('label' => 'image',
            'required' => false,
            'constraints' => array(
           new NotBlank(), new Image( array('maxSize' => '10M', 'minHeight'=>100, 'minWidth'=> 100, 'mimeTypes' => array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png')) ),
       )
            ))


        ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $gestionImage = $this->get('essaba_annonce.image');

            $nomImage = $gestionImage->telechargerProfil($data['image']);

            if($user->getImage())
            {
              $gestionImage->supprimerProfil($user->getImage());  
            }

            $em = $this->getDoctrine()->getManager();
            $user->setImage($nomImage);
            $em->flush();
        }
        else
        {

            $session = $this->get('session');
            $session->getFlashBag()->add('erreur', 'Erreur : '.$form['image']->getErrors() );
        }
        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }

    public function pseudosAction(Request $request)
    {
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();
        $lesPseudos = $em->getRepository('UserBundle:Utilisateur')->getLesPseudos($this->getUser());
        $response->setData($lesPseudos);
        return $response;
    }

    public function creerAction(Request $request)
    {
        $demande = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $demande);
        return $this->render('UserBundle:Utilisateur:creer.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function demanderAction(Request $request)
    {
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();

        $slugAnnonce = $request->query->get('slugAnnonce');
        $annonce  = $em->getRepository("ESSABAAnnonceBundle:Annonce")->findOneBySlug($slugAnnonce);
        if($annonce == null ||  !$annonce->getEstOffre() || $annonce->getUtilisateur() != $this->getUser())
        {
            throw $this->createAccessDeniedException();
        }

        $titreAnnonce = $annonce->getTitre();
        $pAnnonceur = $request->query->get('pAnnonceur');
        $pAcheteur = $request->query->get('pAcheteur');
        $inconnue = $request->query->get('inconnue');
        
        $aRetourner['isOK'] = false;
        $aRetourner['message'] = '';
        if($inconnue == null){
            $acheteur = $em->getRepository('UserBundle:Utilisateur')->findOneByUsername($pAcheteur);
            
            if($acheteur != null && $pAcheteur != $this->getUser())
            {
                
                $url = $this->generateUrl('user_demande_repondre_vendu', 
                    array('demandeur' => $this->getUser() , 'slugAnnonce' => $slugAnnonce
                        ), UrlGeneratorInterface::ABSOLUTE_URL );

                $notif = new Notification();
                $notif->setContenu($pAnnonceur.' vous demande de confirmer que vous avez bien fait une affaire avec lui '
                ." concerant l'annonce : ".$titreAnnonce );

                $notif->setUtilisateur($acheteur);
                $notif->setLien($url);
                $notif->setType('confirme_demande');
                

                $notifMeta = new NotificationMeta();
                $notifMeta->setNotification($notif);
                $notifMeta->setCle('titre_annonce');
                $notifMeta->setValeur($titreAnnonce);

                //$notif->addNotificationMeta($notifMeta);

                //$em->persist($notif);
                $em->persist($notifMeta);
                $em->remove($annonce);

                $em->flush();
                $aRetourner['isOK'] = true;
                $session = $this->get('session');
                $session->getFlashBag()->add('message', 'Une demande de confirmation vient d\'être envoyée à '.$pAcheteur.'. Et votre annonce n\'existe plus.');
            }
            else
            {
                $aRetourner['message'] = "Verifier le nom de l'acheteur. Cette acheteur n'est pas reconnu.";
            }
        }
        else
        {
           $aRetourner['isOK'] = true; 
        }
        $response->setData($aRetourner);
        return $response;
    }

    public function repondreAction(Request $request, $demandeur){
        $confirme = @$request->request->get('form')['confirme'];

        $currentUrl = $request->getUri();
        $em = $this->getDoctrine()->getManager();
        $repNotif = $em->getRepository('ESSABAAnnonceBundle:Notification');
        $notif = $repNotif->getByLienAndUser($currentUrl, $this->getUser());
        
        if(!$notif)
        {
           throw $this->createNotFoundException('La page que vous cherchez n\'existe pas ou plus nn.'); 
        }

        $titreAnnonce = $notif->getNotificationMetas()[0]->getValeur();
        //$titreAnnonce = $em->getRepository('ESSABAAnnonceBundle:NotificationMeta')->getByNotifAndCle('titre_annonce', $notif);
        
        $contraintVote = array();
        if($confirme == 'oui')
        {
            $contraintVote = array(new NotBlank());
        }

        $form = $this->createFormBuilder()
        ->add('confirme', ChoiceType::class, array(
            'expanded'=>true,
            'choices' => array('Oui' => 'oui', 'Non' => 'non'),
             'constraints' => array(
                new NotBlank(),
            ),
            'choice_attr' => function($val, $key, $index) {
                // adds a class like attending_yes, attending_no, etc
                if($val == "oui")
                {
                   return ['afficher' => "#contVote"]; 
                }

                if($val == "non")
                {
                   return ['cacher' => "#contVote"]; 
                }
                
            }
        ))->add('vote', ChoiceType::class, array(
            'expanded'=>true,
            'choices' => array('Bon zahe' => 'bon', 'Mauvais zahe' => 'mauvais'),
            'constraints' => $contraintVote,
            'required' => false
        ))->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            if($data['confirme'] == 'oui')
            {
                $vendeur = $em->getRepository('UserBundle:Utilisateur')->findOneByUsername($demandeur);
                $acheteur = $this->getUser();
                $vendeur->incrementeNbrVendu();
                $acheteur->incrementeNbrAcheter();
                if($data['vote'] == 'bon')
                {
                    $vendeur->incrementeNbrbon();
                    if( in_array($vendeur->getNbrBon(), array(1, 10, 50))){
                      $this->donnerTropher($vendeur, $vendeur->getNbrBon().'-bon-zahe', $this);
                    }
                }
                else
                {
                    $vendeur->incrementeNbrMauvais();
                }
                $em->persist($vendeur);
                $em->persist($acheteur);
                //$em->flush();

                if( in_array($acheteur->getNbrAcheter(), array(1, 10, 50))){
                  $this->donnerTropher($acheteur, $acheteur->getNbrAcheter().'-achat', $this);
                }

                if( in_array($vendeur->getNbrVendu(), array(1, 10, 50))){
                  $this->donnerTropher($vendeur, $vendeur->getNbrVendu().'-vente', $this);
                }
            }
            //$repNotif->supprimerNotif($notif);

            
            $em->remove($notif);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('message', 'Votre réponse été enregistré');
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return $this->render('UserBundle:Utilisateur:valider_demande.html.twig', array(
            'form' => $form->createView(),
            'demandeur' => $demandeur,
            'titreAnnonce' => $titreAnnonce,
        ));
    }

    /*public function donnerTropher($utilisateur, $slugTrophe, $controller)
    {
        $em = $controller->getDoctrine()->getManager();
        $trophe = $em->getRepository("ESSABAAnnonceBundle:Trophe")->findOneBySlug($slugTrophe);
        if($trophe == null)
        {
            return null;
        }
        $utilisateur->addTrophe($trophe);
        $url = $controller->generateUrl('fos_user_profile_show');
        $url .= '#trophees';
        $notif = new Notification();
        $notif->setContenu('Bravo ! Vous avez obtenu un nouveau trophée');
        $notif->setUtilisateur($utilisateur);
        $notif->setLien($url);

        //$em->persist($utilisateur);
        $em->persist($notif);
        //$em->flush();
    }*/

}
