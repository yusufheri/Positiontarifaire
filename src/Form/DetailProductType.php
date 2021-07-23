<?php

namespace App\Form;

use App\Entity\Colonne;
use App\Entity\DetailProduct;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Valeur',
                'attr' => [
                    'placeholder' => 'Saisir la valeur pour la colonne sélectionnée'
                ]
            ])
            ->add('colonne', EntityType::class, [
                'label' => 'Colonne',
                'class' => Colonne::class,
                'placeholder' => 'Sélectionnez la colonne supplementaire',
                'choice_label' => function(Colonne $colonne) {
                    return $colonne->getName();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DetailProduct::class,
        ]);
    }
}
