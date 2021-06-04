<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Votre nom de famille'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'placeholder'=>'Veuillez saisir votre adresse mail'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => new Length(6, 6, 25, null, null,null,"Cette chaîne est trop courte. Elle doit avoir au minimum 6 caractères.","Cette chaîne est trop longue. Elle doit avoir au maximum 25 caractères."),
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques.',
                'required' => true,
                'first_options' => [
                    'label' => 'Votre mot de passe', 
                    'attr' => ['placeholder' => 'Merci de saisir un mot de passe']
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe', 
                    'attr' => ['placeholder' => 'Merci de confirmer votre mot de passe']
                ]
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
