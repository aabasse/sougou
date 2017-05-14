<?php

namespace ESSABA\AnnonceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

use ESSABA\AnnonceBundle\Entity\Location;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RequestStack;
use ESSABA\AnnonceBundle\Entity\Annonce;

class LocationEditType extends AnnonceEditType
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
            
            ->add('type', ChoiceType::class, array('label'=>'Type de bien imobilier',
                'choices' => Location::getTypes(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ->add('surface', TextType::class, array('required' => false, 'attr'=> array('pattern' => '.*(^\d+([,|.]\d{1,2})?$).*')))
            ->add('meuble', ChoiceType::class, array('label'=>'Meublé ou non meublé',
                'choices' => array('meublé', 'non meublé'),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
            ))
            ->add('piece');
            if($this->estOffre)
            {
                $builder
                ->remove('prix')
                ->remove('autreMoyen')
                ->add('prix', MoneyType::class, array('required' => false, 'scale' => 2, 'label' => 'Loyer', 'attr'=> array('pattern' => '.*(^\d+([,|.]\d{1,2})?$).*') ))

                ->add('autreMoyen', ChoiceType::class, array('required' => false, 'label'=>'Ou pour le loyer',
                'choices' => Annonce::getListAutreMoyens(),
                'choice_label' => function ($value, $key, $index) {
                        return ucfirst($value);
                    }
                ));



            }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ESSABA\AnnonceBundle\Entity\Location',
            'validation_groups' => $this->validationGroups,
        ));
    }
}
