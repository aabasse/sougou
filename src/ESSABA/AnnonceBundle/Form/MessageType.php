<?php

namespace ESSABA\AnnonceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MessageType extends AbstractType
{
    protected $authorizationchecker;

    public function __construct(AuthorizationChecker $authorizationchecker)
    {
        $this->authorizationchecker = $authorizationchecker;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*if (!$this->authorizationchecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $builder->add('nom', TextType::class, array('label' => 'Votre nom'))
            ->add('email', EmailType::class, array('label' => 'Votre email'))
            ->add('tel', TextType::class, array('label' => 'Votre téléphone', 'required' => false));
        }*/

        $builder
            ->add('contenu', TextareaType::class, array('label' => 'Votre message'))
            //->add('envoyer', SubmitType::class, array('label_format'=>'html', 'label' => '<i class="fa fa-paper-plane-o"></i> Envoyer le message'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ESSABA\AnnonceBundle\Entity\Message'
        ));
    }
}
