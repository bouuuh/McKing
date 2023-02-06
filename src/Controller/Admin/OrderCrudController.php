<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('date'),
            ChoiceField::new('state', 'Status')->setChoices([
                'Non-payée' => 0,
                'Payée' => 1,
                'Prépation en cours' => 2,
                'Livrée' => 3
            ]),
            TextField::new('reference', 'Référence'),
            ChoiceField::new('place', 'Endroit')->setChoices([
                'Click & Collect' => 0,
                'Sur place' => 1,
            ]),
        ];
    }
    
}
