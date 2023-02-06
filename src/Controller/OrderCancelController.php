<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    #[Route('/commande/erreur/', name: 'order_cancel')]
    public function index(): Response
    {
        return $this->render('order_cancel/index.html.twig', [
            'controller_name' => 'OrderCancelController',
        ]);
    }
}
