<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EditProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
