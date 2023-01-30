<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
        ->add('burger', EntityType::class, [
            'class' => Product::class,
            'choice_label' => 'name',
            'expanded' => true,
            'required' => true,
        ])
        // ->add('snack', RadioType::class, [
        //     'label' => false,
        //     'required' => true,
        // ])
        // ->add('drink', RadioType::class, [
        //     'label' => false,
        //     'required' => true,
        // ])
        // ->add('sauce', RadioType::class, [
        //     'label' => false,
        //     'required' => true,
        // ])
        // ->add('dessert', RadioType::class, [
        //     'label' => false,
        //     'required' => true,
        // ])
        ->add('submit', SubmitType::class, [
            'label' => false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            
        ]);
    }
}
