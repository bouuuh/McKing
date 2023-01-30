<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoyaltyController extends AbstractController
{
    #[Route('/fidelite', name: 'loyalty')]
    public function index(): Response
    {
        return $this->render('loyalty/index.html.twig');
    }
}
