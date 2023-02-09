<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private $entityManager;
    private  $adminUrlGenerator;


    public function __construct(EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        
            $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', 'fas fa-box-open')->linkToCrudAction('updatePreparation');
            $updateDelivery = Action::new('updateDelivery', 'Livrée', 'fas fa-truck')->linkToCrudAction('updateDelivery');
        
        return $actions
            ->add('index', 'detail')
            ->add('detail', $updatePreparation)
            ->add('detail', $updateDelivery);
    }

    public function updatePreparation(AdminContext $context) {
        $order = $context->getEntity()->getInstance();
        if ($order->getState() == 1) {
            $order->setState(2);
        $this->entityManager->flush();

        $this->addFlash('notice', '<span style="color:green;"><strong>La commande ' . $order->getReference() . 'est bien en cours de préparation</strong></span>');

        $url = $this->adminUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
        } else {
            $this->addFlash('notice', '<span style="color:red;"><strong>La commande ' . $order->getReference() . ' ne peut pas être en cours de préparation</strong></span>');
            $url = $this->adminUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
        }
        
        
    }
    public function updateDelivery(AdminContext $context) {
        $order = $context->getEntity()->getInstance();
        $order->setState(3);
        $this->entityManager->flush();

        $this->addFlash('notice', '<span style="color:green;"><strong>La commande ' . $order->getReference() . ' est bien livrée</strong></span>');

        $url = $this->adminUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        //On pourrait envoyer un mail ici
        //$mail = new Mail();
        //$mail->

        return $this->redirect($url);
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('date'),
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
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex()
        ];
    }
    
}
