<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountMentionsLegalesController extends AbstractController
{
    #[Route('/mon-compte/mentions-legales', name: 'account_ml')]
    public function index(): Response
    {
        return $this->render('account/ml.html.twig');
    }
}
