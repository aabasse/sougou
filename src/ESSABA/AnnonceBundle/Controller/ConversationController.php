<?php

namespace ESSABA\AnnonceBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ESSABA\AnnonceBundle\Entity\Message;
use ESSABA\AnnonceBundle\Entity\Notification;
use Symfony\Component\HttpFoundation\Request;
use ESSABA\AnnonceBundle\Form\MessageType;

/**
 * Message controller.
 *
 */
class ConversationController extends Controller
{
    /**
     * Lists all Message entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $conversations = $em->getRepository('ESSABAAnnonceBundle:Conversation')->getAllConversations($this->getUser());

        return $this->render('ESSABAAnnonceBundle:Conversation:index.html.twig', array(
            'conversations' => $conversations,
        ));
    }


    public function showAction($idConversation, $nom, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $repConv = $em->getRepository('ESSABAAnnonceBundle:Conversation');
        $messages = $repConv->getConversation($idConversation, $this->getUser());
        if($messages == null)
        {
            throw $this->createNotFoundException('La conversation n\'existe pas ou plus.<br/>Les raisons possibles : 
                <br/>L\'annonce concerné par cette conversation n\'existe plus.
                <br/>Tous les messages de cette conversation ont été supprimés.');
        }

        //var_dump($messages);


        $nom = urldecode($nom);

        if($messages != null)
        {
            $repConv->voirConv($idConversation, $this->getUser());
            $em->getRepository('ESSABAAnnonceBundle:Notification')->supprimerByConv($idConversation, $this->getUser());
        }

        $estInconu = $messages[0]['nom'] != '';
        $viewForm = null;
        if(!$estInconu)
        {
            $nvoMessage = new Message();
            $nvoMessage->setExpediteur(  $this->getUser() );
            $form = $this->createForm(MessageType::class, $nvoMessage);
            $form->handleRequest($request);
            $viewForm = $form->createView();
        }
        else
        {
            $nom = $messages[0]['nom'];
        }

        return $this->render('ESSABAAnnonceBundle:Conversation:show.html.twig', array(
            'messages' => $messages, 'form' => $viewForm,
            'nom' => $nom,
            'estInconu' => $estInconu,
        ));
    }
}
