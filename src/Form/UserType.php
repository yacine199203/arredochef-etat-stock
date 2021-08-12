<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('pseudo',TextType::class,[
            'label'=>'Pseudonyme :',
            'attr'=>[
                'placeholder'=>'Pseudonyme'    
            ]
        ])
        ->add('password',PasswordType::class,[
            'label'=>'Mot de passe :',
            'attr'=>[
                'placeholder'=>'Mot de passe'    
            ]
        ])
        ->add('roles',ChoiceType::class,[
            'choices' => [
               'Administrateur' => 'ROLE_ADMIN',
               'Gestionnaire de stock' => 'ROLE_MANAGER',
               'Commercial' => 'ROLE_USER',
            ],
            'expanded' => true,
            'multiple' => true,
            'label' => 'Rôles :'
        ])
        ->add('description',HiddenType::class,[
            'mapped'=> false,
            'required'=> false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
