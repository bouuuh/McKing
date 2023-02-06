<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => new Length(
                    min: 2,
                    max: 30,
                    minMessage: "Il n'y a pas assez de caractères pour le champ 'Prénom'.",
                    maxMessage: "Il y a trop de caractères pour le champ 'Prénom'.",),
                'attr' => [
                    'class' => 'register-form'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => new Length(
                    min: 2,
                    max: 30,
                    minMessage: "Il n'y a pas assez de caractères pour le champ 'Nom'.",
                    maxMessage: "Il y a trop de caractères pour le champ 'Nom'."),
                'attr' => [
                    'class' => 'register-form'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => new Length(
                    min: 2,
                    max: 60,
                    minMessage: "Il n'y a pas assez de caractères pour le champ 'Email'.",
                    maxMessage: "Il y a trop de caractères pour le champ 'Email'."),
                'attr' => [
                    'class' => 'register-form'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez le mot de passe'],
                'invalid_message' => 'Les deux mot de passe ne sont pas identiques',
                'attr' => [
                    'class' => 'register-form'
                ]
                
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => [
                    'class' => 'register-form-submit'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
