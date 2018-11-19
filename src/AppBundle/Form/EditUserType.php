<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class EditUserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,array('label'=>'Nom'))
                ->add('lastname',TextType::class,array('label'=>'Prénom','required'=>false))
                ->add('telephone',TextType::class,array('label'=>'Téléphone'))
                ->add('username',TextType::class,array('label'=>'Nom d\'utilisateur'))
                ->add('email',EmailType::class,array('label'=>'Adresse e-mail'));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'user';
    }


}
