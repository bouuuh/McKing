<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/erreur/{stripeSessionId}', name: 'order_cancel')]
    public function index($stripeSessionId): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');

            $mail = new Mail();
            $content = "Bonjour ".$order->getuser()->getFirstName()."<br> Merci pour votre commande.";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(), 'Votre commande McKing à été annulée', 'Commande chez McKing', $content);
        }



        return $this->render('order_cancel/index.html.twig', [
            'order' => $order
        ]);
    }
}
