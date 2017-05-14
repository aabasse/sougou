<?php

namespace ESSABA\AnnonceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ESSABA\AnnonceBundle\Entity\Notification;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

use ESSABA\AnnonceBundle\Entity\Annonce;
use ESSABA\AnnonceBundle\Entity\Voiture;
use ESSABA\AnnonceBundle\Entity\Chaussure;
use ESSABA\AnnonceBundle\Entity\Vetement;
use ESSABA\AnnonceBundle\Entity\Location;
use ESSABA\AnnonceBundle\Entity\Emploi;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ESSABAAnnonceBundle:Default:index.html.twig', array('isAccueil' => true));
    }

    public function aideAction()
    {
        return $this->render('ESSABAAnnonceBundle:Default:aide.html.twig');
    }

    public function mentionsLegalesAction()
    {
        return $this->render('ESSABAAnnonceBundle:Default:mentions_legales.html.twig');
    }

    public function menuAction()
    {
    	$em = $this->getDoctrine();
        $repoNotification = $em->getRepository("ESSABAAnnonceBundle:Notification"); //Entity Repository
        
        $nbrNotification = 0;
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $nbrNotification = $repoNotification->nbrNouveau($this->getUser());
            //die();
        }
        
        return $this->render('ESSABAAnnonceBundle:Default:menu.html.twig', array('nbrNotification'=>$nbrNotification));
    }

    public function categoriesAction($categActive = null, $nomRoute = null)
    {
        $em = $this->getDoctrine();
        $categories = $em->getRepository("ESSABAAnnonceBundle:Categorie")->getAll();
        
        return $this->render('ESSABAAnnonceBundle:Default:categories.html.twig', 
            array('categories'=>$categories, 'nomRoute'=>$nomRoute, 'categActive'=>$categActive));
    }

    public function typeEvenementsAction($typeEvenementActive = null, $nomRoute = null)
    {
        $em = $this->getDoctrine();
        $typeEvenements = $em->getRepository("ESSABAAnnonceBundle:TypeEvenement")->getAll();
        
        return $this->render('ESSABAAnnonceBundle:Default:typeEvenement.html.twig', 
            array('typeEvenements'=>$typeEvenements, 'nomRoute'=>$nomRoute, 'typeEvenementActive'=>$typeEvenementActive));
    }

    public function rechercheAction(Request $request, $page, $term = null, $commune = null)
    {
        $em = $this->getDoctrine();
        
        $defaultData = array();
        if($term != null){$defaultData['titre'] = $term;}
        if($commune != null)
        {
            $repoCommune = $em->getRepository("ESSABAAnnonceBundle:Commune");
            $commune = $repoCommune->findOneBySlug($commune);
            $defaultData['commune'] = $commune;

            if($defaultData['commune'] == null )
            {
                throw $this->createNotFoundException('Cette page n\'existe pas ou plus.');
            }

        }

        $idCategAjax = $request->query->get('categ');
        $idCateg = $request->request->get('form')['categorie'];

        $idCateg = $idCategAjax != null ? $idCategAjax : $idCateg;

        $gestionAnnonce = $this->get('essaba_annonce.gestionannonce');
        $yaDesCritereSpecifique = false;

        
        $repoAno = $em->getRepository("ESSABAAnnonceBundle:Annonce");
        

        $form = $this->createFormBuilder($defaultData)->setAction($this->generateUrl('essaba_annonce_recherche'))
            //->setMethod('GET')
            ->add('titre', TextType::class, Array('required' => false ))
            ->add('typeAnnonce', ChoiceType::class, array('label'=>'Type d\'annonce', 'placeholder' => 'Tous', 'required' => false, 'expanded'=>true, 'choices' => array('offre', 'demande'),
            'choice_label' => function ($value, $key, $index) { return ucfirst($value);}))

            ->add('categorie', EntityType::class, array('class'=>'ESSABAAnnonceBundle:SousCategorie','group_by' => function($val, $key, $index) { return $val->getCategorie();
                },
                'required' => false,
                'label' => 'Catégorie'
                ))
            ->add('commune', EntityType::class, array('class' => 'ESSABAAnnonceBundle:Commune', 'choice_label' => 'nom', 'required' => false))
            ->add('min', MoneyType::class, array('scale' => 2, 'required' => false, 'attr'=> array('placeholder' => 'min', 'pattern' => '.*(^\d+([,|.]\d{1,2})?$).*') ))
            ->add('max', MoneyType::class, array('scale' => 2, 'required' => false, 'attr'=> array('placeholder' => 'max', 'pattern' => '.*(^\d+([,|.]\d{1,2})?$).*') ));
            $className = $gestionAnnonce->getClassNameAnnonce($idCateg);

            // critere specifique :
            $form->add('marque', ChoiceType::class, array('attr'=>array('class'=>'spe-50'), 'expanded'=>true, 'multiple'=>true, 'required' => false, 'choices' => Voiture::getMarques(),'choice_label' => function ($value, $key, $index) {return ucfirst($value); }))
            ->add('boiteVitesse', ChoiceType::class, array('attr'=>array('class'=>'spe-50'), 'label'=>'Boîte de vitesse', 'required' => false, 'choices' => Voiture::getListBoiteVitesse(),
            'choice_label' => function ($value, $key, $index) { return ucfirst($value);}))

            ->add('kilometrageMin', TextType::class, Array('attr'=>array('class'=>'spe-50 spe-51', 'placeholder'=>'km'), 'required' => false, 'label'=>'Kilomètres minimum' ))
            ->add('kilometrageMax', TextType::class, Array('attr'=>array('class'=>'spe-50 spe-51', 'placeholder'=>'km'), 'required' => false, 'label'=>'Kilomètres maximum' ))
            ->add('carburant', ChoiceType::class, array('attr'=>array('class'=>'spe-50'), 'label'=>'Carburant', 'required' => false, 'choices' => Voiture::getListCarburant(),
            'choice_label' => function ($value, $key, $index) { return ucfirst($value); }))
            ->add('genre', ChoiceType::class, array('attr'=>array('class'=>'spe-10 spe-11'), 'required' => false, 'label'=>'Genre',
                'choices' => Vetement::getListGenre(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ->add('pointure', ChoiceType::class, array('attr'=>array('class'=>'spe-10'), 'required' => false, 'label'=>'Pointure',
                'choices' => Chaussure::getListPointure(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ->add('taille', ChoiceType::class, array('attr'=>array('class'=>'spe-11'), 'required' => false, 'choices' => Vetement::getListTaille(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ->add('couleur', ChoiceType::class, array('attr'=>array('class'=>'spe-10 spe-11'), 'required' => false, 'choices' => Chaussure::getListCouleur(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ->add('type', ChoiceType::class, array('attr'=>array('class'=>'spe-60 spe-61'), 'required' => false, 'label'=>'Type de bien imobilier',
                'choices' => Location::getTypes(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ->add('surfaceMin', TextType::class, Array('attr'=>array('class'=>'spe-60 spe-61'), 'required' => false, 'label'=>'Surface minimum' ))
            ->add('surfaceMax', TextType::class, Array('attr'=>array('class'=>'spe-60 spe-61'), 'required' => false, 'label'=>'Surface maximum' ))
            ->add('meuble', ChoiceType::class, array('attr'=>array('class'=>'spe-60'), 'required' => false, 'label'=>'Meublé ou non meublé',
                'choices' => array('meublé', 'non meublé'),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ->add('pieceMin', TextType::class, Array('attr'=>array('class'=>'spe-60 spe-61'), 'required' => false, 'label'=>'Nombre de piece minimum' ))
            ->add('pieceMax', TextType::class, Array('attr'=>array('class'=>'spe-60 spe-61'), 'required' => false, 'label'=>'Nombre de piece maximum' ))
            // emploi -----------------------------------------
            ->add('typeEmploi', ChoiceType::class, array('attr'=>array('class'=>'spe-80'), 'multiple'=>true, 'required' => false, 'choices' => Emploi::getListTypes(),'choice_label' => function ($value, $key, $index) {return ucfirst($value); }))
            ->add('metier', ChoiceType::class, array('attr'=>array('class'=>'spe-80'), 'required' => false, 'choices' => Emploi::getListMetiers(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ;


        $form = $form->getForm();
        $form->handleRequest($request);
        $data = $form->getData();

        $maxAnnonce = $this->container->getParameter('max_annonce_per_page');
        $nbrAnnonce = $repoAno->nbrAnnonceRecherche($data, $className);
        $annonces = $repoAno->rechercher($data, $className, $page, $maxAnnonce);

        
        $pages_count = ceil($nbrAnnonce / $maxAnnonce);
        $routeName = $request->get('_route');

        $route_params = array();
        if($term != null)
        {
            $route_params['term'] = $term;
        }
        if($commune != null)
        {
            $route_params['commune'] = $commune;
        }
        $pagination = array(
            'page' => $page,
            'route' => $routeName,
            'pages_count' => $pages_count,
            'route_params' => $route_params
        );

        return $this->render('ESSABAAnnonceBundle:Default:recherche.html.twig', array(
            'form' => $form->createView(),
            'annonces' => $annonces,
            'commune' => $commune,
            'pagination' => $pagination
        ));
    }

    public function classementAction()
    {
        $repUtilisateur = $this->getDoctrine()->getRepository("UserBundle:Utilisateur");
       
        $vendeurs = $repUtilisateur->getTopVendeur();
        $acheteurs = $repUtilisateur->getTopAcheteur();

        //var_dump(count($vendeurs));

        return $this->render('ESSABAAnnonceBundle:Default:classement.html.twig', array(
            'vendeurs' => $vendeurs,
            'acheteurs' => $acheteurs,
            ));
    }

    public function pageNotFoundAction()
    {
        throw $this->createNotFoundException('Cette page n\'existe pas ou plus.');
    }
}
