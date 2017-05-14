<?php

namespace ESSABA\AnnonceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RequestStack;

class MotoType extends AnnonceType
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
            ->add('kilometrage', TextType::class, array('required' => true, 'attr'=> array('pattern' => '.*(^\d+([,|.]\d{1,2})?$).*')))
            ->add('save',SubmitType::class, array('label' => "Déposer l'annonce"));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ESSABA\AnnonceBundle\Entity\Moto',
            'validation_groups' => $this->validationGroups
        ));
    }
}
