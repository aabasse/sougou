<?php
namespace ESSABA\AnnonceBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use ESSABA\AnnonceBundle\Entity\Message;
use ESSABA\AnnonceBundle\Entity\Annonce;
use ESSABA\AnnonceBundle\Entity\Evenement;
use ESSABA\AnnonceBundle\Entity\Notification;
use ESSABA\AnnonceBundle\Entity\NotificationMeta;
use UserBundle\Entity\Utilisateur;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use ESSABA\AnnonceBundle\Services\GestionImage;

class ApplicationNotification
{
  private $mailer;
  private $router;
  private $gestionImage;
  protected $aEnregistrer = [];

  const FIN_SLUG_ANNONCE = '-annonce';
  const FIN_SLUG_ACHAT = '-achat';

  //public function __construct(\Swift_Mailer $mailer, \Symfony\Bundle\FrameworkBundle\Routing\Router $router, $templating)
  public function __construct(\Symfony\Bundle\FrameworkBundle\Routing\Router $router, $container, GestionImage $gestionImage)
  {
    $this->container = $container;
    $this->router = $router;
    $this->gestionImage = $gestionImage;
  }

  public function postPersist(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    $em = $args->getEntityManager();

    if ($entity instanceof Message) {
      $conv = $entity->getConversation();
      $messageDeInconu = false;
      $destinateurDuMessage = null;
      if($conv->getUtilisateur1() != null && $conv->getUtilisateur2() != null)
      {
          $destinateurDuMessage = $conv->getUtilisateur1();
          if($entity->getExpediteur()->getId() == $conv->getUtilisateur1()->getId() )
          {
            $destinateurDuMessage = $conv->getUtilisateur2();
          } 
      }
      else
      {
        if($entity->getExpediteur() == null)
        {
           $destinateurDuMessage = $conv->getAnnonce()->getUtilisateur();
        }
        $messageDeInconu = true;
      }
        
      $exped_nom = $messageDeInconu ? $conv->getNom() : $entity->getExpediteur()->getUsername();

      if($destinateurDuMessage != null)
      {
        $nom =  $entity->getExpediteur() != null ? $entity->getExpediteur()->getUsername() : $conv->getEmail();
        $url = $this->router->getGenerator()->generate('essaba_annonce_message_show', array('idConversation' => $conv->getId(), 'nom'=> urlencode($nom) ), UrlGeneratorInterface::ABSOLUTE_URL );

        $notif = new Notification();
        $notif->setContenu('Vous avez reçu un nouveau message de '.$exped_nom
          ." au sujet de l'annonce : ".$conv->getAnnonce()->getTitre());
        $notif->setUtilisateur($destinateurDuMessage);
        $notif->setLien($url);
        $notif->setType('message');
        $em->persist($notif);

        $notifMeta = new NotificationMeta();
        $notifMeta->setNotification($notif);
        $notifMeta->setCle('id_conv');
        $notifMeta->setValeur($conv->getId());

        $em->persist($notifMeta);
        $em->flush();
      }

        
      //if($messageDeInconu || !$destinateurDuMessage->estConnecte() )
      if(!$destinateurDuMessage->estConnecte() )
      {

        $message = \Swift_Message::newInstance()
        ->setSubject('✉ Nouveau message pour : '.$conv->getAnnonce()->getTitre())
        ->setFrom('abaavav@gmail.com')
        ->setTo($destinateurDuMessage->getEmail())
        ->setBody(
           $this->container->get('templating')->render(
                'Emails/notice_message.html.twig',
                array(
                  'annonce_titre' => $conv->getAnnonce()->getTitre(),
                  'dest_nom' => $destinateurDuMessage->getUsername(),
                  'exped_nom' => $exped_nom,
                  'url' => $url,
                )
            ),
            'text/html'
        );
        $this->container->get('mailer')->send($message);
      }
    }
    elseif ($entity instanceof Annonce) {
        $user = $entity->getUtilisateur();
        $user->incrementeNbrAnnonce();
        $em = $args->getEntityManager();
    }
  }

  public function preRemove(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    if($entity instanceof Annonce)
    {
      $photos = $entity->getPhotos();
      foreach ($photos as $p) {
        $this->gestionImage->supprimerImageAnnonce($p->getNom());
      }
    }
    else if ($entity instanceof Evenement) {
      $photo = $entity->getPhoto();
      $this->gestionImage->supprimerImageAnnonce($photo, 'evenement');
    }
  }

  public function postFlush(PostFlushEventArgs $event)
  {
    if(!empty($this->aEnregistrer)) {
        $em = $event->getEntityManager();
        foreach ($this->aEnregistrer as $thing) {
            $em->persist($thing);
        }
        $this->aEnregistrer = [];
        $em->flush();
    }
  }
}
?>