<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categoryname',TextType::class,[
                'label'=>'Catégorie :',
                'attr'=>[
                    'placeholder'=>'Catégorie'
                ]
            ])
            ->add('depot',EntityType::class,[
                'label'=>'Dépôts :',
                'class'=>'App\Entity\Depot',
                'choice_label'=>'libelle',
                'choice_value'=>'slug',
                'expanded'=>false,
                'multiple'=>false,
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
            'data_class' => Category::class,
        ]);
    }
}
