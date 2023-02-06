<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/mon-compte', name: 'account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/mon-compte/mes-informations', name: 'account_info')]
    public function info(): Response
    {
        return $this->render('account/info.html.twig');
    }
}
