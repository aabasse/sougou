<?php

namespace ESSABA\AnnonceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use UserBundle\Form\UtilisateurType;
use UserBundle\Form\UtilisateurLessType;
use ESSABA\AnnonceBundle\Entity\SousCategorie;
use ESSABA\AnnonceBundle\Form\PhotoType;
use ESSABA\AnnonceBundle\Repository\SousCategorieRepository;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

use Symfony\Component\HttpFoundation\RequestStack;
use ESSABA\AnnonceBundle\Entity\Annonce;


class AnnonceType extends AbstractType
{


    protected $authorizationchecker;
    protected $validationGroups;
    protected $estOffre;

    public function __construct(AuthorizationChecker $authorizationchecker, RequestStack $requestStack)
    {
        $this->authorizationchecker = $authorizationchecker;
        $this->validationGroups[] = "Default";

        if ($this->authorizationchecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->validationGroups[] = "Profile";
        }
        else
        {
            $this->validationGroups[] = "Registration";
        }

        $request = $requestStack->getCurrentRequest();
        $routeName = $request->get('_route');
        $this->estOffre = $routeName == 'essaba_annonce_offre_new';

        if($this->estOffre)
        {
            $this->validationGroups[] = "Offre";
        }
        else
        {
            $this->validationGroups[] = "Demande";
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($this->authorizationchecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $builder->add('utilisateur', UtilisateurLessType::class, array( 'label' => false ));
        }
        else
        {
            $builder->add('utilisateur', UtilisateurType::class, array( 'label' => false ));
        }

        $builder
            //->add('utilisateur', UtilisateurType::class, array( 'label' => false ))
            ->add('titre')
            ->add('detail')
            ->add('photos', CollectionType::class, array(
                // each entry in the array will be an "Photo" field
                'entry_type'   => PhotoType::class,
            ));
        if($this->estOffre)
        {
            $builder
                ->add('prix', MoneyType::class, array('currency'=>'MLF','scale' => 2, 'required' => false, 'attr'=> array('pattern' => '.*(^\d+([,|.]\d{1,2})?$).*') ))
                //->add('prix', TextType::class, array('required' => false, 'attr'=> array('pattern' => '.*(^\d+([,|.]\d{1,2})?$).*') ))
                ->add('autreMoyen', ChoiceType::class, array('required' => false, 'label'=>'Ou pour le prix',
                'choices' => Annonce::getListAutreMoyens(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))

            ;
        }
            $builder->add('commune', null, array('label'=>'Région'))
            /*->add('sousCategorie', EntityType::class, array('required' => false, 'class'=>'ESSABAAnnonceBundle:SousCategorie',
                'group_by' => function($val, $key, $index) {
                    return $val->getCategorie();
                }, 'label' => 'Catégorie'
                ))*/
            ->add('save',SubmitType::class, array('label' => "Déposer l'annonce"))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ESSABA\AnnonceBundle\Entity\Annonce',
            'validation_groups' => $this->validationGroups
        ));
    }
}
