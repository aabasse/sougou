<?php

namespace ESSABA\AnnonceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ESSABA\AnnonceBundle\Entity\Emploi;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmploiType extends AnnonceType
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
            ->remove('titre')
            ->remove('detail')
            ->remove('save')
            ->remove('prix')
            ->remove('autreMoyen')
            ->add('titre', null, array('label' => 'Intitulé du poste'))
            ->add('detail', null, array('label' => 'Description du poste'))
            ->add('type', ChoiceType::class, array('choices' => Emploi::getListTypes(),
                'choice_label' => function ($value, $key, $index) {
                return ucfirst($value);
                }
            ))
            ->add('metier', ChoiceType::class, array('choices' => Emploi::getListMetiers(),
                'choice_label' => function ($value, $key, $index) {
                return ucfirst($value);
                }
            ))
            ->add('profil')
            ->add('formation')
            ->add('salaire')
            ->add('experience')
            ->add('save',SubmitType::class, array('label' => "Déposer l'annonce"))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ESSABA\AnnonceBundle\Entity\Emploi',
            'validation_groups' => $this->validationGroups
        ));
    }
}
