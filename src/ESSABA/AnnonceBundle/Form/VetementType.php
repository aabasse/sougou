<?php

namespace ESSABA\AnnonceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use ESSABA\AnnonceBundle\Entity\Vetement;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RequestStack;

class VetementType extends AnnonceType
{
    public function __construct(AuthorizationChecker $authorizationchecker, RequestStack $requestStack)
    {
        parent::__construct($authorizationchecker, $requestStack);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('save')
            ->add('genre', ChoiceType::class, array('label'=>'Genre',
                'choices' => Vetement::getListGenre(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ->add('taille', ChoiceType::class, array('choices' => Vetement::getListTaille(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ->add('couleur', ChoiceType::class, array('required' => false, 'choices' => Vetement::getListCouleur(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ->add('save',SubmitType::class, array('label' => "Déposer l'annonce"))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ESSABA\AnnonceBundle\Entity\Vetement',
            'validation_groups' => $this->validationGroups
        ));
    }
}
