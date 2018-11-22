<?php

namespace Sfb\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title',TextType::class,array('label'=>'Titre'))
                ->add('description',TextareaType::class,array('label'=>'Description'))
                ->add('quantity',IntegerType::class,array('label'=>'Quantité'))
                ->add('pricettc',TextType::class,array('label'=>'Prix TTC'))
                ->add('types',ChoiceType::class,array(
                    'label'=>'Types',
                    'choices'=>array(
                        'soleil'=>'soleil',
                        'vue'=>'vue',
                        'sport'=>'sport'
                    ),
                    'choices_as_values'=>true,
                    'placeholder'=>'-- Sélectionnez un type --'
                ))
                ->add('genreProduit',EntityType::class,array(
                    'label'=>'Catégorie',
                    'required'=>true,
                    'class'=>'SfbSiteBundle:GenreProduit',
                    'choice_label'=>'name',
                    'expanded'=>true
                ))
                ->add('images', CollectionType::class, array(
                    'entry_type'   => ImageType::class,
                    'allow_add'    => true,
                    'allow_delete' => true
                ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sfb\SiteBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'produit';
    }
}
