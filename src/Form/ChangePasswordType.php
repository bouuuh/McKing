<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('old_password', PasswordType::class, [
                'label' => 'Mon mot de passe actuel',
                'mapped' => false, // Fait le lien, pour dire ne regarde pas si il existe dans mon entityUser
            ])

            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false, // Fait le lien, pour dire ne regarde pas si il existe dans mon entityUser
                'invalid_message' => 'Le mot passe et la confirmation doivent être identique', 
                'label' => 'Mon nouveau mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'Mon nouveau mot de passe'
                ],
                'second_options' => [
                    'label' => 'Confirmer votre nouveau mot de passe'
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label' => "Changer mon mot de passe",
                'attr' => [
                    'class' => 'submit-change-password'
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
