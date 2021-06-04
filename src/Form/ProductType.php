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
                'label' => 'Date début *'
            ])
            ->add('position', TextType::class, [
                'label' => 'Position Tarifaire *'
            ])
            ->add('libelle', TextType::class, [
                'label' => 'Libellé  *'
            ])
            ->add('tva', TextType::class, [
                'label' => 'TVA',
                'required' => false
            ])
            ->add('ddi', TextType::class, [
                'label' => 'DDI',
                'required' => false
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
