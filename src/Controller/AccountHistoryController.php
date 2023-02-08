<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
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

        // dd($orders);

        return $this->render('account/history.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/mon-compte/mes-commandes/{reference}', name: 'account_history_order')]
    public function show($reference): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);

        $order_details = $this->entityManager->getRepository(OrderDetails::class)->findAll();
        
    
          


        return $this->render('account/history_order.html.twig', [
            'order' => $order
        ]);
    }
}
