<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date début *',
                'attr' => [
                    'placeholder' => 'Sélectionnez la date'
                ]
            ])
            ->add('position', TextType::class, [
                'label' => 'Position Tarifaire *',
                'attr' => [
                    'placeholder' => 'Saisir la position tarifaire'
                ]
            ])
            ->add('libelle', TextType::class, [
                'label' => 'Libellé  *',
                'attr' => [
                    'placeholder' => 'Saisir le libellé'
                ]
            ])
            ->add('tva', TextType::class, [
                'label' => 'TVA',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisir la TVA'
                ]
            ])
            ->add('ddi', TextType::class, [
                'label' => 'DDI',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisir le DDI'
                ]
            ])
            ->add('dcl', TextType::class, [
                'label' => 'DCL',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisir le DCL'
                ]
            ])
            ->add('dci', TextType::class, [
                'label' => 'DCI',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisir le DCI'
                ]
            ])
            ->add('unite', TextType::class, [
                'label' => 'Unité',
                'required' => false
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
