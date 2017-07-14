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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use UserBundle\Form\UtilisateurType;
use UserBundle\Form\UtilisateurLessType;
use ESSABA\AnnonceBundle\Entity\SousCategorie;
use ESSABA\AnnonceBundle\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\RequestStack;
//use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

use ESSABA\AnnonceBundle\Entity\Annonce;

class AnnonceEditType extends AbstractType
{
    //protected $authorizationchecker;
    protected $validationGroups;
    protected $estOffre;

    //public function __construct(AuthorizationChecker $authorizationchecker, RequestStack $requestStack)
    public function __construct(RequestStack $requestStack)
    {
        //$this->authorizationchecker = $authorizationchecker;
        $this->validationGroups[] = "Edit";
        $request = $requestStack->getCurrentRequest();
        $routeName = $request->get('_route');
        $this->estOffre = $routeName == 'essaba_annonce_offre_modifier';

        if($this->estOffre)
        {
            $this->validationGroups[] = "EditOffre";
        }
        else
        {
            $this->validationGroups[] = "EditDemande";
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            //->add('utilisateur', UtilisateurType::class, array( 'label' => false ))
            ->add('titre')
            ->add('detail');
            if($this->estOffre)
            {
                $builder
                ->add('prix', MoneyType::class, array('scale' => 2, 'required' => false, 'attr'=> array('pattern' => '.*(^\d+([,|.]\d{1,2})?$).*') ))
                ->add('autreMoyen', ChoiceType::class, array('required' => false, 'label'=>'Ou pour le prix',
                'choices' => Annonce::getListAutreMoyens(),
                'choice_label' => function ($value, $key, $index) {
                    return ucfirst($value);
                }
                ))
                ;
            }
            $builder->add('commune', null, array('label'=>'Région'));

            
            /*
            // if user = moderat
            if ($this->authorizationchecker->isGranted('ROLE_ADMIN')) {
            $builder->add('sousCategorie', EntityType::class, array('class'=>'ESSABAAnnonceBundle:SousCategorie','group_by' => function($val, $key, $index) { return $val->getCategorie();
                },
                'label' => 'Catégorie'
                ));
            }

            */
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ESSABA\AnnonceBundle\Entity\Annonce',
            'validation_groups' => $this->validationGroups,
        ));
    }
}
