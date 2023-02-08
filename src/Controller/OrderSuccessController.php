<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{

    private $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session){
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'order_success')]
    public function index($stripeSessionId, Cart $cart): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        // dd($order);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($order->getState() == 0) {

            $cart->remove();
            $this->session->set('list_item', null);
            $this->session->set('points', null); 

            $order->setState(1);
            $this->entityManager->flush();
           
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }

    #[Route('/commande/valider/{id}', name: 'order_success_loyalty')]
    public function show($id, Cart $cart): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneById($id);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($order->getState() == 0) {

            $this->session->set('points', null);

            $order->setState(1);
            $this->entityManager->flush();
           
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
