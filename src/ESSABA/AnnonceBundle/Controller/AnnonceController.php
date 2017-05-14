<?php

namespace ESSABA\AnnonceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use ESSABA\AnnonceBundle\Entity\Annonce;
use ESSABA\AnnonceBundle\Entity\Moto;
use ESSABA\AnnonceBundle\Entity\Voiture;
use ESSABA\AnnonceBundle\Entity\Message;
use ESSABA\AnnonceBundle\Entity\Photo;

use ESSABA\AnnonceBundle\Form\AnnonceType;
use ESSABA\AnnonceBundle\Form\AnnonceEditType;
use ESSABA\AnnonceBundle\Form\MotoType;
use ESSABA\AnnonceBundle\Form\VoitureType;
use ESSABA\AnnonceBundle\Form\VoitureEditType;
use ESSABA\AnnonceBundle\Form\MessageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnnonceController extends Controller
{
    public function indexAction(Request $request, $page, $type = null)
    {
        $maxAnnonce = $this->container->getParameter('max_annonce_per_page');
        $em = $this->getDoctrine();
        $repo  = $em->getRepository("ESSABAAnnonceBundle:Annonce");

        $routeName = 'essaba_annonces';
        $estOffre = null;
        if($type !== null)
        {
            switch ($type) {
                case 'offre':
                    $routeName = 'essaba_annonces_list_offre';
                    break;
                case 'demande':
                    $routeName = 'essaba_annonces_list_demande';
                    break;
            }
            $estOffre = $type == 'offre';
        }
        
        $nbrAnnonce = $repo->nbrAnnonce($estOffre);


        $pages_count = ceil($nbrAnnonce / $maxAnnonce);

        $annonces = $repo->getList($page, $maxAnnonce, $estOffre); 

        $pagination = array(
            'page' => $page,
            'route' => $routeName,
            'pages_count' => $pages_count,
            'route_params' => array()
        );

        return $this->render('ESSABAAnnonceBundle:Annonce:index.html.twig', array(
            "annonces" => $annonces, 'pagination' => $pagination, 'type' => $type
        ));
    }

    public function listByCategAction(Request $request, $page, $slugSousCateg, $type = null)
    {
        $em = $this->getDoctrine();
        $sousCtegorie  = $em->getRepository("ESSABAAnnonceBundle:SousCategorie")->findOneBySlug($slugSousCateg);
        if(!$sousCtegorie)
        {
            throw $this->createNotFoundException('Cette categories n\'existe pas ou plus.');
        }


        $maxAnnonce = $this->container->getParameter('max_annonce_per_page');
        
        $repo  = $em->getRepository("ESSABAAnnonceBundle:Annonce");

        $routeName = 'essaba_annonce_par_sous_categ';
        $estOffre = null;
        if($type !== null)
        {
            switch ($type) {
                case 'offre':
                    $routeName = 'essaba_offres_par_sous_categ';
                    break;
                case 'demande':
                    $routeName = 'essaba_demandes_par_sous_categ';
                    break;
            }
            $estOffre = $type == 'offre';
        }
        
        $nbrAnnonce = $repo->nbrAnnonceByCateg($slugSousCateg, $estOffre); 

        $pages_count = ceil($nbrAnnonce / $maxAnnonce);

        $annonces = $repo->getListByCateg($slugSousCateg, $estOffre, $page, $maxAnnonce);
        
        $pagination = array(
            'page' => $page,
            'route' => $routeName,
            'pages_count' => $pages_count,
            'route_params' => array('slugSousCateg'=>$slugSousCateg)
        );

        return $this->render('ESSABAAnnonceBundle:Annonce:by_categ.html.twig', array(
            "annonces" => $annonces, 'pagination' => $pagination, 'slugSousCateg' => $slugSousCateg
            , 'sousCtegorie' => $sousCtegorie
        ));
    }

    public function showAction($slug, Request $request)
    {
        $em = $this->getDoctrine();
        $repo  = $em->getRepository("ESSABAAnnonceBundle:Annonce"); //Entity Repository
        $annonce = $repo->findOneBySlug($slug);

        if($annonce == null)
        {
            throw $this->createNotFoundException();
        }

        $form = null;
        if($annonce->getUtilisateur() != $this->getUser())
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
        }

        return $this->render('ESSABAAnnonceBundle:Annonce:show.html.twig', array(
            "annonce" => $annonce, 'form' => $form
        ));
    }

    public function editAction($slug, Request $request)
    {
        

        $em = $this->getDoctrine();
        $repo  = $em->getRepository("ESSABAAnnonceBundle:Annonce"); //Entity Repository
        $annonce = $repo->findOneBySlug($slug);

        if(!$annonce)
        {
           throw $this->createNotFoundException(); 
        }

        if($annonce->getUtilisateur() != $this->getUser()  && ( !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') ) )
        {
            throw $this->createAccessDeniedException();
        }

        $gestionAnnonce = $this->get('essaba_annonce.gestionannonce');
        $annonceName = $gestionAnnonce->getClassNameByAnnonce($annonce);

        $form = $this->createForm('ESSABA\AnnonceBundle\Form\\'.$annonceName.'EditType', $annonce);
        $form->handleRequest($request);

        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            $session = $this->get('session');
            $session->getFlashBag()->add('message', 'Votre annonce a bien été modifié');
            return $this->redirect($this->generateUrl('essaba_annonce_voir', array('slug' => $annonce->getSlug(), 'categ'=>$annonce->getSousCategorie()->getSlug() ) ) );
        }

        return $this->render('ESSABAAnnonceBundle:Annonce:edit.html.twig', array(
            "annonce" => $annonce, 'form' => $form->createView()
        ));
    }

    public function deposerAction()
    {
        return $this->render('ESSABAAnnonceBundle:Annonce:deposer.html.twig', array(
            // ...
        ));
    }

    public function deleteAction(Request $request)
    {
        $id = $request->request->get('idAnnonce');
        $em = $this->getDoctrine()->getManager();
        $annonce  = $em->getRepository("ESSABAAnnonceBundle:Annonce")->findOneById($id);

        if(!$annonce)
        {
           throw $this->createNotFoundException(); 
        }

        if($annonce->getUtilisateur() != $this->getUser()  && ( !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') ) )
        {
            throw $this->createAccessDeniedException();
        }
        
        $em->remove($annonce);
        $em->flush();

        $session = $this->get('session');
        $session->getFlashBag()->add('message', 'L\'annonce a bien été supprimé');
        return $this->redirect($this->generateUrl('fos_user_profile_show') );
    }

    public function choixCategAction($estOffre)
    {
        $categories = $this->getDoctrine()->getRepository("ESSABAAnnonceBundle:Categorie")->getAll();

        //var_dump($categories);
        return $this->render('ESSABAAnnonceBundle:Annonce:choix_categ.html.twig', array(
            'categories' => $categories, 'estOffre' => $estOffre,
        ));
    }

    public function vendreAction(Request $request, $slugSousCateg)
    {
        $routeName = $request->get('_route');
        $estOffre = $routeName == 'essaba_annonce_offre_new';
        $em = $this->getDoctrine();
        $gestionAnnonce = $this->get('essaba_annonce.gestionannonce');

        $repoSousCateg  = $em->getRepository("ESSABAAnnonceBundle:SousCategorie");
        $sousCateg = $repoSousCateg->findOneBySlug($slugSousCateg);

        if(!$sousCateg)
        {
           throw $this->createNotFoundException(); 
        }

        $classNameAnnonce = $gestionAnnonce->getClassNameAnnonce($sousCateg->getId());
        $className = 'ESSABA\AnnonceBundle\Entity\\'.$classNameAnnonce;
        $annonce = new $className();
        $annonce->setSousCategorie($sousCateg);
        $annonce->setEstOffre($estOffre);

        for ($i=0; $i < 3 ; $i++) {
            $photo = new Photo();
            $annonce->addPhoto($photo);
        }
        
        $classTypeForm = 'ESSABA\AnnonceBundle\Form\\'.$classNameAnnonce.'Type';

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $annonce->setUtilisateur($this->getUser());
        }

        $form = $this->createForm($classTypeForm, $annonce); 
         
        $form->handleRequest($request);

        if($form->isValid())
        {
            $gestionImage = $this->get('essaba_annonce.image');
            $classNameAnnonceForFile = $gestionAnnonce->getClassNameForFile($classNameAnnonce);
            
            $dejaPrincipalTrouve = false;
            foreach ( $annonce->getPhotos() as $key =>  $photo) {
                
                if($photo->getNom() == null )
                {
                    $annonce->removePhoto($photo);
                }
                else
                {
                    $photo->setNom($gestionImage->telecharger($photo->getNom(), array('prefix'=>$gestionImage::slugify( $annonce->getTitre() ))
                        ));
                    if(!$dejaPrincipalTrouve)
                    {
                        $photo->setEstPrincipal(true);
                        $dejaPrincipalTrouve = true;
                    }
                    $photo->setAnnonce($annonce);
                }
            }

            $user = $annonce->getUtilisateur();
            //$user->incrementeNbrAnnonce();
            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                $user->setEnabled(true);
                $user->setLastLogin(new \DateTime('now'));
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();
            
            $session = $this->get('session');
            $session->getFlashBag()->add('message', 'Votre annonce a bien été enregistré');

            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                $session->getFlashBag()->add('message', 'Votre compte a bien été créé et vous êtes maintenant connecté'); 
            }
            
            return $this->redirect($this->generateUrl('essaba_annonce_voir', array('slug' => $annonce->getSlug(), 'categ'=>$annonce->getSousCategorie()->getSlug() ) ) );
        }
        return $this->render('ESSABAAnnonceBundle:Annonce:vendre.html.twig', array(
                'form' => $form->createView(), 'sousCateg' => $sousCateg, 'estOffre' => $estOffre
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

        //$aAetourner = '';
        $session = $this->get('session');
        if (0 === count($errorList)) {
            $num = $request->request->get('num');
            if(in_array($num, array(0,1,2)))
            {
                $gestionImage = $this->get('essaba_annonce.image');
                $annonce = $this->getDoctrine()->getRepository("ESSABAAnnonceBundle:Annonce")->findOneById($id);

                if($annonce != null && $annonce->getUtilisateur() == $this->getUser())
                {
                    $photos = $annonce->getPhotos();
                    if( isset($photos[$num]) )
                    {
                        $photo = $photos[$num];
                        $imageAsupprimer = $photo->getNom();
                        
                    }
                    else
                    {
                        $photo = new Photo();
                        $photo->setAnnonce($annonce);
                        $annonce->addPhoto($photo);
                    }
                    $photo->setNom( $gestionImage->telecharger($image, array('prefix'=>$gestionImage::slugify( $annonce->getTitre() ))
                        ) );

                    $em = $this->getDoctrine()->getManager();
                    $em->flush();

                    if(isset($imageAsupprimer))
                    {
                        $gestionImage->supprimerImageAnnonce($imageAsupprimer);
                    }
                    $session->getFlashBag()->add('message', 'L\'image a bien été enregistré');
                }
            }
        } else {
            $session->getFlashBag()->add('erreur', 'Erreur : '.$errorList[0]->getMessage());
        }

        /*$response = new JsonResponse();
        $response->setData($aAetourner);
        return $response;*/
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    public function supprimerImageAction(Request $request, $id)
    {
        $imageConstraint = new Assert\Image( array('mimeTypes' => array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png')) );

        $validator = $this->get('validator');

        $image = $request->files->get('image');

        $errorList = $validator->validate(
            $image,
            array(new NotBlank(), $imageConstraint)
        );

        $session = $this->get('session');
        $num = $request->request->get('num');
        if(in_array($num, array(0,1,2)))
        {
            $gestionImage = $this->get('essaba_annonce.image');
            $annonce = $this->getDoctrine()->getRepository("ESSABAAnnonceBundle:Annonce")->findOneById($id);

            if($annonce != null && $annonce->getUtilisateur() == $this->getUser())
            {
                $em = $this->getDoctrine()->getManager();

                $photos = $annonce->getPhotos();
                if( isset($photos[$num]) )
                {
                    $photo = $photos[$num];
                    $annonce->removePhoto($photos[$num]);
                    $imageAsupprimer = $photo->getNom();
                    $em->remove($photo);
                }

                $em->flush();

                if(isset($imageAsupprimer))
                {
                    $gestionImage->supprimerImageAnnonce($imageAsupprimer);
                }
                $session->getFlashBag()->add('message', 'L\'image a bien été supprimé');
            }
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}
