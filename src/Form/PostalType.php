<?php

namespace App\Form;

use App\Entity\Postal;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', ChoiceType::class, [
                'attr' => [
                    'class' => 'js_cities',
                   'placeholder' => 'Entrez votre code postal', 
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider' 
            ])
        ;
        $builder->get('code')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Postal::class,
        ]);
    }
}
