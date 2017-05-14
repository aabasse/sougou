<?php

namespace ESSABA\AnnonceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use UserBundle\Form\UtilisateurType;
use UserBundle\Form\UtilisateurLessType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EvenementType extends AbstractType
{
    public function __construct(AuthorizationChecker $authorizationchecker)
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
            ->add('nom')
            ->add('datelieu', null, array('label'=>'Date'))
            ->add('description')
            ->add('adresse')
            ->add('photo', FileType::class, Array('label' => 'Photo', 'required' => false ))
            ->add('commune')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ESSABA\AnnonceBundle\Entity\Evenement',
            'validation_groups' => $this->validationGroups
        ));
    }
}
