<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\Postal;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountHistoryController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager =$entityManager;
    }

    #[Route('/mon-compte/mes-commandes', name: 'account_history')]
    public function index(): Response
    {

        $orders = $this->entityManager->getRepository(Order::class)->findSucessOrders($this->getUser());

        $cities = [];
        foreach ($orders as $key => $value) {
            $cities[] = $this->entityManager->getRepository(Postal::class)->findOneById($value->getCity()->getId());
        }

        return $this->render('account/history.html.twig', [
            'orders' => $orders,
            'cities' => $cities
        ]);
    }

    #[Route('/mon-compte/mes-commandes/{reference}', name: 'account_history_order')]
    public function show($reference): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);
        $order_details = $this->entityManager->getRepository(OrderDetails::class)->findAll();
        $product = $this->entityManager->getRepository(Product::class)->findAll();
        $cities = $this->entityManager->getRepository(Postal::class)->findAll();
        $menus = $this->entityManager->getRepository(Menu::class)->findAll();

        $order_history = [];
        foreach ($order_details as $value) {
           if(($value->getIdOrder()->getId()) == $order->getId()){
            $order_history[] = $value;
           }
        }
          


        return $this->render('account/history_order.html.twig', [
            'order' => $order,
            'order_history' => $order_history
        ]);
    }
}
