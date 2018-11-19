<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,array('label'=>'Nom'))
                ->add('lastname',TextType::class,array('label'=>'Prénom','required'=>false))
                ->add('username',TextType::class,array('label'=>'Nom d\'utilisateur'))
                ->add('email',EmailType::class,array('label'=>'Adresse e-mail'))
                ->add('password',RepeatedType::class,array(
                    'type' => PasswordType::class,
                    'invalid_message' => "Les deux mots ne sont pas identiques.",
                    'first_options'=>array('label'=>'Mot de passe'),
                    'second_options'=>array('label'=>'Répéter le mot de passe')
                ));
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
