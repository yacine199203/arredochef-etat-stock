<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('ref',IntegerType::class,[
            'label'=>'Réferance :',
            'attr'=>[ 
                'placeholder'=>'Réferance', 
            ],
        ])
        ->add('category',EntityType::class,[
            'label'=>'Catégorie :',
            'class'=>'App\Entity\Category',
            'choice_label'=>'categoryname',
            'choice_value'=>'id',
            'expanded'=>false,
            'multiple'=>false,
        ])
        ->add('libelle',TextType::class,[
            'label'=>'Produit :',
            'attr'=>[
                'placeholder'=>'Produit'    
            ]
        ])
        ->add('color',TextType::class,[
            'label'=>'Couleur :',
            'attr'=>[
                'placeholder'=>'Couleur'    
            ]
        ])
        ->add('qte',IntegerType::class,[
            'label'=>'Quantité :',
            'attr'=>[ 
                'placeholder'=>'Quantité', 
            ],
        ])
        ->add('alert',IntegerType::class,[
            'label'=>'Alerte quantité:',
            'attr'=>[ 
                'placeholder'=>'Alerte quantité', 
            ],
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
            'data_class' => Product::class,
        ]);
    }
}
