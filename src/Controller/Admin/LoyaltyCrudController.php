<?php

namespace App\Controller\Admin;

use App\Entity\Loyalty;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LoyaltyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Loyalty::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('product', 'Produit'),
            ChoiceField::new('numberPoints', 'Points')->setChoices([
                '15 points' => 0,
                '30 points' => 1,
                '60 points' => 2,
                '90 points' => 3
            ]),
            
        ];
    }
    
}
