<?php

namespace ESSABA\AnnonceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ESSABA\AnnonceBundle\Entity\Evenement;
use ESSABA\AnnonceBundle\Form\EvenementType;
use ESSABA\AnnonceBundle\Form\EvenementEditType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class EvenementController extends Controller
{
	public function choixTypeAction()
    {
        $typeEvenements = $this->getDoctrine()->getRepository("ESSABAAnnonceBundle:TypeEvenement")->getAll();
        return $this->render('ESSABAAnnonceBundle:Evenement:choix_type.html.twig', array(
            'typeEvenements' => $typeEvenements
        ));
    }

    public function newAction(Request $request, $slugTypeEvenement)
    {
        $em = $this->getDoctrine();
        $gestionAnnonce = $this->get('essaba_annonce.gestionannonce');

        $repTypeEvenement  = $em->getRepository("ESSABAAnnonceBundle:TypeEvenement");
        $typeEvenement = $repTypeEvenement->findOneBySlug($slugTypeEvenement);

        if(!$typeEvenement)
        {
           throw $this->createNotFoundException(); 
        }

        $evenement = new Evenement();
        $evenement->setTypeEvenement($typeEvenement);

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $evenement->setUtilisateur($this->getUser());
        }

        $form = $this->createForm(EvenementType::class, $evenement); 
         
        $form->handleRequest($request);

        if($form->isValid())
        {
            $newDatelieu = $this->get('essaba_annonce.generale')->date_fr_to_mysql( $evenement->getDateLieu() );
            $evenement->setDateLieu($newDatelieu);
            
            if($evenement->getPhoto() != null)
            {
                $gestionImage = $this->get('essaba_annonce.image');
                $evenement->setPhoto($gestionImage->telecharger($evenement->getPhoto(), array(
                    'prefix'=>$gestionImage::slugify( $evenement->getNom() ), 'type'=>'evenement'
                    ) 
                ));
            }

            $user = $evenement->getUtilisateur();

            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                $user->setEnabled(true);
                $user->setLastLogin(new \DateTime('now'));
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
            
            $session = $this->get('session');
            $session->getFlashBag()->add('message', 'Votre annonce a bien été enregistré');

            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                $session->getFlashBag()->add('message', 'Votre compte a bien été créé et vous êtes maintenant connecté'); 
            }
            
            return $this->redirect($this->generateUrl('essaba_evenement_voir', array('slug' => $evenement->getSlug(), 'type'=>$evenement->getTypeEvenement()->getSlug() ) ) );
        }
        return $this->render('ESSABAAnnonceBundle:Evenement:new.html.twig', array(
                'form' => $form->createView(), 'typeEvenement' => $typeEvenement,
            ));
    }

    public function indexAction(Request $request, $page, $type = null)
    {
        $maxAnnonce = $this->container->getParameter('max_annonce_per_page');
        $em = $this->getDoctrine();
        $repo  = $em->getRepository("ESSABAAnnonceBundle:Evenement");

        $routeName = 'essaba_annonce_evenement_list';
        
        $nbrEvenement = $repo->nbrEvenement();


        $pages_count = ceil($nbrEvenement / $maxAnnonce);
        $evenementsAll = $repo->getList($page, $maxAnnonce);

        $evenements = array();
        $em = $this->getDoctrine()->getManager();
        $em = $this->getDoctrine()->getManager();
        $dateAujord = new \DateTime("now");
        foreach ($evenementsAll as $e) {
            if($e->getDateLieu() < $dateAujord  )
            {
                $em->remove($e);
            }
            else
            {
                $evenements[] = $e;
            }
        }
        $em->flush();
        $pagination = array(
            'page' => $page,
            'route' => $routeName,
            'pages_count' => $pages_count,
            'route_params' => array()
        );

        $items = $this->getIems($evenements, $request);

        return $this->render('ESSABAAnnonceBundle:Evenement:index.html.twig', array(
            "evenements" => $evenements, 'items'=>$items, 'pagination' => $pagination, 'type' => $type
        ));
    }


    public function getIems($list, $request)
    {
        $urlBase = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $appExtension = $this->get('app.twig_extension');
        $items = null;
        foreach ($list as $key => $e) {
            $urlEven = $this->generateUrl('essaba_evenement_voir', array('slug' => $e->getSlug(), 'type'=>$e->getTypeEvenement()->getSlug() ) );

            $items[$key]['type'] = 'smallItem';
            $items[$key]['label'] = $appExtension->formatDate($e->getDateLieu());

            $shortContent = '';
            $image = '';
            if($e->getPhoto() != '')
            {
                $image = '<div class="text-center"><img src="'.$urlBase.'/'.$e->getCheminImage().'min_'.$e->getPhoto().'" alt="'.$e->getNom().'"/></div>';

                $items[$key]['picto'] = '<img src="'.$urlBase.'/'.$e->getCheminImage().'min_'.$e->getPhoto().'" />';
            }
            else
            {
                $items[$key]['picto'] = '<span class="type-evenement-'.$e->getTypeEvenement()->getId().'-gris"></span>';
            }
            $shortContent .= '<h1><a href="'.$urlEven.'">'.$e->getNom().'</a></h1>';

            $items[$key]['shortContent'] = $shortContent;

            $fullContent = $image.$shortContent;
            
            $fullContent .= '<p><span class="geo-fence"></span> localisation : <span itemprop="addressLocality">'.$e->getCommune()->getNom().'</span> </p>';
            $fullContent .= '<p>'.$e->getAdresse().'</p>';
            $fullContent .= '<p>'.$e->getDescription().'</p>';

            $items[$key]['fullContent'] = $fullContent;
            $items[$key]['showMore'] = '<span class="text-click">Plus de detail</span>';
            $items[$key]['showLess'] = '<span class="text-click">Moins de detail</span>';
        }
        return $items;
    }

    public function showAction($slug, Request $request)
    {
        $em = $this->getDoctrine();
        $repo  = $em->getRepository("ESSABAAnnonceBundle:Evenement"); //Entity Repository
        $evenement = $repo->findOneBySlug($slug);

        if($evenement == null)
        {
            throw $this->createNotFoundException();
        }

        /*$form = null;
        if($evenement->getUtilisateur() != $this->getUser())
        {
            $form = $this->createFormBuilder();
            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                $form = $form->add('nom', TextType::class, array('label' => 'Votre nom'))
                ->add('email', EmailType::class, array('label' => 'Votre email'))
                ->add('tel', TextType::class, array('label' => 'Votre téléphone', 'required' => false));
            }
            $form = $form->add('contenu', TextareaType::class, array('label' => 'Votre message'))
            ->getForm();
            $form = $form->createView();
        }*/

        return $this->render('ESSABAAnnonceBundle:Evenement:show.html.twig', array(
            "evenement" => $evenement
        ));
    }

    public function listByTypeAction(Request $request, $page, $slugTypeEvenement)
    {
        $em = $this->getDoctrine();
        $typeEvenement  = $em->getRepository("ESSABAAnnonceBundle:TypeEvenement")->findOneBySlug($slugTypeEvenement);
        if(!$typeEvenement)
        {
            throw $this->createNotFoundException('Cette categories n\'existe pas ou plus.');
        }


        $maxAnnonce = $this->container->getParameter('max_annonce_per_page');
        
        $repo  = $em->getRepository("ESSABAAnnonceBundle:Evenement");

        $routeName = 'essaba_evenement_par_type_evenement';
        
        $nbrEvenement = $repo->nbrEvenementByType($slugTypeEvenement); 

        $pages_count = ceil($nbrEvenement / $maxAnnonce);
        $evenements = $repo->getListBytype($slugTypeEvenement, $page, $maxAnnonce);
        
        $pagination = array(
            'page' => $page,
            'route' => $routeName,
            'pages_count' => $pages_count,
            'route_params' => array('slugTypeEvenement'=>$slugTypeEvenement)
        );

        $items = $this->getIems($evenements, $request);

        return $this->render('ESSABAAnnonceBundle:Evenement:by_type.html.twig', array(
            "evenements" => $evenements, 'pagination' => $pagination, 'slugTypeEvenement' => $slugTypeEvenement
            , 'typeEvenement' => $typeEvenement, 'items'=>$items
        ));
    }

    public function editAction($slug, Request $request)
    {
        $em = $this->getDoctrine();
        $repo  = $em->getRepository("ESSABAAnnonceBundle:Evenement"); //Entity Repository
        $evenement = $repo->findOneBySlug($slug);

        if(!$evenement)
        {
           throw $this->createNotFoundException(); 
        }

        if($evenement->getUtilisateur() != $this->getUser()  && ( !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') ) )
        {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(EvenementEditType::class, $evenement);
        $form->handleRequest($request);

        if($form->isValid())
        {
            $newDatelieu = $this->get('essaba_annonce.generale')->date_fr_to_mysql( $evenement->getDateLieu() );
            $evenement->setDateLieu($newDatelieu);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('message', 'Votre événement a bien été modifié');
            return $this->redirect($this->generateUrl('essaba_evenement_voir', array('type' => $evenement->getTypeEvenement()->getSlug(), 'slug'=>$evenement->getSlug() ) ) );
        }

        return $this->render('ESSABAAnnonceBundle:Evenement:edit.html.twig', array(
            "evenement" => $evenement, 'form' => $form->createView()
        ));
    }

    public function modifierImageAction(Request $request, $id)
    {
        $imageConstraint = new Assert\Image( array('mimeTypes' => array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png')) );

        $validator = $this->get('validator');

        $image = $request->files->get('image');

        $errorList = $validator->validate(
            $image,
            array(new NotBlank(), $imageConstraint)
        );

        $session = $this->get('session');
        if (0 === count($errorList)) {
            $gestionImage = $this->get('essaba_annonce.image');
            $evenement = $this->getDoctrine()->getRepository("ESSABAAnnonceBundle:Evenement")->findOneById($id);

            if($evenement != null && $evenement->getUtilisateur() == $this->getUser())
            {
                if( $evenement->getPhoto() != null )
                {
                    $imageAsupprimer = $evenement->getPhoto();
                }
                $evenement->setPhoto($gestionImage->telecharger($image, array(
                'prefix'=>$gestionImage::slugify( $evenement->getNom() ), 'type'=>'evenement'
                ) ));

                $em = $this->getDoctrine()->getManager();
                $em->flush();

                if(isset($imageAsupprimer))
                {
                    $gestionImage->supprimerImageAnnonce($imageAsupprimer, 'evenement');
                }
                $session->getFlashBag()->add('message', 'L\'image a bien été enregistré');
            }
            else
            {
                $session->getFlashBag()->add('erreur', 'Opération non autorisé.');
            }

        } else {
            $session->getFlashBag()->add('erreur', 'Erreur : '.$errorList[0]->getMessage());
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    public function supprimerImageAction(Request $request, $id)
    {
        $session = $this->get('session');
        $gestionImage = $this->get('essaba_annonce.image');
        $evenement = $this->getDoctrine()->getRepository("ESSABAAnnonceBundle:Evenement")->findOneById($id);

        if($evenement != null && $evenement->getUtilisateur() == $this->getUser())
        {
            if( $evenement->getPhoto() != null )
            {
                $imageAsupprimer = $evenement->getPhoto();

                $evenement->setPhoto(null);

                $em = $this->getDoctrine()->getManager();
                $em->flush();

                if(isset($imageAsupprimer))
                {
                    $gestionImage->supprimerImageAnnonce($imageAsupprimer, 'evenement');
                }
                $session->getFlashBag()->add('message', 'L\'image a bien été supprimé');
            }
        }
        else
        {
            $session->getFlashBag()->add('erreur', 'Opération non autorisé.');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    public function deleteAction(Request $request)
    {
        $id = $request->request->get('idAnnonce');
        $em = $this->getDoctrine()->getManager();
        $evenement  = $em->getRepository("ESSABAAnnonceBundle:Evenement")->findOneById($id);

        if(!$evenement)
        {
           throw $this->createNotFoundException(); 
        }

        if($evenement->getUtilisateur() != $this->getUser()  && !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') )
        {
            throw $this->createAccessDeniedException();
        }

        $em->remove($evenement);
        $em->flush();

        $session = $this->get('session');
        $session->getFlashBag()->add('message', 'L\'événement a bien été supprimé');
        return $this->redirect($this->generateUrl('user_mes_evenements') );
    }

}
