<?php

namespace ESSABA\AnnonceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use ESSABA\AnnonceBundle\Entity\Message;
use ESSABA\AnnonceBundle\Entity\Notification;
use ESSABA\AnnonceBundle\Entity\Conversation;
use Symfony\Component\HttpFoundation\Request;
use ESSABA\AnnonceBundle\Form\MessageType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;

/**
 * Message controller.
 *
 */
class MessageController extends Controller
{
    /**
     * Lists all Message entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $messages = $em->getRepository('ESSABAAnnonceBundle:Message')->findAll();

        return $this->render('ESSABAAnnonceBundle:Message/index.html.twig', array(
            'messages' => $messages,
        ));
    }



    public function getNvoMessageAjaxAction($idConversation, Request $request){
        $messages = null;
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $messages = $em->getRepository('ESSABAAnnonceBundle:Message')->getNvoMessages($idConversation, $this->getUser());
            $repConv = $em->getRepository('ESSABAAnnonceBundle:Conversation');
            if(count($messages) > 0)
            {
                $repConv->voirConv($idConversation, $this->getUser()); 
                $em->getRepository('ESSABAAnnonceBundle:Notification')->supprimerByConv($idConversation, $this->getUser());
            }
        }
        $response = new JsonResponse();
        $response->setData($messages);
        return $response;
    }


    public function envoyerAction($slug, Request $request)
    {
        $estConnecte = $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY');
        $em = $this->getDoctrine()->getManager();
        $repoAno  = $em->getRepository("ESSABAAnnonceBundle:Annonce");
        $annonce = $repoAno->findOneBySlug($slug);

        if($annonce->getUtilisateur() == $this->getUser())
        {
            return $this->redirect($this->generateUrl('essaba_annonce_voir', array('slug'=>$slug, 'categ'=>$annonce->getSousCategorie()->getSlug())));
        }

        
        //$form = $this->createForm(MessageType::class, $message);

        $form = $this->createFormBuilder();
        if (!$estConnecte) {
            $form = $form->add('nom', TextType::class, array('label' => 'Votre nom', 'constraints' => array(
                new NotBlank(),
                new Regex(array( 'pattern' => '/^[^#\\\\<>$\/]+$/') )
            )))
            ->add('email', EmailType::class, array('label' => 'Votre email', 'constraints' => array(
                new NotBlank(), new Email()
            )))
            ->add('tel', TextType::class, array('label' => 'Votre téléphone', 'required' => false, 
                'constraints' => array(
                new Regex(array( 'pattern' => '/^0[1-68]([-. ]?[0-9]{2}){4}$/')) ) ) );
        }
        $form = $form->add('contenu', TextareaType::class, array('label' => 'Votre message', 'constraints' => array(
                new NotBlank(),
                new Regex(array( 'pattern' => '/^[^\\\\<>$\/]+$/') )
            )))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = new Message();
            $data = $form->getData();

            $message->setContenu($data['contenu']);

            $repoConv  = $em->getRepository("ESSABAAnnonceBundle:Conversation");
            $exp = $estConnecte ? $this->getUser() : $data['email'];
            $conv = $repoConv->getConvForAnnonce($exp, $annonce);
            if($conv == null)
            {
                $conv = new Conversation();
                $conv->setUtilisateur1($annonce->getUtilisateur());
                if ($estConnecte) {
                    $conv->setUtilisateur2($this->getUser());
                }
                else
                {
                    $conv->setNom($data['nom']);
                    $conv->setEmail($data['email']);
                    $conv->setTel($data['tel']);
                }
            }

            $conv->setAnnonce($annonce);
            $message->setConversation($conv);
            if ($estConnecte) {
                $message->setExpediteur($this->getUser());
            }

            $em->persist($conv);
            $em->persist($message);

            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('message', 'Votre message a bien été envoyé');
            return $this->redirect($this->generateUrl('essaba_annonce_voir', array('slug'=>$slug, 'categ'=>$annonce->getSousCategorie()->getSlug()  )));
        }
        return $this->render('ESSABAAnnonceBundle:Annonce:show.html.twig', array(
            "annonce" => $annonce, 'form' => $form->createView(), 'afficheForm' => true
        ));
    }

    function saveAjaxAction($idConversation, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repConv = $em->getRepository('ESSABAAnnonceBundle:Conversation');

        $nvoMessage = new Message();
        $nvoMessage->setExpediteur(  $this->getUser() );
        $form = $this->createForm(MessageType::class, $nvoMessage);

        $form->handleRequest($request);
        $aAetourner['pasErreur'] = false;

        if($form->isSubmitted() && $form->isValid())
        {
            $conv = $repConv->getConvDeUtilisateur($idConversation, $this->getUser());
            $nvoMessage->setConversation( $conv );
            $em->persist($nvoMessage);
            $em->flush();
            $aAetourner['pasErreur'] = true;
            $aAetourner['message']['id'] = $nvoMessage->getId();
            $aAetourner['message']['contenu'] = $nvoMessage->getContenu();
            $aAetourner['message']['vu'] = $nvoMessage->getVu();
            $aAetourner['message']['created'] = $nvoMessage->getCreated();
            $aAetourner['message']['idExpediteur'] = $this->getUser()->getId();
           
        }

        $response = new JsonResponse();
        $response->setData($aAetourner);
        return $response;
    }

    public function deleteAction(Request $request)
    {
        $id = $request->request->get('idMessage');
        $em = $this->getDoctrine()->getManager();
        $message  = $em->getRepository("ESSABAAnnonceBundle:Message")->findOneById($id);

        if($message == null || $message->getExpediteur() != $this->getUser())
        {
            throw $this->createAccessDeniedException();
        }

        $em->remove($message);
        $em->flush();

        $response = new JsonResponse();
        return $response;

        /*$session = $this->get('session');
        $session->getFlashBag()->add('message', 'Le message a bien été supprimé');
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);*/
    }




}
