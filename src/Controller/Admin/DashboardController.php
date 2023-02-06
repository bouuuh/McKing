<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\IllustrationHome;
use App\Entity\Loyalty;
use App\Entity\Menu;
use App\Entity\Order;
use App\Entity\Postal;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    #[Route('/admin', name: 'admin')]
     public function index(): Response
     {
        
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
         //$adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        //return $this->redirect($adminUrlGenerator->setController(IllustrationHome::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
         //
          //if ('jane' === $this->getUser()->getUsername()) {
         //    return $this->redirect('...');
          //}
         // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
         // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
         //
         return $this->render('admin/index.html.twig');
     }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mcking');
    }

    public function configureMenuItems(): iterable
    {
        
        yield MenuItem::linkToCrud('Illustration Accueil', 'fas fa-image', IllustrationHome::class);
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Menus', 'fas fa-bag-shopping', Menu::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-tag', Product::class);
        yield MenuItem::linkToCrud('Order', 'fa-solid fa-cart-shopping', Order::class);
        yield MenuItem::linkToCrud('Fidélité', 'fa-solid fa-coins', Loyalty::class);
        yield MenuItem::linkToCrud('Codes Postaux', 'fa-solid fa-envelopes-bulk', Postal::class);
    }
}
