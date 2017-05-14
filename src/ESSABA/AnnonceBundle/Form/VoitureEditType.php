<?php

namespace ESSABA\AnnonceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use ESSABA\AnnonceBundle\Entity\Voiture;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RequestStack;



class VoitureEditType extends AnnonceEditType
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
            ->add('marque', ChoiceType::class, array('choices' => Voiture::getMarques(),
                'choice_label' => function ($value, $key, $index) {
                return ucfirst($value);

        // or if you want to translate some key
        //return 'form.choice.'.$key;
    }
    ))
            ->add('boiteVitesse', ChoiceType::class, array('label'=>'Boîte de vitesse', 'required' => false, 'choices' => Voiture::getListBoiteVitesse(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ->add('kilometrage', TextType::class, array('required' => true, 'attr'=> array('pattern' => '.*(^\d+([,|.]\d{1,2})?$).*')))
            ->add('carburant', ChoiceType::class, array('label'=>'Carburant', 'required' => false, 'choices' => Voiture::getListCarburant(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ESSABA\AnnonceBundle\Entity\Voiture',
            'validation_groups' => array('Edit'),
        ));
    }
}
