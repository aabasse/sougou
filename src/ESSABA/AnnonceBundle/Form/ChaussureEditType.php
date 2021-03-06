<?php

namespace ESSABA\AnnonceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use ESSABA\AnnonceBundle\Entity\Vetement;
use ESSABA\AnnonceBundle\Entity\Chaussure;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RequestStack;

class ChaussureEditType extends AnnonceEditType
{
    public function __construct(RequestStack $requestStack)
    {
        parent::__construct($requestStack);
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
            ->add('pointure', ChoiceType::class, array('label'=>'Pointure',
                'choices' => Chaussure::getListPointure(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ->add('couleur', ChoiceType::class, array('required' => false, 'choices' => Chaussure::getListCouleur(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ESSABA\AnnonceBundle\Entity\Chaussure',
            'validation_groups' => $this->validationGroups,
        ));
    }
}
